<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\User;
use Carbon\Carbon;
use Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = \App\Models\Event::all();
        $users = User::all();

        if ($events->isEmpty() || $users->isEmpty()) {
            $this->command->error('❌ Veuillez d\'abord créer des événements et des utilisateurs !');
            return;
        }

        $participantsCount = 0;

        foreach ($events as $event) {
            // Nombre aléatoire de participants (entre 30% et 100% de la capacité)
            $numParticipants = rand(
                (int)($event->max_participants * 0.3), 
                min($event->max_participants, $users->count())
            );

            // Sélectionner des utilisateurs aléatoires
            $selectedUsers = $users->random(min($numParticipants, $users->count()));

            foreach ($selectedUsers as $user) {
                // Éviter que l'organisateur soit aussi participant
                if ($user->id === $event->user_id) {
                    continue;
                }

                // Date d'inscription aléatoire entre la création de l'événement et maintenant
                $registrationDate = Carbon::create($event->created_at)
                    ->addDays(rand(0, max(1, now()->diffInDays($event->created_at))));

                // Déterminer le statut en fonction de la date de l'événement
                $attendanceStatus = 'registered';
                
                if ($event->isPast()) {
                    // Pour les événements passés : soit présent, soit absent
                    $attendanceStatus = rand(0, 100) < 85 ? 'attended' : 'absent';
                } elseif ($event->isOngoing()) {
                    $attendanceStatus = 'confirmed';
                } else {
                    // Pour les événements futurs : registered ou confirmed
                    $attendanceStatus = rand(0, 100) < 70 ? 'confirmed' : 'registered';
                }

                // Générer feedback et rating pour les participants présents
                $feedback = null;
                $rating = null;

                if ($attendanceStatus === 'attended' && rand(0, 100) < 60) {
                    $feedbacks = [
                        "Excellent événement ! J'ai beaucoup appris et l'ambiance était super.",
                        "Très instructif, les animateurs étaient compétents et disponibles.",
                        "Bonne organisation, je recommande vivement !",
                        "Expérience enrichissante, j'ai hâte de participer au prochain.",
                        "Parfait pour découvrir de nouvelles techniques de recyclage.",
                        "Équipe accueillante et professionnelle, merci !",
                        "Très pratique et concret, exactement ce qu'il me fallait.",
                        "Belle initiative pour l'environnement, continuez !",
                        "J'ai rencontré des gens formidables partageant les mêmes valeurs.",
                        "Atelier bien structuré avec de bonnes explications.",
                    ];

                    $feedback = $feedbacks[array_rand($feedbacks)];
                    $rating = rand(3, 5); // Note entre 3 et 5 étoiles
                }

                Participant::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'registration_date' => $registrationDate,
                    'attendance_status' => $attendanceStatus,
                    'feedback' => $feedback,
                    'rating' => $rating,
                    'email_sent' => true,
                ]);

                $participantsCount++;
            }
        }

        $this->command->info('✅ ' . $participantsCount . ' inscriptions créées avec succès !');
        
        // Afficher quelques statistiques
        $this->command->info('');
        $this->command->info('📊 Statistiques :');
        $this->command->info('   - Confirmés : ' . Participant::confirmed()->count());
        $this->command->info('   - Présents : ' . Participant::attended()->count());
        $this->command->info('   - Absents : ' . Participant::absent()->count());
        $this->command->info('   - Avec feedback : ' . Participant::withFeedback()->count());
    }
}
