<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryPostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\WorkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get("/posts/categories", [CategoryPostController::class, "index"]);
Route::get("/projects/categories", [CategoryController::class, "index"]);
Route::get("/technologies", [TechnologyController::class, "index"]);
Route::get("/posts", [PostController::class, "index"]);
Route::get("/posts/{slug}", [PostController::class, "show"]);
Route::get("/projects", [ProjectController::class, "index"]);
Route::get("/works", [WorkController::class, "index"]);


Route::post('/admin/optimize', function () {
    Artisan::call('optimize');
    return response()->json(['message' => 'Optimización ejecutada con éxito']);
});

Route::post('/admin/storage-link', function () {
    Artisan::call('storage:link');
    return response()->json(['message' => 'Enlace simbólico creado con éxito']);
});