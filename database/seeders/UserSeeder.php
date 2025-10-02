<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ahmed Ben Salem',
                'email' => 'ahmed@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 98 123 456',
                'address' => '15 Avenue Habib Bourguiba',
                'city' => 'Tunis',
                'is_admin' => true,
                'total_co2_saved' => 125.50,
                'projects_completed' => 8,
            ],
            [
                'name' => 'Sarah Mansouri',
                'email' => 'sarah@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 22 456 789',
                'address' => '42 Rue de la Liberté',
                'city' => 'Sfax',
                'is_admin' => false,
                'total_co2_saved' => 87.30,
                'projects_completed' => 5,
            ],
            [
                'name' => 'Youssef Trabelsi',
                'email' => 'youssef@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 25 789 123',
                'address' => 'Cité El Khadra',
                'city' => 'Sousse',
                'is_admin' => false,
                'total_co2_saved' => 156.75,
                'projects_completed' => 12,
            ],
            [
                'name' => 'Fatma Bouazizi',
                'email' => 'fatma@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 54 321 987',
                'address' => 'Résidence Ennasr',
                'city' => 'Ariana',
                'is_admin' => false,
                'total_co2_saved' => 45.20,
                'projects_completed' => 3,
            ],
            [
                'name' => 'Karim Hamdi',
                'email' => 'karim@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 29 654 321',
                'address' => 'Zone Industrielle',
                'city' => 'Ben Arous',
                'is_admin' => false,
                'total_co2_saved' => 203.40,
                'projects_completed' => 15,
            ],
            [
                'name' => 'Leila Gharbi',
                'email' => 'leila@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 21 852 963',
                'address' => 'Avenue de la République',
                'city' => 'Bizerte',
                'is_admin' => false,
                'total_co2_saved' => 92.15,
                'projects_completed' => 6,
            ],
            [
                'name' => 'Mohamed Khalil',
                'email' => 'mohamed@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 97 741 852',
                'address' => 'Quartier Mrezga',
                'city' => 'Nabeul',
                'is_admin' => false,
                'total_co2_saved' => 68.90,
                'projects_completed' => 4,
            ],
            [
                'name' => 'Amina Ben Ali',
                'email' => 'amina@waste2product.tn',
                'password' => Hash::make('password'),
                'phone' => '+216 23 159 753',
                'address' => 'Cité Olympique',
                'city' => 'Monastir',
                'is_admin' => false,
                'total_co2_saved' => 134.60,
                'projects_completed' => 9,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ ' . count($users) . ' utilisateurs créés avec succès !');
        $this->command->info('📧 Email: ahmed@waste2product.tn | Mot de passe: password (Admin)');
        $this->command->info('📧 Tous les utilisateurs ont le même mot de passe: password');
    }
}
