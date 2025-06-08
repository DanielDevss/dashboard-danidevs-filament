<?php

namespace Database\Seeders;

use App\Models\CategoryPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "name" => "Datos interesantes",
                "description" => "Datos interesantes que pueden existir en el mundo del desarrollo.",
            ],
            [
                "name" => "Desarrollo web",
                "description" => "Publicaciones referentes al mundo del desarrollo web.",
            ],
            [
                "name" => "Buenas prácticas",
                "description" => "Aprende buenas prácticas con estos consejos que dejado para tí.",
            ],
            [
                "name" => "Novedades",
                "description" => "Informate acerca de las novedades que hay en este rubro",
            ],
            [
                "name" => "Práctica para programar",
                "description" => "Descubre formas de prácticar tus hábilidades",
            ],
            [
                "name" => "Tips de programación",
                "description" => "Descubre estos increíbles tips en programación",
            ],
            [
                "name" => "Recursos para desarrolladores",
                "description" => "Descubre estos increíbles recursos que puedes usar mientras desarrollas",
            ],
            [
                "name" => "Recursos para diseñadores",
                "description" => "Descubre estos increíbles recursos que puedes usar mientras diseñas",
            ],
        ];

        foreach($categories as $category) {
            CategoryPost::firstOrCreate(
                ["name" => $category["name"]],
                [
                    "name" => $category["name"],
                    "slug" => Str::slug($category["name"]),
                    "description" => $category["description"]
                ]
            );
        }
    }
}
