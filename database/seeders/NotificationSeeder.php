<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user
        $admin = User::where('is_admin', true)->first();

        if (!$admin) {
            $this->command->warn('No admin user found. Please create an admin user first.');
            return;
        }

        $notifications = [
            [
                'user_id' => $admin->id,
                'type' => 'user',
                'icon' => 'fa-user-plus',
                'color' => 'blue',
                'title' => 'Nouvel utilisateur inscrit',
                'message' => 'Ahmed Ben Ali vient de créer un compte',
                'link' => '/admin/users',
                'is_read' => false,
                'created_at' => now()->subMinutes(5),
            ],
            [
                'user_id' => $admin->id,
                'type' => 'project',
                'icon' => 'fa-project-diagram',
                'color' => 'green',
                'title' => 'Nouveau projet créé',
                'message' => 'Le projet "Table en Palettes" a été publié',
                'link' => '/admin/projects',
                'is_read' => false,
                'created_at' => now()->subMinutes(15),
            ],
            [
                'user_id' => $admin->id,
                'type' => 'event',
                'icon' => 'fa-calendar-alt',
                'color' => 'purple',
                'title' => 'Événement programmé',
                'message' => 'Atelier de Compostage - 25 Octobre 2025',
                'link' => '/admin/events',
                'is_read' => true,
                'read_at' => now()->subHours(1),
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $admin->id,
                'type' => 'dechet',
                'icon' => 'fa-recycle',
                'color' => 'orange',
                'title' => 'Nouveau déchet disponible',
                'message' => 'Cartons - 50kg disponibles à Tunis',
                'link' => '/admin/dechets',
                'is_read' => false,
                'created_at' => now()->subMinutes(30),
            ],
            [
                'user_id' => null, // Global notification
                'type' => 'system',
                'icon' => 'fa-cog',
                'color' => 'red',
                'title' => 'Maintenance programmée',
                'message' => 'Maintenance du système prévue le 20 Octobre à 2h du matin',
                'link' => null,
                'is_read' => false,
                'created_at' => now()->subHours(3),
            ],
            [
                'user_id' => $admin->id,
                'type' => 'tutorial',
                'icon' => 'fa-book',
                'color' => 'green',
                'title' => 'Tutoriel publié',
                'message' => 'Le tutoriel "Créer un composteur" a été publié',
                'link' => '/admin/tutorials',
                'is_read' => true,
                'read_at' => now()->subHours(5),
                'created_at' => now()->subHours(6),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Sample notifications created successfully!');
    }
}
