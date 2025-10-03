<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('❌ Veuillez d\'abord créer des utilisateurs !');
            return;
        }

        $events = [
            [
                'title' => 'Repair Café Tunis - Réparons Ensemble',
                'description' => "Venez réparer vos objets du quotidien au lieu de les jeter ! Notre Repair Café est un événement convivial où bénévoles et visiteurs se retrouvent pour réparer ensemble toutes sortes d'objets.\n\nCe que vous pouvez apporter :\n- Appareils électroniques\n- Vêtements à raccommoder\n- Petits meubles\n- Vélos\n- Jouets\n\nNos bénévoles experts vous aideront à diagnostiquer et réparer vos objets. C'est gratuit, convivial et écologique !\n\nPas besoin d'être bricoleur, venez juste avec vos objets à réparer et votre bonne humeur !",
                'type' => 'repair_cafe',
                'date_start' => Carbon::now()->addDays(7)->setTime(14, 0),
                'date_end' => Carbon::now()->addDays(7)->setTime(18, 0),
                'location' => 'Fab Lab ENSI, La Manouba, Tunis',
                'max_participants' => 25,
                'price' => 0,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Atelier Upcycling : Transformez vos Textiles',
                'description' => "Apprenez à transformer vos vieux vêtements en créations tendance !\n\nAu programme :\n- Techniques de base de couture\n- Transformation de jeans en sacs\n- Création de coussins avec des t-shirts\n- Patchwork créatif\n\nMatériel fourni :\n- Machines à coudre\n- Fils, aiguilles, ciseaux\n- Patrons et modèles\n\nÀ apporter :\n- Vieux vêtements en bon état\n- Votre créativité !\n\nNiveau : Débutant accepté\nEncadrement par des couturières professionnelles.",
                'type' => 'workshop',
                'date_start' => Carbon::now()->addDays(10)->setTime(10, 0),
                'date_end' => Carbon::now()->addDays(10)->setTime(13, 0),
                'location' => 'Maison des Jeunes, Sfax Centre',
                'max_participants' => 15,
                'price' => 10.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Grande Collecte Déchets Électroniques',
                'description' => "Participez à notre grande collecte de déchets électroniques et donnez une seconde vie aux composants !\n\nCe que nous collectons :\n- Ordinateurs et laptops\n- Téléphones et tablettes\n- Imprimantes et scanners\n- Câbles et chargeurs\n- Petits appareils électroménagers\n- Composants électroniques\n\nCe que nous faisons :\n- Tri et démontage\n- Récupération des composants fonctionnels\n- Recyclage écologique des déchets\n- Réparation et don aux écoles\n\nVenez en équipe, en famille ou seul. Café et collations offerts !\n\nChaque kilo collecté = 2kg CO2 économisés",
                'type' => 'collection',
                'date_start' => Carbon::now()->addDays(14)->setTime(9, 0),
                'date_end' => Carbon::now()->addDays(14)->setTime(17, 0),
                'location' => 'Place Pasteur, Tunis Centre (Parking disponible)',
                'max_participants' => 50,
                'price' => 0,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Formation : Compostage Urbain pour Débutants',
                'description' => "Découvrez comment transformer vos déchets organiques en or noir pour votre jardin !\n\nAu programme :\n- Principes du compostage\n- Que composter et que ne pas composter\n- Compostage en appartement (bokashi, lombricomposteur)\n- Compostage au jardin\n- Utilisation du compost\n- Solutions aux problèmes courants\n\nFormation théorique et pratique avec démonstrations en direct.\n\nCadeaux :\n- Guide pratique du compostage\n- Échantillon de compost mûr\n- Liste des points de compost collectif à Tunis\n\nPour tous : particuliers, écoles, restaurants.",
                'type' => 'training',
                'date_start' => Carbon::now()->addDays(16)->setTime(15, 0),
                'date_end' => Carbon::now()->addDays(16)->setTime(17, 0),
                'location' => 'Parc Belvédère, Tunis (Près de l\'entrée principale)',
                'max_participants' => 30,
                'price' => 5.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Atelier Création de Meubles en Palettes',
                'description' => "Transformez des palettes récupérées en meubles design pour votre maison !\n\nVous apprendrez à créer :\n- Table basse industrielle\n- Étagères murales\n- Jardinières\n- Banc d\'extérieur\n\nTechniques enseignées :\n- Démontage sécurisé des palettes\n- Ponçage et traitement du bois\n- Assemblage et vissage\n- Finitions (peinture, vernis)\n\nMatériel fourni :\n- Palettes\n- Outils électriques\n- Vis et quincaillerie\n- Produits de finition\n\nÀ apporter : Gants de travail et vêtements que vous pouvez salir.\n\nRéalisez votre propre meuble à ramener chez vous !",
                'type' => 'workshop',
                'date_start' => Carbon::now()->addDays(21)->setTime(9, 0),
                'date_end' => Carbon::now()->addDays(21)->setTime(17, 0),
                'location' => 'Atelier Menuiserie Solidaire, Ben Arous',
                'max_participants' => 12,
                'price' => 25.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Collecte et Plantation d\'Arbres - Journée Verte',
                'description' => "Journée spéciale environnement : le matin collecte de déchets, l'après-midi plantation d'arbres !\n\nProgramme de la journée :\n\n🌅 MATIN (8h-12h) : Collecte de déchets\n- Nettoyage de la forêt de Bou Kornine\n- Tri sur place des déchets\n- Pesée et comptabilisation\n\n🍽️ 12h-13h : Déjeuner bio offert\n\n🌳 APRÈS-MIDI (13h-17h) : Plantation\n- Plantation de 200 arbres fruitiers\n- Techniques de plantation et entretien\n- Installation de systèmes d\'irrigation goutte-à-goutte\n\nFourni :\n- Gants, sacs poubelle, outils\n- Eau et collations\n- Transport depuis Tunis (optionnel)\n- Certificat de participation\n\nVenez en famille ! Activités pour enfants prévues.",
                'type' => 'collection',
                'date_start' => Carbon::now()->addDays(28)->setTime(8, 0),
                'date_end' => Carbon::now()->addDays(28)->setTime(17, 0),
                'location' => 'Forêt de Bou Kornine, Ben Arous (RDV parking Hammamet Yasmine)',
                'max_participants' => 80,
                'price' => 0,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Repair Café Sousse - Électroménager',
                'description' => "Session spéciale réparation d'appareils électroménagers.\n\nNos experts peuvent réparer :\n- Cafetières, bouilloires\n- Mixeurs, robots\n- Fers à repasser\n- Aspirateurs\n- Grille-pain, micro-ondes\n\nService gratuit !\n\nPièces de rechange disponibles à prix coûtant.\n\nConseils d'entretien pour prolonger la vie de vos appareils.\n\nAmbiance conviviale avec café et gâteaux maison.",
                'type' => 'repair_cafe',
                'date_start' => Carbon::now()->addDays(12)->setTime(14, 0),
                'date_end' => Carbon::now()->addDays(12)->setTime(18, 0),
                'location' => 'Centre Culturel, Sousse Ville',
                'max_participants' => 20,
                'price' => 0,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Formation DIY : Produits Ménagers Écologiques',
                'description' => "Apprenez à fabriquer vos propres produits ménagers zéro déchet !\n\nRecettes que vous apprendrez :\n- Lessive liquide et en poudre\n- Liquide vaisselle\n- Nettoyant multi-usages\n- Détartrant naturel\n- Désodorisant maison\n\nIngrédients de base fournis :\n- Savon de Marseille\n- Bicarbonate de soude\n- Vinaigre blanc\n- Huiles essentielles\n\nChacun repart avec :\n- Ses produits fabriqués\n- Fiches recettes illustrées\n- Conseils d'utilisation et conservation\n\nÉconomies : -70% sur votre budget ménager !\nÉcologie : -95% de plastique !",
                'type' => 'training',
                'date_start' => Carbon::now()->addDays(18)->setTime(10, 0),
                'date_end' => Carbon::now()->addDays(18)->setTime(12, 30),
                'location' => 'Espace Associatif, La Marsa',
                'max_participants' => 25,
                'price' => 15.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Atelier Poterie avec Argile Récupérée',
                'description' => "Créez vos propres pots et objets déco avec de l'argile récupérée de chantiers !\n\nTechniques abordées :\n- Préparation de l'argile\n- Modelage à la main (colombin, plaque)\n- Tournage (initiation)\n- Décoration et émaillage\n\nCréations possibles :\n- Bols et assiettes\n- Vases et cache-pots\n- Objets décoratifs\n\nMatériel fourni :\n- Argile et outils\n- Tabliers\n- Four de cuisson\n\nVos créations seront cuites et disponibles une semaine après l'atelier.\n\nNiveau : Tous niveaux\nPetit groupe pour un suivi personnalisé.",
                'type' => 'workshop',
                'date_start' => Carbon::now()->addDays(25)->setTime(14, 0),
                'date_end' => Carbon::now()->addDays(25)->setTime(17, 0),
                'location' => 'Atelier d\'Art, Sidi Bou Said',
                'max_participants' => 10,
                'price' => 20.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Hackathon Économie Circulaire',
                'description' => "48h pour imaginer et prototyper des solutions innovantes pour l'économie circulaire en Tunisie !\n\nThématiques :\n- Applications mobiles de gestion des déchets\n- Marketplace de produits upcyclés\n- Systèmes de traçabilité blockchain\n- IoT pour tri intelligent\n- Gamification du recyclage\n\nÉquipes de 3-5 personnes (développeurs, designers, business)\n\nPrix :\n🥇 1er : 3000 DT + Incubation\n🥈 2ème : 2000 DT\n🥉 3ème : 1000 DT\n\nFourni :\n- Espace de travail 24h/24\n- Repas et snacks\n- Mentors experts\n- Accès APIs partenaires\n\nInscription par équipe.\nPitch final devant jury professionnel.",
                'type' => 'training',
                'date_start' => Carbon::now()->addDays(35)->setTime(9, 0),
                'date_end' => Carbon::now()->addDays(37)->setTime(18, 0),
                'location' => 'Cogite Coworking Space, Lac 2, Tunis',
                'max_participants' => 60,
                'price' => 30.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Collecte Textile - Donnez Vos Vêtements',
                'description' => "Grande collecte de textiles pour donner une seconde vie à vos vêtements !\n\nNous acceptons :\n✅ Vêtements en bon état\n✅ Chaussures par paires\n✅ Sacs et accessoires\n✅ Linge de maison\n✅ Tissus et chutes\n\nCe que nous en faisons :\n- Don aux associations caritatives\n- Vente en friperie solidaire (prix libres)\n- Transformation en nouveaux vêtements (upcycling)\n- Recyclage textile pour isolation\n\nLe tri est fait sur place avec vous pour vous expliquer notre démarche.\n\nObjectif : 2 tonnes de textile collectées !\n\nContribution écologique : 1kg textile = 3kg CO2 économisés",
                'type' => 'collection',
                'date_start' => Carbon::now()->addDays(20)->setTime(10, 0),
                'date_end' => Carbon::now()->addDays(20)->setTime(16, 0),
                'location' => 'Parking Carrefour, Menzah 6, Tunis',
                'max_participants' => 40,
                'price' => 0,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Repair Café Spécial Vélos',
                'description' => "Session dédiée à la réparation et l'entretien de vélos !\n\nNos mécaniciens bénévoles vous aident avec :\n- Réglage freins et vitesses\n- Changement chambres à air\n- Réparation crevaisons\n- Graissage et nettoyage\n- Réglage direction et selle\n- Remplacement câbles\n\nPièces détachées disponibles :\n- Chambres à air\n- Câbles de freins/vitesses\n- Patins de frein\n- Sonnettes et lumières\n(à prix coûtant)\n\nVenez aussi pour :\n- Apprendre l'entretien de base\n- Conseils sécurité routière\n- Marquage antivol Bicycode\n\nAmbiance conviviale, café offert !\n\nApportez votre vélo et repartez en toute sécurité.",
                'type' => 'repair_cafe',
                'date_start' => Carbon::now()->addDays(15)->setTime(9, 0),
                'date_end' => Carbon::now()->addDays(15)->setTime(13, 0),
                'location' => 'Association Vélorution, Bab El Khadra, Tunis',
                'max_participants' => 30,
                'price' => 0,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Formation Permaculture et Jardinage Urbain',
                'description' => "Découvrez la permaculture et créez votre jardin urbain productif !\n\nAu programme :\n\nThéorie (2h) :\n- Principes de la permaculture\n- Design de jardin urbain\n- Associations de plantes\n- Compostage et mulching\n- Gestion de l'eau\n\nPratique (3h) :\n- Création d'un carré potager\n- Plantation de légumes de saison\n- Installation goutte-à-goutte\n- Semis et repiquage\n- Fabrication de semis en recyclage\n\nChacun repart avec :\n- Kit de démarrage (graines, plants)\n- Guide pratique illustré\n- Plan de jardin personnalisé\n\nPour tous : balcon, terrasse ou jardin !\n\nFormateur : Ingénieur agronome spécialisé en permaculture urbaine.",
                'type' => 'training',
                'date_start' => Carbon::now()->addDays(30)->setTime(9, 0),
                'date_end' => Carbon::now()->addDays(30)->setTime(14, 0),
                'location' => 'Jardin Partagé Bhar Lazreg, La Goulette',
                'max_participants' => 20,
                'price' => 25.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Atelier Zéro Déchet - Cosmétiques Maison',
                'description' => "Apprenez à fabriquer vos cosmétiques naturels sans plastique !\n\nRecettes que vous réaliserez :\n- Déodorant solide\n- Dentifrice naturel\n- Shampoing solide\n- Baume à lèvres\n- Crème hydratante\n- Savon surgras\n\nIngrédients naturels fournis :\n- Huiles végétales bio\n- Beurres végétaux\n- Huiles essentielles\n- Argiles\n- Cires naturelles\n\nChacun repart avec :\n- Ses 6 cosmétiques fabriqués\n- Recettes détaillées\n- Pochettes en tissu recyclé\n- Guide des ingrédients naturels\n\nBénéfices :\n💰 -80% sur budget cosmétiques\n🌍 Zéro déchet plastique\n🌿 100% naturel et sain\n\nNiveau : Débutant accepté",
                'type' => 'workshop',
                'date_start' => Carbon::now()->addDays(22)->setTime(14, 0),
                'date_end' => Carbon::now()->addDays(22)->setTime(17, 30),
                'location' => 'Espace Bien-être Bio, Ennasr, Ariana',
                'max_participants' => 18,
                'price' => 35.00,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
            [
                'title' => 'Collecte Bouteilles Plastique - Projet Art Public',
                'description' => "Collecte participative pour créer une sculpture géante en bouteilles plastique !\n\nLe projet :\nCréation d'une œuvre d'art monumentale (5m de haut) avec 10 000 bouteilles plastique pour sensibiliser au recyclage.\n\nNous collectons :\n- Bouteilles plastique 0.5L à 2L\n- Bouchons de toutes couleurs\n- Propres et vides\n\nDéroulement :\n- 9h-12h : Collecte et tri par couleur\n- 12h-13h : Pause déjeuner (sandwichs offerts)\n- 13h-17h : Assemblage avec artistes\n\nSculpture finale :\nExposée sur l'Avenue Habib Bourguiba pendant 1 mois !\n\nTous participants seront mentionnés sur une plaque.\n\nActivités enfants prévues : peinture sur bouteilles.\n\nVenez nombreux pour ce projet artistique et écologique !",
                'type' => 'collection',
                'date_start' => Carbon::now()->addDays(40)->setTime(9, 0),
                'date_end' => Carbon::now()->addDays(40)->setTime(17, 0),
                'location' => 'Place de la République, Tunis (Esplanade)',
                'max_participants' => 100,
                'price' => 0,
                'status' => 'published',
                'image' => null,
                'user_id' => $users->random()->id,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }

        $this->command->info('✅ ' . count($events) . ' événements créés avec succès !');
    }
};
