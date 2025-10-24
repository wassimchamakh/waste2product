<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumVote;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create sample users
        $users = User::all();
        
        if ($users->count() < 3) {
            $this->command->warn('Please create at least 3 users first!');
            return;
        }

        // Sample posts data
        $posts = [
            [
                'title' => 'Comment recycler efficacement le plastique à la maison?',
                'body' => "Bonjour à tous!\n\nJe cherche des conseils pratiques pour mieux recycler mes déchets plastiques à la maison. Quelles sont les meilleures techniques? Y a-t-il des types de plastique qu'on peut transformer en objets utiles?\n\nMerci pour vos suggestions!",
                'status' => 'published',
            ],
            [
                'title' => 'Projet DIY: Transformer des bouteilles en verre en vases décoratifs',
                'body' => "Je viens de terminer un super projet! J'ai transformé 10 bouteilles en verre en magnifiques vases.\n\nMéthode:\n1. Couper les bouteilles avec de la ficelle et de l'alcool\n2. Poncer les bords\n3. Décorer avec de la peinture acrylique\n\nRésultat incroyable! Qui veut essayer?",
                'status' => 'published',
            ],
            [
                'title' => 'Quels sont les meilleurs centres de recyclage à Tunis?',
                'body' => "Je recherche des centres de recyclage fiables dans la région de Tunis. Quelqu'un a des recommandations?\n\nJe veux surtout recycler:\n- Électronique\n- Verre\n- Métaux\n\nMerci!",
                'status' => 'published',
            ],
            [
                'title' => 'Guide complet: Compostage des déchets organiques',
                'body' => "Le compostage est essentiel pour réduire nos déchets! Voici mon guide complet basé sur 2 ans d'expérience:\n\n1. Choisir le bon composteur\n2. Équilibrer matières vertes et brunes\n3. Maintenir l'humidité\n4. Retourner régulièrement\n\nQui pratique déjà le compostage? Partagez vos astuces!",
                'status' => 'published',
            ],
        ];

        $createdPosts = [];
        foreach ($posts as $index => $postData) {
            $user = $users->random();
            $post = ForumPost::create([
                'title' => $postData['title'],
                'body' => $postData['body'],
                'status' => $postData['status'],
                'user_id' => $user->id,
                'views_count' => rand(10, 200),
            ]);

            $createdPosts[] = $post;

            // Pin the first post
            if ($index === 0) {
                $post->update(['is_pinned' => true]);
            }

            // Update user posts count
            $user->increment('posts_count');
            $user->increment('reputation', 5);
            $user->updateBadge();
        }

        // Add comments to posts
        $comments = [
            "Excellente question! J'utilise un système de tri à 3 bacs depuis 6 mois et ça change tout.",
            "Merci pour ce partage! Je vais essayer ce weekend avec mes enfants.",
            "Super idée! Quelqu'un sait où acheter les outils nécessaires à Tunis?",
            "J'ai essayé cette technique et le résultat est magnifique. Je recommande!",
            "Très instructif, merci! Combien de temps faut-il pour obtenir du compost utilisable?",
        ];

        foreach ($createdPosts as $post) {
            $numComments = rand(2, 5);
            $commentUsers = $users->random($numComments);

            foreach ($commentUsers as $commentUser) {
                if ($commentUser->id === $post->user_id) continue; // Skip post author

                $comment = ForumComment::create([
                    'forum_post_id' => $post->id,
                    'user_id' => $commentUser->id,
                    'body' => $comments[array_rand($comments)],
                ]);

                // Update user comments count
                $commentUser->increment('comments_count');
                $commentUser->increment('reputation', 2);
                $commentUser->updateBadge();

                // 30% chance to mark as best answer (for top-level comments only)
                if (rand(1, 100) <= 30) {
                    $comment->markAsBestAnswer();
                }
            }
        }

        // Add votes
        foreach ($createdPosts as $post) {
            $voters = $users->random(rand(3, $users->count()));
            
            foreach ($voters as $voter) {
                if ($voter->id === $post->user_id) continue; // Can't vote own post

                $vote = rand(0, 1) ? 1 : -1; // 50/50 chance
                
                ForumVote::create([
                    'user_id' => $voter->id,
                    'votable_type' => ForumPost::class,
                    'votable_id' => $post->id,
                    'vote' => $vote,
                ]);
            }
        }

        $this->command->info('✅ Forum seeded successfully!');
        $this->command->info("Created " . count($createdPosts) . " posts with comments and votes");
    }
}
