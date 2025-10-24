<?php

namespace Database\Seeders;

use App\Models\Tutorial;
use App\Models\TutoComment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutoCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Realistic comment templates
        $positiveComments = [
            "Excellent tutoriel ! Très clair et facile à suivre. J'ai réussi du premier coup ! 👍",
            "Merci beaucoup pour ce guide détaillé. Les explications sont parfaites.",
            "Super tutoriel, exactement ce que je cherchais. Les photos aident vraiment.",
            "Très bien expliqué ! J'ai pu le faire avec mes enfants, ils ont adoré.",
            "Tutoriel au top ! Très pédagogique et accessible aux débutants.",
            "Génial ! J'ai économisé beaucoup d'argent grâce à ce tutoriel.",
            "Bravo pour ce travail ! Instructions claires et résultat parfait.",
            "Merci pour ce partage ! C'est vraiment utile et écologique.",
            "Parfait ! Les conseils sur les erreurs à éviter sont très précieux.",
            "Tutoriel excellent ! La vidéo en complément est un vrai plus.",
        ];

        $neutralComments = [
            "Tutoriel intéressant, mais j'aurais aimé plus de détails sur certaines étapes.",
            "Bon tutoriel dans l'ensemble. Quelques points pourraient être améliorés.",
            "Instructions correctes. Le résultat est conforme à ce qui est annoncé.",
            "Tutoriel bien fait, même si j'ai eu quelques difficultés au début.",
            "Pas mal, mais je pense qu'il manque des informations sur le matériel nécessaire.",
            "Correct pour débuter. Les débutants apprécieront la simplicité.",
            "Bon tutoriel, mais la durée estimée est un peu courte selon moi.",
        ];

        $questionsComments = [
            "Est-ce que je peux utiliser un autre type de matériau pour cette étape ?",
            "Combien de temps cela prend-il en moyenne ? L'estimation semble courte.",
            "Où peut-on trouver ce type de matériel ? Des recommandations ?",
            "Y a-t-il des alternatives moins coûteuses pour certains matériaux ?",
            "Peut-on adapter ce tutoriel pour une version plus petite ?",
            "Est-ce que cela fonctionne aussi en hiver ?",
            "Combien coûte approximativement l'ensemble du matériel ?",
            "Faut-il des compétences particulières pour réaliser ce projet ?",
        ];

        $replies = [
            "Merci pour votre retour ! Je suis content que ça vous aide.",
            "Bonne question ! Je vais ajouter cette information dans le tutoriel.",
            "Oui, tout à fait ! Vous pouvez adapter selon vos besoins.",
            "Vous avez raison, je vais préciser ce point.",
            "N'hésitez pas si vous avez d'autres questions !",
            "Merci pour cette suggestion d'amélioration !",
        ];

        // Get all tutorials and users
        $tutorials = Tutorial::where('status', 'Published')->get();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn("⚠️  No users found. Creating a default user...");
            $users = collect([User::factory()->create()]);
        }

        foreach ($tutorials as $tutorial) {
            // Random number of comments per tutorial (2-8)
            $numComments = rand(2, 8);
            
            for ($i = 0; $i < $numComments; $i++) {
                $user = $users->random();
                
                // Randomly choose comment type
                $rand = rand(0, 100);
                if ($rand < 60) {
                    // 60% positive comments
                    $commentText = $positiveComments[array_rand($positiveComments)];
                    $rating = rand(4, 5);
                    $status = 'Approved';
                } elseif ($rand < 85) {
                    // 25% questions/neutral
                    $commentText = rand(0, 1) 
                        ? $questionsComments[array_rand($questionsComments)]
                        : $neutralComments[array_rand($neutralComments)];
                    $rating = rand(3, 4);
                    $status = 'Approved';
                } else {
                    // 15% pending/moderate
                    $commentText = $positiveComments[array_rand($positiveComments)];
                    $rating = rand(3, 5);
                    $status = rand(0, 1) ? 'Pending' : 'Approved';
                }

                $comment = TutoComment::create([
                    'tutorial_id' => $tutorial->id,
                    'user_id' => $user->id,
                    'parent_comment_id' => null,
                    'comment_text' => $commentText,
                    'rating' => rand(0, 2) == 0 ? $rating : null, // 33% chance of rating
                    'helpful_count' => rand(0, 15),
                    'is_helpful_by_users' => json_encode($users->random(rand(0, 3))->pluck('id')->toArray()),
                    'status' => $status,
                    'moderation_note' => null,
                    'moderated_by' => null,
                    'moderated_at' => $status === 'Approved' ? now()->subDays(rand(1, 20)) : null,
                    'is_edited' => rand(0, 10) == 0, // 10% edited
                    'edited_at' => rand(0, 10) == 0 ? now()->subDays(rand(1, 5)) : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                // 30% chance of having 1-2 replies
                if (rand(0, 100) < 30 && $status === 'Approved') {
                    $numReplies = rand(1, 2);
                    
                    for ($r = 0; $r < $numReplies; $r++) {
                        $replyUser = $users->random();
                        
                        TutoComment::create([
                            'tutorial_id' => $tutorial->id,
                            'user_id' => $replyUser->id,
                            'parent_comment_id' => $comment->id,
                            'comment_text' => $replies[array_rand($replies)],
                            'rating' => null, // Replies don't have ratings
                            'helpful_count' => rand(0, 5),
                            'is_helpful_by_users' => json_encode($users->random(rand(0, 2))->pluck('id')->toArray()),
                            'status' => 'Approved',
                            'moderation_note' => null,
                            'moderated_by' => null,
                            'moderated_at' => now()->subDays(rand(1, 15)),
                            'is_edited' => false,
                            'edited_at' => null,
                            'created_at' => $comment->created_at->addHours(rand(1, 48)),
                        ]);
                    }
                }
            }

            // Update tutorial's average rating
            $tutorial->updateAverageRating();
            
            $this->command->info("✅ Created comments for: {$tutorial->title}");
        }

        // Add some flagged comments for moderation testing
        $flaggedComments = [
            "Ce tutoriel est nul, ça ne marche pas !",
            "Perte de temps, inutile.",
            "Site spam, ne suivez pas ces instructions.",
        ];

        foreach ($tutorials->take(2) as $tutorial) {
            TutoComment::create([
                'tutorial_id' => $tutorial->id,
                'user_id' => $users->random()->id,
                'parent_comment_id' => null,
                'comment_text' => $flaggedComments[array_rand($flaggedComments)],
                'rating' => 1,
                'helpful_count' => 0,
                'is_helpful_by_users' => json_encode([]),
                'status' => 'Flagged',
                'moderation_note' => 'Signalé par plusieurs utilisateurs',
                'moderated_by' => null,
                'moderated_at' => null,
                'is_edited' => false,
                'edited_at' => null,
                'created_at' => now()->subDays(rand(1, 10)),
            ]);
        }

        $this->command->info("🎉 All tutorial comments created successfully!");
        $this->command->info("📊 Statistics:");
        $this->command->info("   - Total comments: " . TutoComment::count());
        $this->command->info("   - Approved: " . TutoComment::where('status', 'Approved')->count());
        $this->command->info("   - Pending: " . TutoComment::where('status', 'Pending')->count());
        $this->command->info("   - Flagged: " . TutoComment::where('status', 'Flagged')->count());
        $this->command->info("   - With ratings: " . TutoComment::whereNotNull('rating')->count());
        $this->command->info("   - Replies: " . TutoComment::whereNotNull('parent_comment_id')->count());
    }
}