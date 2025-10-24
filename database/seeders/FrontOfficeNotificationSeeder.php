<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class FrontOfficeNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first regular user (not admin)
        $user = User::where('is_admin', false)->first();

        if (!$user) {
            $this->command->warn('No regular user found. Please create a user first.');
            return;
        }

        $notifications = [
            // Project notifications
            [
                'user_id' => $user->id,
                'type' => 'project',
                'icon' => 'fa-check-circle',
                'color' => 'green',
                'title' => 'Projet approuvé !',
                'message' => 'Félicitations ! Votre projet "Table en Palettes" a été approuvé et est maintenant visible publiquement.',
                'link' => '/projects/1',
                'is_read' => false,
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $user->id,
                'type' => 'comment',
                'icon' => 'fa-comment',
                'color' => 'yellow',
                'title' => 'Nouveau commentaire sur votre projet',
                'message' => 'Ahmed Ben Ali a commenté votre projet "Table en Palettes".',
                'link' => '/projects/1',
                'is_read' => false,
                'created_at' => now()->subHours(5),
            ],

            // Event notifications
            [
                'user_id' => $user->id,
                'type' => 'event',
                'icon' => 'fa-calendar-check',
                'color' => 'purple',
                'title' => 'Inscription confirmée',
                'message' => 'Votre inscription à l\'événement "Atelier de Compostage" a été confirmée.',
                'link' => '/events/1',
                'is_read' => true,
                'read_at' => now()->subHours(3),
                'created_at' => now()->subHours(6),
            ],
            [
                'user_id' => $user->id,
                'type' => 'event',
                'icon' => 'fa-calendar',
                'color' => 'purple',
                'title' => 'Rappel : Événement demain',
                'message' => 'L\'événement "Repair Café" aura lieu demain à 14h00.',
                'link' => '/events/2',
                'is_read' => false,
                'created_at' => now()->subHours(8),
            ],
            [
                'user_id' => $user->id,
                'type' => 'message',
                'icon' => 'fa-envelope',
                'color' => 'pink',
                'title' => 'Message de l\'organisateur',
                'message' => 'Nouveau message concernant "Atelier de Compostage" : N\'oubliez pas d\'apporter vos déchets organiques !',
                'link' => '/events/1',
                'is_read' => false,
                'created_at' => now()->subHours(10),
            ],

            // Dechet notifications
            [
                'user_id' => $user->id,
                'type' => 'dechet',
                'icon' => 'fa-recycle',
                'color' => 'orange',
                'title' => 'Déchet réservé',
                'message' => 'Sarah Trabelsi souhaite récupérer votre déchet "Palettes en bois".',
                'link' => '/dechets/1',
                'is_read' => true,
                'read_at' => now()->subHours(12),
                'created_at' => now()->subDay(),
            ],
            [
                'user_id' => $user->id,
                'type' => 'dechet',
                'icon' => 'fa-box',
                'color' => 'orange',
                'title' => 'Nouveau déchet disponible',
                'message' => '50kg de cartons est maintenant disponible près de chez vous.',
                'link' => '/dechets/5',
                'is_read' => false,
                'created_at' => now()->subDay(),
            ],

            // Tutorial notifications
            [
                'user_id' => $user->id,
                'type' => 'tutorial',
                'icon' => 'fa-book',
                'color' => 'blue',
                'title' => 'Tutoriel mis à jour',
                'message' => 'Le tutoriel "Composteur DIY" que vous suivez a été mis à jour avec de nouvelles étapes.',
                'link' => '/tutorials/1',
                'is_read' => false,
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $user->id,
                'type' => 'tutorial',
                'icon' => 'fa-graduation-cap',
                'color' => 'green',
                'title' => 'Tutoriel terminé !',
                'message' => 'Félicitations ! Vous avez terminé le tutoriel "Recyclage du Plastique".',
                'link' => '/tutorials/3',
                'is_read' => true,
                'read_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
            ],

            // User mention
            [
                'user_id' => $user->id,
                'type' => 'comment',
                'icon' => 'fa-at',
                'color' => 'blue',
                'title' => 'Vous avez été mentionné',
                'message' => 'Mohamed Jebali vous a mentionné dans un commentaire.',
                'link' => '/projects/2',
                'is_read' => false,
                'created_at' => now()->subDays(4),
            ],

            // System/Welcome notification
            [
                'user_id' => $user->id,
                'type' => 'system',
                'icon' => 'fa-star',
                'color' => 'yellow',
                'title' => 'Nouveau badge débloqué !',
                'message' => 'Vous avez débloqué le badge "Éco-Warrior" pour avoir créé 5 projets !',
                'link' => '/profile',
                'is_read' => true,
                'read_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id' => $user->id,
                'type' => 'system',
                'icon' => 'fa-gift',
                'color' => 'red',
                'title' => 'Nouveau défi disponible',
                'message' => 'Un nouveau défi mensuel est disponible : "Recycler 10 objets". Participez maintenant !',
                'link' => '/challenges',
                'is_read' => false,
                'created_at' => now()->subDays(7),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Sample FrontOffice notifications created successfully for user: ' . $user->name);
    }
}
