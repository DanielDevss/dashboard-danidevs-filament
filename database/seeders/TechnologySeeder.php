<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            "HTML",
            "CSS",
            "Javascript",
            "PHP",
            "MySQL",
            "SQL Server",
            "Nodejs",
            "React",
            "Astro",
            "Laravel",
            "Next.js",
            "Express",
            "Typescript",
            "Bootstrap",
            "Tailwindcss",
            "Stripe",
        ];

        foreach ($technologies as $tech) {
            Technology::firstOrCreate(["name" => $tech], ["name" => $tech]);
        }
    }
}
