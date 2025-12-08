@extends($layout ?? 'FrontOffice.layout.app')

@section('title', 'Aide - Waste2Product')

@section('content')
<main class="pt-20 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Centre d'Aide
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Guides et tutoriels pour utiliser Waste2Product efficacement
            </p>
        </div>

        <!-- Guide Categories -->
        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all">
                <h3 class="text-xl font-bold text-gray-900 mb-3">Premiers Pas</h3>
                <p class="text-gray-600 mb-4">Créez votre compte et commencez à utiliser la plateforme</p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li><i class="fas fa-check text-success mr-2"></i>Inscription</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Configuration du profil</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Navigation sur la plateforme</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all">
                <h3 class="text-xl font-bold text-gray-900 mb-3">Gestion des Déchets</h3>
                <p class="text-gray-600 mb-4">Publiez et gérez vos annonces de déchets</p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li><i class="fas fa-check text-success mr-2"></i>Créer une annonce</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Ajouter des photos</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Modifier/Supprimer</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all">
                <h3 class="text-xl font-bold text-gray-900 mb-3">Projets & Tutoriels</h3>
                <p class="text-gray-600 mb-4">Partagez vos créations et apprenez de la communauté</p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li><i class="fas fa-check text-success mr-2"></i>Créer un projet</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Suivre un tutoriel</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Partager vos réalisations</li>
                </ul>
            </div>
        </div>

        <!-- Detailed Guides -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Guides Détaillés</h2>
            
            <div class="space-y-6">
                <div class="border-l-4 border-primary pl-6 py-2">
                    <h4 class="font-bold text-gray-900 mb-2">📝 Comment créer une annonce de qualité ?</h4>
                    <p class="text-gray-600 text-sm">
                        Une bonne annonce comprend un titre clair, une description détaillée, des photos de qualité et une localisation précise.
                    </p>
                </div>

                <div class="border-l-4 border-secondary pl-6 py-2">
                    <h4 class="font-bold text-gray-900 mb-2">🔍 Comment rechercher des matériaux ?</h4>
                    <p class="text-gray-600 text-sm">
                        Utilisez les filtres de recherche par catégorie, localisation et date pour trouver exactement ce dont vous avez besoin.
                    </p>
                </div>

                <div class="border-l-4 border-accent pl-6 py-2">
                    <h4 class="font-bold text-gray-900 mb-2">💬 Comment communiquer avec les autres membres ?</h4>
                    <p class="text-gray-600 text-sm">
                        Utilisez la messagerie interne ou les informations de contact fournies dans les annonces pour échanger avec la communauté.
                    </p>
                </div>

                <div class="border-l-4 border-success pl-6 py-2">
                    <h4 class="font-bold text-gray-900 mb-2">🎯 Comment participer aux événements ?</h4>
                    <p class="text-gray-600 text-sm">
                        Consultez le calendrier des événements, inscrivez-vous en ligne et recevez toutes les informations nécessaires par email.
                    </p>
                </div>
            </div>
        </div>

        <!-- Video Tutorials -->
        <div class="mt-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg p-8 text-white text-center">
            <i class="fas fa-video text-5xl mb-4"></i>
            <h3 class="text-2xl font-bold mb-3">Tutoriels Vidéo</h3>
            <p class="mb-6 opacity-90">Découvrez nos guides vidéo pour maîtriser toutes les fonctionnalités</p>
            <button class="inline-flex items-center gap-2 bg-white text-purple-600 hover:bg-gray-100 px-8 py-3 rounded-xl font-bold transition-all transform hover:scale-105">
                <i class="fas fa-play"></i>
                Voir les vidéos
            </button>
        </div>
    </div>
</main>
@endsection
