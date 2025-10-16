<?php

namespace Database\Seeders;

use App\Models\Tutorial;
use App\Models\TutoStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutoStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Steps data for each tutorial
        $tutorialSteps = [
            // Tutorial 1: Compost
            'Comment créer un compost domestique' => [
                [
                    'title' => 'Choisir l\'emplacement',
                    'description' => 'Sélectionnez un endroit approprié pour votre composteur',
                    'content' => 'Choisissez un endroit à mi-ombre, sur de la terre nue si possible. Le composteur doit être accessible facilement et permettre un bon drainage. Évitez les endroits trop ensoleillés qui assècheraient le compost.',
                    'estimated_time' => 10,
                    'tips' => 'Placez le composteur près de votre cuisine pour faciliter les allers-retours. Un accès facile encourage une utilisation régulière.',
                    'common_mistakes' => 'Évitez de placer le composteur directement sur du béton ou des dalles, car cela empêche les vers de terre d\'accéder au compost.',
                    'required_materials' => 'Composteur ou bac, pelle',
                ],
                [
                    'title' => 'Préparer le fond',
                    'description' => 'Créez une base aérée pour votre compost',
                    'content' => 'Déposez une couche de 10-15 cm de branchages ou de paille au fond du composteur. Cette couche favorise l\'aération et le drainage, essentiels pour un compost de qualité.',
                    'estimated_time' => 15,
                    'tips' => 'Utilisez des branches de taille de haie ou de petits branchages. Cela permet à l\'air de circuler depuis le bas.',
                    'common_mistakes' => 'Ne compactez pas cette première couche, elle doit rester aérée.',
                    'required_materials' => 'Branchages, paille, sécateur',
                ],
                [
                    'title' => 'Alterner les matières',
                    'description' => 'Équilibrez matières vertes et brunes',
                    'content' => 'Alternez les couches de matières vertes (épluchures, tontes) et brunes (feuilles mortes, carton). Un bon ratio est 50% de chaque. Les matières vertes apportent l\'azote, les brunes le carbone.',
                    'estimated_time' => 20,
                    'tips' => 'Gardez un sac de feuilles mortes ou de carton déchiqueté près du composteur pour équilibrer facilement.',
                    'common_mistakes' => 'Trop de matières vertes créent des odeurs, trop de brunes ralentissent la décomposition.',
                    'required_materials' => 'Déchets verts, déchets bruns, fourche',
                ],
                [
                    'title' => 'Maintenir l\'humidité',
                    'description' => 'Surveillez et ajustez l\'humidité',
                    'content' => 'Le compost doit être humide comme une éponge essorée. Arrosez légèrement si trop sec, ajoutez des matières brunes si trop humide. Vérifiez régulièrement.',
                    'estimated_time' => 5,
                    'tips' => 'En été, vérifiez l\'humidité chaque semaine. En hiver, le compost retient mieux l\'humidité.',
                    'common_mistakes' => 'Un compost trop sec ne se décompose pas, un compost trop humide sent mauvais.',
                    'required_materials' => 'Arrosoir, matières brunes de réserve',
                ],
                [
                    'title' => 'Brasser régulièrement',
                    'description' => 'Aérez votre compost pour accélérer la décomposition',
                    'content' => 'Brassez le compost toutes les 2-3 semaines avec une fourche ou un brass-compost. Cette opération apporte de l\'oxygène nécessaire aux micro-organismes et homogénéise le mélange.',
                    'estimated_time' => 10,
                    'tips' => 'Profitez du brassage pour vérifier la texture et l\'odeur du compost. Un bon compost sent la terre forestière.',
                    'common_mistakes' => 'Ne brassez pas trop souvent, cela refroidit le compost et ralentit la décomposition.',
                    'required_materials' => 'Fourche ou brass-compost',
                ],
            ],
            
            // Tutorial 2: Sacs réutilisables
            'Fabriquer des sacs réutilisables en tissu' => [
                [
                    'title' => 'Sélectionner le tissu',
                    'description' => 'Choisissez un tissu résistant et adapté',
                    'content' => 'Utilisez du coton épais, du lin, ou de la toile de jute. Vous pouvez recycler de vieux draps, nappes, ou rideaux. Lavez et repassez le tissu avant de commencer.',
                    'estimated_time' => 15,
                    'tips' => 'Le coton bio est idéal, mais n\'hésitez pas à recycler des tissus que vous avez déjà. Vérifiez la solidité du tissu en tirant dessus.',
                    'common_mistakes' => 'Évitez les tissus trop fins qui ne supporteront pas le poids des courses.',
                    'required_materials' => 'Tissu (50x80 cm par sac), ciseaux, règle',
                ],
                [
                    'title' => 'Découper les pièces',
                    'description' => 'Préparez les différentes parties du sac',
                    'content' => 'Découpez un rectangle de 40x70 cm pour le corps du sac et deux bandes de 8x50 cm pour les anses. Ajoutez 2 cm de marge de couture sur tous les côtés.',
                    'estimated_time' => 20,
                    'tips' => 'Utilisez une règle de couturière et une craie pour marquer les lignes avant de couper.',
                    'common_mistakes' => 'N\'oubliez pas les marges de couture, sinon votre sac sera trop petit.',
                    'required_materials' => 'Ciseaux de couture, règle, craie ou crayon',
                ],
                [
                    'title' => 'Coudre les anses',
                    'description' => 'Assemblez les poignées du sac',
                    'content' => 'Pliez chaque bande en deux dans la longueur, endroit contre endroit. Cousez à 1 cm du bord. Retournez et repassez. Surpiquez les deux côtés pour plus de solidité.',
                    'estimated_time' => 25,
                    'tips' => 'Renforcez les coutures des anses, c\'est elles qui supporteront le poids.',
                    'common_mistakes' => 'Ne négligez pas le repassage, des anses bien plates seront plus faciles à coudre.',
                    'required_materials' => 'Machine à coudre, épingles, fer à repasser',
                ],
                [
                    'title' => 'Assembler le sac',
                    'description' => 'Cousez le corps du sac',
                    'content' => 'Pliez le rectangle en deux, endroit contre endroit. Cousez les côtés à 1,5 cm du bord. Faites un ourlet en haut. Retournez le sac.',
                    'estimated_time' => 20,
                    'tips' => 'Faites des points arrière au début et à la fin de chaque couture pour la solidité.',
                    'common_mistakes' => 'Vérifiez que les coutures sont bien alignées avant de coudre.',
                    'required_materials' => 'Machine à coudre, épingles, fil solide',
                ],
                [
                    'title' => 'Fixer les anses',
                    'description' => 'Attachez solidement les poignées',
                    'content' => 'Positionnez les anses à 10 cm des bords latéraux. Cousez-les en faisant un carré renforcé par une croix. Testez la solidité en tirant dessus.',
                    'estimated_time' => 15,
                    'tips' => 'Cousez plusieurs fois sur les points d\'attache des anses pour une solidité maximale.',
                    'common_mistakes' => 'Des anses mal fixées risquent de se détacher avec le poids.',
                    'required_materials' => 'Machine à coudre, fil extra-fort',
                ],
            ],
            
            // Tutorial 3: Récupérateur d'eau
            'Installer un récupérateur d\'eau de pluie' => [
                [
                    'title' => 'Choisir l\'emplacement',
                    'description' => 'Identifier le meilleur endroit pour le récupérateur',
                    'content' => 'Placez le récupérateur sous une descente de gouttière, sur une surface plane et stable. Vérifiez que l\'endroit permet un accès facile avec un arrosoir.',
                    'estimated_time' => 15,
                    'tips' => 'Privilégiez un endroit proche du jardin ou des plantes à arroser.',
                    'common_mistakes' => 'Ne placez pas le récupérateur sur un sol instable qui pourrait s\'affaisser.',
                    'required_materials' => 'Niveau à bulle, pelle',
                ],
                [
                    'title' => 'Préparer la base',
                    'description' => 'Créez une base stable et de niveau',
                    'content' => 'Nivelez le sol et placez des dalles, des parpaings, ou construisez une base en bois. La base doit être solide car un récupérateur plein est très lourd (300-1000 kg).',
                    'estimated_time' => 45,
                    'tips' => 'Surélevez légèrement le récupérateur pour faciliter le remplissage des arrosoirs.',
                    'common_mistakes' => 'Une base instable peut faire basculer le récupérateur.',
                    'required_materials' => 'Dalles ou parpaings, niveau, sable',
                ],
                [
                    'title' => 'Modifier la gouttière',
                    'description' => 'Adaptez la descente d\'eau',
                    'content' => 'Coupez la descente de gouttière à la hauteur souhaitée. Installez un collecteur d\'eau de pluie avec filtre. Raccordez-le au récupérateur avec le tuyau fourni.',
                    'estimated_time' => 30,
                    'tips' => 'Choisissez un collecteur avec filtre intégré pour éviter les feuilles et débris.',
                    'common_mistakes' => 'Ne coupez pas la gouttière sans avoir mesuré précisément.',
                    'required_materials' => 'Scie, collecteur, tuyau, collier de serrage',
                ],
                [
                    'title' => 'Installer le récupérateur',
                    'description' => 'Positionnez et connectez la cuve',
                    'content' => 'Placez le récupérateur sur sa base. Connectez le tuyau du collecteur à l\'entrée du récupérateur. Vérifiez l\'étanchéité de tous les raccords.',
                    'estimated_time' => 20,
                    'tips' => 'Utilisez du téflon sur les filetages pour garantir l\'étanchéité.',
                    'common_mistakes' => 'Serrez les raccords sans forcer pour ne pas les abîmer.',
                    'required_materials' => 'Clé, téflon, joints',
                ],
                [
                    'title' => 'Tester et finaliser',
                    'description' => 'Vérifiez le bon fonctionnement',
                    'content' => 'Attendez une pluie ou versez de l\'eau dans la gouttière pour tester. Vérifiez qu\'il n\'y a pas de fuites. Installez un couvercle ou un grillage anti-moustiques si nécessaire.',
                    'estimated_time' => 10,
                    'tips' => 'Installez un robinet en bas du récupérateur pour un accès facile à l\'eau.',
                    'common_mistakes' => 'N\'oubliez pas de protéger contre les moustiques qui peuvent pondre dans l\'eau stagnante.',
                    'required_materials' => 'Grillage fin, robinet (optionnel)',
                ],
            ],
        ];

        // Get all tutorials
        $tutorials = Tutorial::whereIn('title', array_keys($tutorialSteps))->get();

        foreach ($tutorials as $tutorial) {
            if (isset($tutorialSteps[$tutorial->title])) {
                $steps = $tutorialSteps[$tutorial->title];
                
                foreach ($steps as $index => $stepData) {
                    TutoStep::create([
                        'tutorial_id' => $tutorial->id,
                        'step_number' => $index + 1,
                        'title' => $stepData['title'],
                        'description' => $stepData['description'],
                        'content' => $stepData['content'],
                        'image_url' => "https://picsum.photos/600/400?random=" . ($tutorial->id * 10 + $index),
                        'video_url' => rand(0, 1) ? "https://www.youtube.com/watch?v=dQw4w9WgXcQ" : null,
                        'estimated_time' => $stepData['estimated_time'],
                        'tips' => $stepData['tips'],
                        'common_mistakes' => $stepData['common_mistakes'],
                        'required_materials' => $stepData['required_materials'],
                    ]);
                }
                
                $this->command->info("✅ Created " . count($steps) . " steps for: {$tutorial->title}");
            }
        }

        // For other tutorials without predefined steps, create generic steps
        $tutorialsWithoutSteps = Tutorial::whereNotIn('title', array_keys($tutorialSteps))->get();
        
        foreach ($tutorialsWithoutSteps as $tutorial) {
            $numSteps = rand(3, 5);
            
            for ($i = 1; $i <= $numSteps; $i++) {
                TutoStep::create([
                    'tutorial_id' => $tutorial->id,
                    'step_number' => $i,
                    'title' => "Étape {$i} : " . $this->generateStepTitle($i),
                    'description' => "Description détaillée de l'étape {$i}",
                    'content' => "Contenu complet de l'étape {$i} avec toutes les instructions nécessaires pour réaliser cette partie du tutoriel.",
                    'image_url' => "https://picsum.photos/600/400?random=" . ($tutorial->id * 10 + $i),
                    'video_url' => rand(0, 2) == 0 ? "https://www.youtube.com/watch?v=dQw4w9WgXcQ" : null,
                    'estimated_time' => rand(10, 30),
                    'tips' => "Astuce importante pour réussir cette étape.",
                    'common_mistakes' => "Erreur fréquente à éviter lors de cette étape.",
                    'required_materials' => "Matériel nécessaire pour cette étape",
                ]);
            }
            
            $this->command->info("✅ Created {$numSteps} generic steps for: {$tutorial->title}");
        }

        $this->command->info("🎉 All tutorial steps created successfully!");
    }

    /**
     * Generate a generic step title
     */
    private function generateStepTitle($stepNumber): string
    {
        $titles = [
            1 => 'Préparation et rassemblement du matériel',
            2 => 'Mise en place et configuration initiale',
            3 => 'Assemblage et construction',
            4 => 'Finitions et ajustements',
            5 => 'Test et mise en service',
        ];

        return $titles[$stepNumber] ?? 'Étape intermédiaire';
    }
}