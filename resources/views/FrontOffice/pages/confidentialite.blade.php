@extends('FrontOffice.layout.app')

@section('title', 'Politique de Confidentialité - Waste2Product')

@section('content')
<main class="pt-20 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Politique de Confidentialité
            </h1>
            <p class="text-gray-600">Dernière mise à jour : {{ date('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 prose prose-lg max-w-none">
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                <p class="text-gray-700">
                    Waste2Product s'engage à protéger la confidentialité de vos données personnelles. Cette politique décrit comment nous collectons, utilisons et protégeons vos informations.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Données Collectées</h2>
                <p class="text-gray-700 mb-3">Nous collectons les informations suivantes :</p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li><strong>Informations de compte :</strong> nom, email, numéro de téléphone</li>
                    <li><strong>Contenu publié :</strong> annonces de déchets, projets, commentaires, photos</li>
                    <li><strong>Données de localisation :</strong> ville, région (si vous les partagez)</li>
                    <li><strong>Données techniques :</strong> adresse IP, type de navigateur, données de navigation</li>
                    <li><strong>Cookies :</strong> pour améliorer votre expérience utilisateur</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Utilisation des Données</h2>
                <p class="text-gray-700 mb-3">Vos données sont utilisées pour :</p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Créer et gérer votre compte</li>
                    <li>Publier et afficher vos annonces</li>
                    <li>Faciliter la communication entre membres</li>
                    <li>Améliorer nos services</li>
                    <li>Envoyer des notifications importantes</li>
                    <li>Prévenir les fraudes et abus</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Partage des Données</h2>
                <p class="text-gray-700">
                    Nous ne vendons jamais vos données. Vos informations peuvent être partagées uniquement dans les cas suivants :
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                    <li>Avec d'autres utilisateurs (nom, ville) dans le cadre de vos annonces publiques</li>
                    <li>Avec des prestataires de services (hébergement, analytics) sous contrat strict</li>
                    <li>Si requis par la loi ou autorités compétentes</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Sécurité</h2>
                <p class="text-gray-700">
                    Nous utilisons des mesures de sécurité techniques et organisationnelles pour protéger vos données : chiffrement SSL, serveurs sécurisés, accès restreint aux données.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Vos Droits</h2>
                <p class="text-gray-700 mb-3">Vous disposez des droits suivants :</p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li><strong>Accès :</strong> consulter vos données personnelles</li>
                    <li><strong>Rectification :</strong> corriger vos informations</li>
                    <li><strong>Suppression :</strong> demander la suppression de votre compte</li>
                    <li><strong>Portabilité :</strong> récupérer vos données</li>
                    <li><strong>Opposition :</strong> refuser certains traitements</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Cookies</h2>
                <p class="text-gray-700">
                    Notre site utilise des cookies pour améliorer votre expérience. Vous pouvez les désactiver dans les paramètres de votre navigateur, mais certaines fonctionnalités pourraient être limitées.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Modifications</h2>
                <p class="text-gray-700">
                    Nous pouvons modifier cette politique de confidentialité. Les changements importants vous seront notifiés par email ou via la plateforme.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Contact</h2>
                <p class="text-gray-700">
                    Pour toute question concernant vos données personnelles, contactez-nous à : 
                    <a href="mailto:privacy@waste2product.tn" class="text-primary hover:underline">privacy@waste2product.tn</a>
                </p>
            </section>
        </div>
    </div>
</main>
@endsection
