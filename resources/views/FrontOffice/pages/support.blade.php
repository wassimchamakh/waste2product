@extends($layout ?? 'FrontOffice.layout.app')

@section('title', 'Support - Waste2Product')

@section('content')
<main class="pt-20 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Centre de Support
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Nous sommes là pour vous aider. Trouvez des réponses à vos questions ou contactez notre équipe.
            </p>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Questions Fréquentes</h2>
            
            <div class="space-y-4">
                <details class="group border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                    <summary class="font-semibold text-gray-900 cursor-pointer flex items-center justify-between">
                        Comment créer un compte sur Waste2Product ?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Cliquez sur "S'inscrire" en haut de la page, remplissez le formulaire avec vos informations et validez votre email.
                    </p>
                </details>

                <details class="group border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                    <summary class="font-semibold text-gray-900 cursor-pointer flex items-center justify-between">
                        Comment déclarer un déchet ?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Connectez-vous, cliquez sur "Déclarer un déchet", remplissez les informations (titre, description, localisation, photos) et publiez votre annonce.
                    </p>
                </details>

                <details class="group border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                    <summary class="font-semibold text-gray-900 cursor-pointer flex items-center justify-between">
                        Est-ce que Waste2Product est gratuit ?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Oui, Waste2Product est 100% gratuit pour tous les utilisateurs. Aucun frais n'est demandé pour publier ou consulter des annonces.
                    </p>
                </details>

                <details class="group border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                    <summary class="font-semibold text-gray-900 cursor-pointer flex items-center justify-between">
                        Comment participer à un événement ?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Consultez la liste des événements, cliquez sur celui qui vous intéresse et inscrivez-vous. Vous recevrez une confirmation par email.
                    </p>
                </details>

                <details class="group border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                    <summary class="font-semibold text-gray-900 cursor-pointer flex items-center justify-between">
                        Comment signaler un contenu inapproprié ?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Utilisez le bouton "Signaler" présent sur chaque publication ou contactez-nous directement via la page contact.
                    </p>
                </details>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="bg-gradient-to-br from-primary to-green-700 rounded-2xl shadow-lg p-8 text-white text-center">
            <i class="fas fa-headset text-5xl mb-4"></i>
            <h3 class="text-2xl font-bold mb-3">Besoin d'aide supplémentaire ?</h3>
            <p class="mb-6 opacity-90">Notre équipe est disponible pour répondre à toutes vos questions</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary hover:bg-gray-100 px-8 py-3 rounded-xl font-bold transition-all transform hover:scale-105">
                <i class="fas fa-envelope"></i>
                Contactez-nous
            </a>
        </div>
    </div>
</main>
@endsection
