<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        foreach(range(1,10) as $i) {
            Project::create([
                'title' => "Projet exemple $i",
                'description' => "Description détaillée pour le projet $i",
                'difficulty_level' => ['facile','intermédiaire','difficile'][rand(0,2)],
                'estimated_time' => rand(1,10) . ' heures',
                'impact_score' => rand(5,50),
                'photo' => null,
                'status' => 'published',
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
            ]);
        }
    }
}
