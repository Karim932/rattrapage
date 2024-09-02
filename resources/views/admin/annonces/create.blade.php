@extends('layouts.templateAdmin')

@section('title', 'Ajouter une annonce')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">
            Ajouter une nouvelle annonce
        </h1>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('annonces.store') }}" class="bg-white p-8 shadow-lg rounded-lg space-y-6">
            @csrf

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Utilisateur :</label>
                <select name="user_id" id="user_id" class="form-select mt-1 block w-full">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ (old('user_id', $annonce->user_id ?? '') == $user->id) ? 'selected' : '' }}>
                            {{ $user->firstname }}
                            {{ $user->lastname }} 
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Service :</label>
                <select name="service_id" id="service_id" class="form-select mt-1 block w-full">
                    @foreach ($services as $service)
                        @if($service->type === 'postes')
                            <option value="{{ $service->id }}" {{ (old('service_id', $annonce->service_id ?? '') == $service->id) ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Titre de l'annonce :</label>
                <input type="text" name="title" id="title" class="form-input mt-1 block w-full"
                       placeholder="Entrez le titre de l'annonce" required value="{{ old('title') }}">
            </div>

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Description :</label>
                <textarea name="description" id="description" required
                          class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                          placeholder="Décrivez ce que vous proposez">{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Image (facultative) :</label>
                <input type="file" name="image" id="image" class="form-input mt-1 block w-full">
            </div>

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Lieu :</label>
                <input type="text" name="location" id="location" required
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                       placeholder="Entrez le lieu où le service est offert" value="{{ old('location') }}">
            </div>

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Prix (€) :</label>
                <input type="number" name="price" id="price" required min="0" step="0.01"
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

            <div class="flex justify-between mt-6">
                <button type="button" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out" onclick="window.history.back();">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </button>

                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Soumettre l'annonce
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
