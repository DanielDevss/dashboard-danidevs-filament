<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        try {
           $data = Category::orderBy("name", "asc")->get()->map(fn ($record) => [
                "id" => $record->id,
                "name" => $record->name,
                "projects_count" => $record->projects()->count()
           ]); 
           return response()->json($data);
        } catch (\Exception $err) {
            return response()->json([
                "error" => $err->getMessage()
            ]);
        }
    }
}
