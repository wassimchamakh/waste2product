@extends('FrontOffice.layout.app')

@section('title', 'Conditions Générales d\'Utilisation - Waste2Product')

@section('content')
<main class="pt-20 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Conditions Générales d'Utilisation
            </h1>
            <p class="text-gray-600">Dernière mise à jour : {{ date('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 prose prose-lg max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptation des Conditions</h2>
                <p class="text-gray-700">
                    En accédant et en utilisant Waste2Product, vous acceptez d'être lié par ces Conditions Générales d'Utilisation (CGU). Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser la plateforme.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Description du Service</h2>
                <p class="text-gray-700">
                    Waste2Product est une plateforme en ligne permettant aux utilisateurs de :
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                    <li>Publier des annonces de déchets recyclables</li>
                    <li>Rechercher et récupérer des matériaux</li>
                    <li>Partager des projets de recyclage et d'upcycling</li>
                    <li>Participer à des événements et ateliers</li>
                    <li>Échanger avec la communauté</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Inscription et Compte</h2>
                <p class="text-gray-700 mb-3">Pour utiliser certaines fonctionnalités, vous devez :</p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Avoir au moins 18 ans ou l'autorisation d'un tuteur légal</li>
                    <li>Fournir des informations exactes et à jour</li>
                    <li>Maintenir la confidentialité de votre mot de passe</li>
                    <li>Être responsable de toutes les activités sous votre compte</li>
                    <li>Notifier immédiatement toute utilisation non autorisée</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Règles d'Utilisation</h2>
                <p class="text-gray-700 mb-3">Vous vous engagez à :</p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li><strong>Respecter les lois :</strong> Toutes les lois tunisiennes et internationales applicables</li>
                    <li><strong>Contenu approprié :</strong> Ne pas publier de contenu illégal, offensant ou trompeur</li>
                    <li><strong>Respect :</strong> Traiter les autres membres avec respect et courtoisie</li>
                    <li><strong>Sécurité :</strong> Prendre des précautions lors des rencontres en personne</li>
                    <li><strong>Propriété :</strong> Assurer que vous avez le droit de publier les contenus partagés</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Contenu Interdit</h2>
                <p class="text-gray-700 mb-3">Il est strictement interdit de publier :</p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Déchets dangereux (amiante, produits chimiques toxiques, etc.)</li>
                    <li>Matériaux illégaux ou volés</li>
                    <li>Contenu pornographique, violent ou discriminatoire</li>
                    <li>Spam ou contenu publicitaire non autorisé</li>
                    <li>Informations fausses ou trompeuses</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Propriété Intellectuelle</h2>
                <p class="text-gray-700">
                    Tous les contenus de la plateforme (logo, design, code) appartiennent à Waste2Product. Vous conservez la propriété de vos contenus publiés, mais vous nous accordez une licence non exclusive pour les afficher et les distribuer.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Transactions et Responsabilités</h2>
                <p class="text-gray-700">
                    Waste2Product facilite les échanges mais n'est pas partie aux transactions. Nous ne sommes pas responsables de :
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                    <li>La qualité, sécurité ou légalité des objets échangés</li>
                    <li>Les litiges entre utilisateurs</li>
                    <li>Les dommages résultant des transactions</li>
                    <li>La véracité des annonces publiées</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Modération et Sanctions</h2>
                <p class="text-gray-700">
                    Nous nous réservons le droit de :
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                    <li>Supprimer tout contenu inapproprié</li>
                    <li>Suspendre ou fermer des comptes en cas de violation</li>
                    <li>Signaler aux autorités toute activité illégale</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Limitation de Responsabilité</h2>
                <p class="text-gray-700">
                    La plateforme est fournie "en l'état". Nous ne garantissons pas l'absence d'erreurs ou d'interruptions. Notre responsabilité est limitée dans la mesure permise par la loi.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Modifications</h2>
                <p class="text-gray-700">
                    Nous pouvons modifier ces CGU à tout moment. Les modifications importantes seront notifiées. L'utilisation continue de la plateforme après les modifications constitue votre acceptation.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Résiliation</h2>
                <p class="text-gray-700">
                    Vous pouvez fermer votre compte à tout moment. Nous pouvons suspendre ou résilier votre accès en cas de violation des CGU.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Droit Applicable</h2>
                <p class="text-gray-700">
                    Ces CGU sont régies par le droit tunisien. Tout litige sera soumis aux tribunaux compétents de Tunis.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Contact</h2>
                <p class="text-gray-700">
                    Pour toute question concernant ces CGU, contactez-nous à : 
                    <a href="mailto:legal@waste2product.tn" class="text-primary hover:underline">legal@waste2product.tn</a>
                </p>
            </section>
        </div>
    </div>
</main>
@endsection
