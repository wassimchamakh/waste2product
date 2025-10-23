<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Dechet;
use App\Models\Category;
use App\Models\User;

class DechetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $users = User::all();

        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->error('❌ Veuillez d\'abord créer les catégories et les utilisateurs !');
            return;
        }

        $Dechets = [
            // Bois & Palettes
            [
                'title' => 'Palettes Européennes - Lot de 5',
                'description' => 'Lot de 5 palettes européennes (120x80cm) en excellent état. Récupérées après livraison de matériaux de construction. Parfaites pour créer des meubles DIY : tables basses, étagères, jardinières. Bois traité et résistant aux intempéries.',
                'quantity' => '5 unités',
                'location' => 'Menzah 6, Tunis',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 98 123 456',
                'notes' => 'À récupérer sur place. Aide au chargement possible.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Bois & Palettes')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Planches de Bois Massif',
                'description' => 'Planches de bois massif récupérées d\'une ancienne armoire démontée. Bois de qualité, idéal pour menuiserie ou création artistique. Longueur variable entre 1m et 2m.',
                'quantity' => '15 planches',
                'location' => 'Sfax Centre',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 22 456 789',
                'notes' => 'Certaines planches nécessitent un léger ponçage.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Bois & Palettes')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Caisses en Bois Vintage',
                'description' => 'Anciennes caisses de vin et de fruits en bois. Patine authentique, parfaites pour décoration vintage ou rangement créatif. Diverses tailles disponibles.',
                'quantity' => '12 caisses',
                'location' => 'Hammamet, Nabeul',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 97 741 852',
                'notes' => 'Style rustique, idéal pour décoration intérieure.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Bois & Palettes')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Plastique
            [
                'title' => 'Bouteilles Plastique 1.5L - Lot de 100',
                'description' => 'Collection de 100 bouteilles plastique 1.5L propres et en bon état. Idéales pour projets créatifs : jardinières verticales, décorations, isolations DIY. Déjà nettoyées et prêtes à l\'emploi.',
                'quantity' => '100 bouteilles',
                'location' => 'Cité Olympique, Sousse',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 25 789 123',
                'notes' => 'Récupération gratuite. Contribution écologique appréciée.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Plastique')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Bidons Plastique 5L',
                'description' => 'Bidons plastique alimentaires de 5 litres, lavés et sans odeur. Parfaits pour stockage d\'eau, créations artistiques ou système d\'irrigation maison.',
                'quantity' => '20 bidons',
                'location' => 'Monastir Centre',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 23 159 753',
                'notes' => 'Qualité alimentaire, avec bouchons.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Plastique')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Métal
            [
                'title' => 'Canettes Aluminium - Grand Lot',
                'description' => 'Lot important de canettes aluminium propres. Collecte de plusieurs mois. Parfait pour recyclage artistique, sculptures, ou création de luminaires design.',
                'quantity' => '300 canettes',
                'location' => 'Ariana Essoghra',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 54 321 987',
                'notes' => 'Canettes aplaties pour faciliter le transport.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Métal')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Ferraille et Pièces Métalliques',
                'description' => 'Assortiment de pièces métalliques diverses : tubes, plaques, vis, écrous. Issu de démontage de machines. Idéal pour bricoleurs et artistes soudeurs.',
                'quantity' => '50 kg',
                'location' => 'Zone Industrielle, Ben Arous',
                'status' => 'reserved',
                'photo' => null,
                'contact_phone' => '+216 29 654 321',
                'notes' => 'Déjà réservé - Liste d\'attente possible.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Métal')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Textile
            [
                'title' => 'Chutes de Tissus Divers',
                'description' => 'Grande quantité de chutes de tissus de qualité : coton, lin, jean. Couleurs variées. Parfait pour patchwork, créations textiles, jouets en tissu ou rembourrage.',
                'quantity' => '10 kg',
                'location' => 'Medina, Tunis',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 98 123 456',
                'notes' => 'Tissus propres, divers motifs et textures.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Textile')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Vêtements à Upcycler',
                'description' => 'Collection de vieux vêtements en bon état mais démodés. Jeans, chemises, robes. Idéal pour transformer en sacs, coussins, tapis ou nouveaux vêtements tendance.',
                'quantity' => '2 cartons pleins',
                'location' => 'La Marsa, Tunis',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 21 852 963',
                'notes' => 'Vêtements lavés et pliés.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Textile')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Électronique
            [
                'title' => 'Ordinateur Portable HP - Pour Pièces',
                'description' => 'Ordinateur portable HP en panne (écran cassé) mais composants internes fonctionnels. Récupération possible : disque dur, RAM, processeur, carte mère. Idéal pour bricoleurs électroniques.',
                'quantity' => '1 unité',
                'location' => 'Ennasr, Ariana',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 54 321 987',
                'notes' => 'Données effacées. Batterie morte.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Électronique')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Câbles et Composants Électroniques',
                'description' => 'Lot de câbles divers (HDMI, USB, alimentation), chargeurs, vieux téléphones. Parfait pour récupération de composants, projets Arduino ou recyclage de matériaux.',
                'quantity' => '1 carton',
                'location' => 'Sfax El Jadida',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 22 456 789',
                'notes' => 'Certains câbles fonctionnels.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Électronique')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Verre
            [
                'title' => 'Bouteilles en Verre Variées',
                'description' => 'Collection de bouteilles en verre de différentes formes et couleurs. Parfaites pour décoration, vases DIY, lampes artisanales ou projets de mosaïque.',
                'quantity' => '50 bouteilles',
                'location' => 'Centre-ville, Bizerte',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 21 852 963',
                'notes' => 'Bouteilles nettoyées, sans étiquettes.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Verre')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Bocaux en Verre avec Couvercles',
                'description' => 'Grands bocaux en verre avec couvercles hermétiques. Idéaux pour conservation, décoration vintage, terrariums ou rangement créatif.',
                'quantity' => '30 bocaux',
                'location' => 'Hammam-Lif, Ben Arous',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 29 654 321',
                'notes' => 'Tailles variées de 500ml à 2L.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Verre')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Papier & Carton
            [
                'title' => 'Cartons de Déménagement',
                'description' => 'Cartons solides de déménagement en excellent état. Diverses tailles. Parfaits pour rangement, nouveaux déménagements ou projets créatifs avec enfants.',
                'quantity' => '25 cartons',
                'location' => 'Lac 2, Tunis',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 98 123 456',
                'notes' => 'Cartons pliés pour faciliter le transport.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Papier & Carton')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Journaux et Magazines Anciens',
                'description' => 'Collection de vieux journaux et magazines. Idéal pour emballage, papier mâché, isolation DIY ou projets artistiques vintage.',
                'quantity' => '3 cartons',
                'location' => 'Kairouan Médina',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 25 789 123',
                'notes' => 'Papier propre et sec.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Papier & Carton')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Pneus
            [
                'title' => 'Pneus de Voiture Usagés',
                'description' => 'Pneus de voiture en bon état structurel mais usure trop avancée pour la route. Parfaits pour jardinières créatives, balançoires, aires de jeux ou barrières de protection.',
                'quantity' => '8 pneus',
                'location' => 'Garage Central, Sousse',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 25 789 123',
                'notes' => 'Taille standard 185/65 R15.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Pneus')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Mobilier
            [
                'title' => 'Chaises en Bois à Restaurer',
                'description' => 'Lot de 6 chaises anciennes en bois massif. Structure solide mais nécessitent restauration (ponçage, peinture). Style vintage parfait pour projet de relooking.',
                'quantity' => '6 chaises',
                'location' => 'Bardo, Tunis',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 98 123 456',
                'notes' => 'Assises à refaire, pieds solides.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Mobilier')->first()->id,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Ancienne Table en Bois Massif',
                'description' => 'Grande table en bois massif, style traditionnel tunisien. Quelques rayures et marques d\'usage mais structure excellente. Idéale pour atelier ou restauration vintage.',
                'quantity' => '1 table (180x90cm)',
                'location' => 'Médina, Sfax',
                'status' => 'reserved',
                'photo' => null,
                'contact_phone' => '+216 22 456 789',
                'notes' => 'Nécessite transport avec camionnette.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Mobilier')->first()->id,
                'user_id' => $users->random()->id,
            ],

            // Jardin
            [
                'title' => 'Pots de Fleurs en Terre Cuite',
                'description' => 'Collection de pots en terre cuite de différentes tailles. Certains avec petites fissures mais toujours utilisables. Parfaits pour jardin ou décoration extérieure.',
                'quantity' => '15 pots',
                'location' => 'Mourouj, Ben Arous',
                'status' => 'available',
                'photo' => null,
                'contact_phone' => '+216 29 654 321',
                'notes' => 'Diamètres variés de 15cm à 40cm.',
                'views_count' => rand(15, 150),
                'category_id' => $categories->where('name', 'Jardin')->first()->id,
                'user_id' => $users->random()->id,
            ],
        ];

        foreach ($Dechets as $Dechet) {
            Dechet::create($Dechet);
        }

        $this->command->info('✅ ' . count($Dechets) . ' déchets créés avec succès !');
    }
}
