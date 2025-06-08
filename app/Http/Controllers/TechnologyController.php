<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function index() {
        try {
            $data = Technology::orderBy("name", "asc")->get()->map(fn($record) => [
                "id" => $record->id,
                "name" => $record->name,
                "projects_count" => $record->projects()->count(),
                "posts_count" => $record->posts()->count(),
            ]);
            return response()->json($data);
        } catch (\Exception $err) {
            return response()->json(["error"=>$err->getMessage()]);
        }
    }
}
