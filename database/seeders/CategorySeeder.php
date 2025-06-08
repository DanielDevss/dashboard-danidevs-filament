<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            "Landing Page",
            "Portafolio",
            "Inmobiliaria",
            "Sitio web",
            "Panel administrativo",
            "Aplicación web",
            "Blog",
            "API Rest",
            "E-commerce",
            "Front-End",
            "Back-End",
            "Full-Stack",
        ];

        foreach($categories as $category) {
            Category::firstOrCreate(["name" => $category],["name" => $category]);
        }
    }
}
