<?php

namespace Database\Seeders;

use App\Models\Tutorial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tutorial data with realistic eco-friendly content
        $tutorials = [
            [
                'title' => 'Comment créer un compost domestique',
                'description' => 'Apprenez à transformer vos déchets organiques en un fertilisant naturel pour votre jardin. Guide complet pour débutants.',
                'content' => 'Le compostage est une méthode écologique et économique de recyclage des déchets organiques. Ce tutoriel vous guidera à travers toutes les étapes nécessaires pour créer et maintenir un compost réussi.',
                'difficulty_level' => 'Beginner',
                'category' => 'Composting',
                'estimated_duration' => 45,
                'prerequisites' => 'Aucun prérequis nécessaire',
                'learning_outcomes' => 'À la fin de ce tutoriel, vous saurez comment créer un compost, quels déchets utiliser, et comment maintenir un compost sain.',
                'tags' => 'compost,jardinage,recyclage,écologie,bio',
                'status' => 'Published',
                'is_featured' => true,
            ],
            [
                'title' => 'Fabriquer des sacs réutilisables en tissu',
                'description' => 'Créez vos propres sacs réutilisables à partir de vieux vêtements. Réduisez votre consommation de plastique tout en pratiquant la couture.',
                'content' => 'Les sacs en plastique sont l\'un des principaux polluants de notre environnement. Dans ce tutoriel, découvrez comment transformer vos vieux tissus en sacs pratiques et durables.',
                'difficulty_level' => 'Intermediate',
                'category' => 'DIY',
                'estimated_duration' => 90,
                'prerequisites' => 'Bases de la couture, machine à coudre',
                'learning_outcomes' => 'Savoir coudre des sacs réutilisables, comprendre les techniques de recyclage textile, réduire sa consommation de plastique.',
                'tags' => 'couture,recyclage,DIY,textile,zéro déchet',
                'status' => 'Published',
                'is_featured' => true,
            ],
            [
                'title' => 'Installer un récupérateur d\'eau de pluie',
                'description' => 'Installation pas à pas d\'un système de récupération d\'eau de pluie pour arroser votre jardin et économiser l\'eau potable.',
                'content' => 'L\'eau est une ressource précieuse. Apprenez à installer un système simple et efficace pour collecter et utiliser l\'eau de pluie.',
                'difficulty_level' => 'Intermediate',
                'category' => 'Water',
                'estimated_duration' => 120,
                'prerequisites' => 'Outils de base en bricolage',
                'learning_outcomes' => 'Installer un système de récupération, comprendre le cycle de l\'eau, économiser sur votre facture.',
                'tags' => 'eau,économie,jardin,écologie,bricolage',
                'status' => 'Published',
                'is_featured' => false,
            ],
            [
                'title' => 'Créer des produits ménagers naturels',
                'description' => 'Recettes simples pour fabriquer vos propres produits d\'entretien écologiques : nettoyant multi-usage, lessive, liquide vaisselle.',
                'content' => 'Les produits ménagers industriels contiennent souvent des substances nocives. Découvrez comment les remplacer par des alternatives naturelles, efficaces et économiques.',
                'difficulty_level' => 'Beginner',
                'category' => 'General',
                'estimated_duration' => 30,
                'prerequisites' => 'Aucun',
                'learning_outcomes' => 'Fabriquer des produits ménagers sains, réduire les déchets chimiques, économiser de l\'argent.',
                'tags' => 'produits naturels,écologie,maison,zéro déchet,économie',
                'status' => 'Published',
                'is_featured' => true,
            ],
            [
                'title' => 'Démarrer un potager urbain sur balcon',
                'description' => 'Cultivez vos propres légumes même en appartement ! Guide complet pour créer un potager productif sur votre balcon.',
                'content' => 'Même avec un espace limité, il est possible de cultiver ses légumes. Ce tutoriel vous montre comment optimiser votre balcon pour une production alimentaire durable.',
                'difficulty_level' => 'Beginner',
                'category' => 'Gardening',
                'estimated_duration' => 60,
                'prerequisites' => 'Un balcon ou terrasse, accès au soleil',
                'learning_outcomes' => 'Créer un potager urbain, choisir les bonnes plantes, entretenir vos cultures.',
                'tags' => 'potager,urbain,balcon,légumes,agriculture urbaine',
                'status' => 'Published',
                'is_featured' => false,
            ],
            [
                'title' => 'Réparer vos appareils électroniques',
                'description' => 'Apprenez les bases de la réparation électronique pour prolonger la vie de vos appareils et réduire les déchets électroniques.',
                'content' => 'Les déchets électroniques sont un problème majeur. Découvrez comment diagnostiquer et réparer les pannes courantes de vos appareils.',
                'difficulty_level' => 'Advanced',
                'category' => 'Recycling',
                'estimated_duration' => 180,
                'prerequisites' => 'Notions d\'électricité, outils de base',
                'learning_outcomes' => 'Diagnostiquer les pannes, remplacer les composants, prolonger la durée de vie des appareils.',
                'tags' => 'électronique,réparation,recyclage,économie circulaire',
                'status' => 'Published',
                'is_featured' => false,
            ],
            [
                'title' => 'Optimiser la consommation énergétique de votre maison',
                'description' => 'Conseils pratiques et modifications simples pour réduire significativement votre consommation d\'énergie.',
                'content' => 'L\'efficacité énergétique commence par de petits changements. Apprenez à identifier et corriger les sources de gaspillage énergétique.',
                'difficulty_level' => 'Intermediate',
                'category' => 'Energy',
                'estimated_duration' => 75,
                'prerequisites' => 'Accès aux compteurs et installations électriques',
                'learning_outcomes' => 'Identifier les pertes d\'énergie, mettre en place des solutions, réduire sa facture énergétique.',
                'tags' => 'énergie,économie,isolation,efficacité énergétique',
                'status' => 'Published',
                'is_featured' => true,
            ],
            [
                'title' => 'Fabriquer du papier recyclé maison',
                'description' => 'Transformez vos vieux papiers en nouveau papier artisanal. Activité ludique et écologique pour toute la famille.',
                'content' => 'Le recyclage du papier à la maison est simple et amusant. Créez du papier unique tout en réduisant vos déchets.',
                'difficulty_level' => 'Beginner',
                'category' => 'Recycling',
                'estimated_duration' => 45,
                'prerequisites' => 'Vieux papiers, mixer, cadre',
                'learning_outcomes' => 'Comprendre le processus de recyclage, créer du papier artisanal, activité créative.',
                'tags' => 'papier,recyclage,artisanat,DIY,créativité',
                'status' => 'Draft',
                'is_featured' => false,
            ],
        ];

        // Get a user to assign as creator (assuming user ID 1 exists)
        $creator = User::first() ?? User::factory()->create();

        foreach ($tutorials as $index => $tutorialData) {
            // Create tutorial
            $tutorial = Tutorial::create([
                'title' => $tutorialData['title'],
                'slug' => Str::slug($tutorialData['title']),
                'description' => $tutorialData['description'],
                'content' => $tutorialData['content'],
                'thumbnail_image' => "https://picsum.photos/800/600?random=" . ($index + 1),
                'intro_video_url' => rand(0, 1) ? "https://www.youtube.com/watch?v=dQw4w9WgXcQ" : null,
                'difficulty_level' => $tutorialData['difficulty_level'],
                'category' => $tutorialData['category'],
                'estimated_duration' => $tutorialData['estimated_duration'],
                'views_count' => rand(50, 2500),
                'completion_count' => rand(10, 300),
                'average_rating' => round(rand(35, 50) / 10, 2),
                'total_ratings' => rand(5, 100),
                'prerequisites' => $tutorialData['prerequisites'],
                'learning_outcomes' => $tutorialData['learning_outcomes'],
                'tags' => $tutorialData['tags'],
                'status' => $tutorialData['status'],
                'is_featured' => $tutorialData['is_featured'],
                'created_by' => $creator->id,
                'published_at' => $tutorialData['status'] === 'Published' ? now()->subDays(rand(1, 30)) : null,
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ]);

            $this->command->info("✅ Created tutorial: {$tutorial->title}");
        }

        $this->command->info("🎉 " . count($tutorials) . " tutorials created successfully!");
    }
}
