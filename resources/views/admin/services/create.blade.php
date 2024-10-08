@extends('layouts.templateAdmin')

@section('title', 'Administrateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
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


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">

                    @if(session('success'))
                        <div id="success-message" class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h1 class="text-2xl font-semibold text-gray-700">Ajouter un Nouveau Service</h1>
                </div>

                <form action="{{ route('services.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type de service</label>
                        <select name="type" id="type" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="" disabled selected>Choisir le type de service</option>
                            <option value="reservations" {{ old('type') == 'reservations' ? 'selected' : '' }}>Réservations</option>
                            <option value="postes" {{ old('type') == 'postes' ? 'selected' : '' }}>Postes</option>
                        </select>
                    </div>
                    

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du Service</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut du Service</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="disponible" {{ old('status') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="indisponible" {{ old('status') == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                        <select name="category" id="category" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="" disabled {{ old('category') == '' ? 'selected' : '' }}>Choisir une catégorie</option>
                            @foreach(['alimentation', 'technologie', 'immobilier', 'vetements', 'beaute', 'loisirs', 'services', 'automobile', 'education', 'sport', 'voyage', 'animaux', 'jardinage', 'mobilier', 'bricolage', 'financier', 'arts'] as $category)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>

                        <div>
                            <label for="condition" class="block text-sm font-medium text-gray-700">Conditions Bénévoles :</label>
                            <textarea name="condition" id="condition" rows="4"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('condition') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Compétences requises</label>
                            <div class="mt-1">
                                @foreach($skills as $skill)
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                                {{ in_array($skill->id, old('skills', isset($service) ? $service->skills->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                                                class="form-checkbox">
                                            <span class="ml-2">{{ $skill->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700">Durée Prévu (minutes) (Optionnel)</label>
                            <input type="number" name="duration" id="duration"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                value="{{ old('duration') }}">
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
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


