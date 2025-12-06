@extends('FrontOffice.layout.app')

@section('title', 'Contact - Waste2Product')

@section('content')
<main class="pt-20 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Contactez-nous
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Une question ? Une suggestion ? N'hésitez pas à nous contacter
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>
                
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom complet</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Votre nom">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="votre@email.com">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sujet</label>
                        <select name="subject" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Choisissez un sujet</option>
                            <option value="support">Support technique</option>
                            <option value="suggestion">Suggestion</option>
                            <option value="partnership">Partenariat</option>
                            <option value="report">Signalement</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none" placeholder="Votre message..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-green-700 hover:from-green-700 hover:to-primary text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer le message
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="space-y-6">
                <!-- Contact Person -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Contact</h3>
                            <p class="text-gray-900 font-semibold">Wassim Chamakh</p>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-secondary rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Email</h3>
                            <p class="text-gray-600">chamakhwassim1@gmail.com</p>
                        </div>
                    </div>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Téléphone</h3>
                            <p class="text-gray-600">+216 27 320 176</p>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-gradient-to-br from-primary to-green-700 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="font-bold mb-4">Suivez-nous</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg flex items-center justify-center transition-all">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg flex items-center justify-center transition-all">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg flex items-center justify-center transition-all">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg flex items-center justify-center transition-all">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
