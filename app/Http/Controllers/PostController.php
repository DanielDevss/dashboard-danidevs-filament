<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Post::query()
                ->with(['categories', 'technologies']);

            // 🔍 Búsqueda global
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('categories', fn($q) =>
                        $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('technologies', fn($q) =>
                        $q->where('name', 'like', "%{$search}%"));
                });
            }

            // 🏷 Filtro por categoría
            if ($category = $request->input('category')) {
                $query->whereHas('categories', fn($q) =>
                $q->where('id', $category));
            }

            // 🧪 Filtro por tecnología
            if ($tech = $request->input('technology')) {
                $query->whereHas('technologies', fn($q) =>
                $q->where('id', $tech));
            }

            $posts = $query->latest()
                ->select(['id', 'slug', 'thumb', 'title', 'description', 'created_at'])
                ->paginate();

            $data = [
                "last_page" => $posts->lastPage(),
                "current_page" => $posts->currentPage(),
                "data" => $posts->map(fn($record) => [
                    "slug" => $record->slug,
                    "thumb" => config("app.url") . "/storage/" . $record->thumb,
                    "title" => $record->title,
                    "description" => $record->description,
                    "created_at" => $record->created_at
                ])
            ];

            return response()->json($data);
        } catch (\Exception $err) {
            return response()->json(["error" => $err->getMessage()], $err->getCode() ?: 500);
        }
    }


    public function show(string $slug)
    {
        try {
            $post = Post::where("slug", "=", $slug)->firstOrFail();
            return response()->json([
                "title" => $post->title,
                "description" => $post->description,
                "thumb" => asset("/storage/" . $post->thumb),
                "banner" => asset("/storage/" . $post->banner),
                "content" => $post->content,
                "categories" => $post->categories()->get()->map(fn($record) => [
                    "name" => $record->name,
                    "slug" => $record->slug
                ]),
                "technologies" => $post->technologies()->get()->map(fn($record) => $record->name),
                "created_at" => $post->created_at,
                "updated_at" => $post->updated_at,
            ]);
        } catch (\Exception $err) {
            return response()->json([
                "error" => $err->getMessage()
            ], $err->getCode());
        }
    }
}
