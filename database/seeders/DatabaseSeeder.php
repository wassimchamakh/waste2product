<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
 public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            DechetSeeder::class,
            ProjectSeeder::class,
            ProjectStepSeeder::class,
            EventSeeder::class,     
            ParticipantSeeder::class, 
            TutorialSeeder::class,
            TutoStepSeeder::class,
            TutoCommentSeeder::class,
            ForumSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Base de données remplie avec succès !');
        $this->command->info('');
        $this->command->info('👤 Comptes de test créés :');
        $this->command->info('   Admin : ahmed@waste2product.tn | password');
        $this->command->info('   User  : sarah@waste2product.tn | password');
        $this->command->info('   User  : youssef@waste2product.tn | password');
        $this->command->info('   - Événements créés : ' . \App\Models\Event::count());
        $this->command->info('   - Participants inscrits : ' . \App\Models\Participant::count());
        $this->command->info('');
    }
}
