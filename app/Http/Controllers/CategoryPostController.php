<?php

namespace App\Http\Controllers;

use App\Models\CategoryPost;
use Illuminate\Http\Request;

class CategoryPostController extends Controller
{
    public function index()
    {
        try {
            
            $data = CategoryPost::orderBy("name", "asc")->get()->map(fn($record) => [
                "name" => $record->name,
                "slug" => $record->slug,
                "description" => $record->description,
                "posts_count" => $record->posts()->count(),
            ]);

            return response()->json($data);
        } catch (\Exception $err) {
            return response()->json(["error" => $err->getMessage()]);
        }
    }
}
