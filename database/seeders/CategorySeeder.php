<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{

    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bois & Palettes',
                'description' => 'Palettes européennes, planches, caisses en bois, meubles anciens',
                'icon' => 'fas fa-tree',
                'color' => '#8B4513',
            ],
            [
                'name' => 'Plastique',
                'description' => 'Bouteilles, contenants plastiques, bidons, sacs',
                'icon' => 'fas fa-bottle-water',
                'color' => '#1E90FF',
            ],
            [
                'name' => 'Métal',
                'description' => 'Canettes, ferraille, pièces métalliques, outils usagés',
                'icon' => 'fas fa-screwdriver-wrench',
                'color' => '#708090',
            ],
            [
                'name' => 'Textile',
                'description' => 'Vêtements, tissus, rideaux, chutes textiles',
                'icon' => 'fas fa-shirt',
                'color' => '#FF69B4',
            ],
            [
                'name' => 'Électronique',
                'description' => 'Appareils électroniques, composants, câbles',
                'icon' => 'fas fa-laptop',
                'color' => '#4169E1',
            ],
            [
                'name' => 'Verre',
                'description' => 'Bouteilles en verre, bocaux, vitres',
                'icon' => 'fas fa-wine-bottle',
                'color' => '#00CED1',
            ],
            [
                'name' => 'Papier & Carton',
                'description' => 'Cartons, journaux, livres, magazines',
                'icon' => 'fas fa-box',
                'color' => '#D2691E',
            ],
            [
                'name' => 'Pneus',
                'description' => 'Pneus usagés de voitures, motos, vélos',
                'icon' => 'fas fa-circle',
                'color' => '#2F4F4F',
            ],
            [
                'name' => 'Mobilier',
                'description' => 'Meubles anciens, chaises, tables à restaurer',
                'icon' => 'fas fa-couch',
                'color' => '#CD853F',
            ],
            [
                'name' => 'Jardin',
                'description' => 'Pots, outils de jardin, déchets verts',
                'icon' => 'fas fa-seedling',
                'color' => '#228B22',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ ' . count($categories) . ' catégories créées avec succès !');
    }
}

