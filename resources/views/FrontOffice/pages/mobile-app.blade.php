@extends('FrontOffice.layout.app')

@section('title', 'Application Mobile - Waste2Product')

@section('content')
<main class="pt-20 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 bg-yellow-500 bg-opacity-10 text-yellow-600 px-6 py-2 rounded-full mb-4">
                <i class="fas fa-tools"></i>
                <span class="font-semibold">En cours de développement</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-4">
                Application Mobile
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Notre application mobile est actuellement en cours de développement et sera publiée prochainement. Restez connectés pour ne pas manquer son lancement !
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all">
                <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell text-primary text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Notifications Push</h3>
                <p class="text-gray-600 text-sm">Recevez des alertes en temps réel pour les nouveaux déchets près de chez vous</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all">
                <div class="w-16 h-16 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-camera text-secondary text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Scan & Identification</h3>
                <p class="text-gray-600 text-sm">Photographiez un déchet pour l'identifier et trouver des idées de recyclage</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all">
                <div class="w-16 h-16 bg-accent bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marked-alt text-accent text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Carte Interactive</h3>
                <p class="text-gray-600 text-sm">Visualisez les déchets disponibles autour de vous sur une carte</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all">
                <div class="w-16 h-16 bg-success bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-qrcode text-success text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">QR Code</h3>
                <p class="text-gray-600 text-sm">Scannez des QR codes pour accéder rapidement aux annonces</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all">
                <div class="w-16 h-16 bg-yellow-500 bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cloud-upload-alt text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Sync Cloud</h3>
                <p class="text-gray-600 text-sm">Synchronisation automatique avec la version web</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all">
                <div class="w-16 h-16 bg-purple-500 bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wifi-slash text-purple-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Mode Hors Ligne</h3>
                <p class="text-gray-600 text-sm">Accédez à vos favoris même sans connexion Internet</p>
            </div>
        </div>

        <!-- Download Section -->
        <div class="bg-gradient-to-br from-primary via-green-600 to-secondary rounded-2xl shadow-2xl p-12 text-white text-center">
            <i class="fas fa-tools text-6xl mb-6"></i>
            <h2 class="text-3xl font-bold mb-4">Application en développement</h2>
            <p class="text-xl mb-8 opacity-90">Nous travaillons activement sur notre application mobile qui sera disponible prochainement sur iOS et Android</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button disabled class="opacity-50 cursor-not-allowed inline-flex items-center gap-3 bg-black text-white px-8 py-4 rounded-xl font-bold text-lg">
                    <i class="fab fa-apple text-2xl"></i>
                    <div class="text-left">
                        <div class="text-xs">Télécharger sur</div>
                        <div class="text-sm">App Store</div>
                    </div>
                </button>

                <button disabled class="opacity-50 cursor-not-allowed inline-flex items-center gap-3 bg-black text-white px-8 py-4 rounded-xl font-bold text-lg">
                    <i class="fab fa-google-play text-2xl"></i>
                    <div class="text-left">
                        <div class="text-xs">Disponible sur</div>
                        <div class="text-sm">Google Play</div>
                    </div>
                </button>
            </div>

            <div class="mt-8 bg-white bg-opacity-10 rounded-lg p-6 backdrop-blur-sm">
                <h4 class="font-bold mb-3">Inscrivez-vous pour être notifié du lancement</h4>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <input type="email" placeholder="Votre email" class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white">
                    <button type="submit" class="bg-white text-primary hover:bg-gray-100 px-6 py-3 rounded-lg font-bold transition-all">
                        M'inscrire
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
