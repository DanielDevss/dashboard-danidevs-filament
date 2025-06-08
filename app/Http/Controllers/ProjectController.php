<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Project::query()
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

            $projects = $query->orderBy('title', 'asc')->paginate();

            $data = [
                "last_page" => $projects->lastPage(),
                "current_page" => $projects->currentPage(),
                "data" => $projects->map(fn($record) => [
                    "slug" => $record->slug,
                    "thumb" => config("app.url") . "/storage/" . $record->thumb,
                    "title" => $record->title,
                    "description" => $record->description,
                    "technologies" => $record->technologies->map(fn($tech) => $tech->name),
                    "categories" => $record->categories->map(fn($cat) => $cat->name),
                ])
            ];

            return response()->json($data);
        } catch (\Exception $err) {
            return response()->json(["error" => $err->getMessage()], $err->getCode() ?: 500);
        }
    }
}
