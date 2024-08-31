<x-app-layout>
    <div id="main-content" class="flex-1 p-10 transition-all bg-gray-100">
        <div class="container mx-auto py-24 max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Annonces disponibles</h2>

            <!-- Bouton pour ajouter une annonce -->
            <div class="mb-6 text-right">
                <a href="{{ route('annonces.adherent.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    + Ajouter une nouvelle annonce
                </a>
            </div>

            <!-- Formulaire de filtrage -->
            <form method="GET" action="{{ route('annonces.adherent.index') }}" class="mb-6">
                <div class="flex flex-wrap gap-4 items-center">
                    <input type="date" name="date" class="form-input border p-2 rounded-lg" placeholder="Date" value="{{ request('date') }}">
                    <input type="text" name="city" class="form-input border p-2 rounded-lg" placeholder="Ville" value="{{ request('city') }}">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        Filtrer
                    </button>
                </div>
            </form>

            <!-- Affichage des annonces -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($annonces as $annonce)
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                        <h3 class="text-lg font-semibold mb-2">Titre: {{ $annonce->title }}</h3>
                        <p class="text-gray-600 mb-1">Lieu: {{ $annonce->location }}</p>
                        <p class="text-gray-600 mb-1">Prix: {{ $annonce->price }} €</p>
                        <p class="text-gray-600 mb-4">Description: {{ $annonce->description }}</p>
                        <a href="{{ route('annonces.adherent.show', $annonce->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">Voir plus</a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $annonces->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
