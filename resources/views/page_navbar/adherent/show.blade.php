<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="md:flex">
                        <!-- Image de l'annonce -->
                        <div class="md:w-1/2">
                            <img src="{{ asset('storage/' . $annonce->image) }}" alt="Image de l'annonce" class="rounded-t-lg md:rounded-l-lg md:rounded-tr-none object-cover w-full h-96">
                        </div>

                        <!-- Détails de l'annonce -->
                        <div class="md:w-1/2 p-6">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $annonce->title }}</h1>
                            <p class="text-lg text-gray-500 mt-2">{{ $annonce->location }}</p>
                            <p class="text-lg font-semibold text-green-600 mt-2">€{{ number_format($annonce->price, 2) }}</p>

                            <div class="mt-4 space-y-2">
                                <p class="text-gray-600">{{ $annonce->description }}</p>
                                <p><strong>Catégorie:</strong> {{ $annonce->category }}</p>
                                <p><strong>Compétences requises:</strong> {{ $annonce->skills_required }}</p>
                                <p><strong>Type d'échange:</strong> {{ $annonce->exchange_type === 'service_for_service' ? 'Service contre service' : 'Service contre crédits' }}</p>
                                <p><strong>Durée estimée:</strong> {{ $annonce->estimated_duration }}</p>
                                <p><strong>Disponibilité:</strong> {{ $annonce->availability }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                        <a href="{{ route('annonces.adherent.index') }}" class="text-blue-600 hover:text-blue-800">Retour aux annonces</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
