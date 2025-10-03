<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $projects = Project::all();

        foreach($projects as $project) {
            foreach(range(1, rand(2,5)) as $stepNumber) {
                ProjectStep::create([
                    'project_id' => $project->id,
                    'step_number' => $stepNumber,
                    'title' => "Étape $stepNumber pour $project->title",
                    'description' => "Description de l'étape $stepNumber",
                    'materials_needed' => "Liste des matériaux pour l'étape $stepNumber",
                    'tools_required' => "Outils nécessaires pour l'étape $stepNumber",
                    'duration' => rand(30,120) . " min",
                ]);
            }
        }
    }
}
