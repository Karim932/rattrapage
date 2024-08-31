<x-app-layout>
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        Créer une nouvelle annonce
    </h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('annonces.adherent.store') }}" enctype="multipart/form-data" class="bg-white p-8 shadow-lg rounded-lg">
        @csrf

        <div class="mb-6">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Titre de l'annonce :</label>
            <input type="text" name="title" id="title" class="form-input mt-1 block w-full"
                   placeholder="Entrez le titre de l'annonce" required value="{{ old('title') }}">
        </div>

        <div class="mb-6">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description :</label>
            <textarea name="description" id="description" required
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                      placeholder="Décrivez ce que vous proposez">{{ old('description') }}</textarea>
        </div>

        <div class="mb-6">
            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Lieu :</label>
            <input type="text" name="location" id="location" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                   placeholder="Entrez le lieu où le service est offert" value="{{ old('location') }}">
        </div>

        <div class="mb-6">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Prix (€) :</label>
            <input type="number" name="price" id="price" min="0" step="0.01"
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                   placeholder="0.00" value="{{ old('price') }}">
        </div>

        <div class="mb-6">
            <label class="form-label block text-gray-700 text-sm font-bold mb-2">Catégorie :</label>
            <select name="category" id="category" required class="form-select mt-1 block w-full">
                <option value="">Sélectionnez une catégorie</option>
                <option value="bricolage">Bricolage</option>
                <option value="electricité">Électricité</option>
                <option value="plomberie">Plomberie</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="form-label block text-gray-700 text-sm font-bold mb-2">Compétences requises :</label>
            <input type="text" name="skills_required" id="skills_required" class="form-input mt-1 block w-full"
                   placeholder="Entrez les compétences nécessaires" value="{{ old('skills_required') }}">
        </div>
        
        <div class="mb-6">
            <label class="form-label block text-gray-700 text-sm font-bold mb-2">Type d'échange :</label>
            <select name="exchange_type" id="exchange_type" required class="form-select mt-1 block w-full">
                <option value="">Sélectionnez un type d'échange</option>
                <option value="service_for_service" {{ old('exchange_type') == 'service_for_service' ? 'selected' : '' }}>Service contre service</option>
                <option value="service_for_credits" {{ old('exchange_type') == 'service_for_credits' ? 'selected' : '' }}>Service contre crédits</option>
            </select>
        </div>
        
        <div class="mb-6">
            <label class="form-label block text-gray-700 text-sm font-bold mb-2">Durée estimée :</label>
            <input type="text" name="estimated_duration" id="estimated_duration" class="form-input mt-1 block w-full"
                   placeholder="Entrez la durée estimée du service" value="{{ old('estimated_duration') }}">
        </div>
        
        <div class="mb-6">
            <label class="form-label block text-gray-700 text-sm font-bold mb-2">Disponibilité :</label>
            <input type="text" name="availability" id="availability" class="form-input mt-1 block w-full"
                   placeholder="Indiquez la disponibilité pour le service" value="{{ old('availability') }}">
        </div>

        <div class="mb-6">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image (facultative) :</label>
            <input type="file" name="image" id="image" class="form-input mt-1 block w-full">
        </div>

        <div class="flex justify-end mt-8">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                Publier l'annonce
            </button>
        </div>
    </form>
</div>
</x-app-layout>
