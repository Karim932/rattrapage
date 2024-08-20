{{-- resources/views/services/edit.blade.php --}}
@extends('layouts.templateAdmin')

@section('title', 'Administrateur | Modifier Service')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4">
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
                    <h1 class="text-2xl font-semibold text-gray-700">Modifier le Service</h1>
                </div>

                <!-- Formulaire de modification de service -->
                <form action="{{ route('services.update', $service->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du Service</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('name', $service->name) }}">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut du Service</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="disponible" {{ old('status', $service->status) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="indisponible" {{ old('status', $service->status) == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
                            <option value="en_attente" {{ old('status', $service->status) == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                            <option value="complet" {{ old('status', $service->status) == 'complet' ? 'selected' : '' }}>Complet</option>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('description', $service->description) }}</textarea>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                        <select name="category" id="category" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="" disabled>Choisir une catégorie</option>
                            <option value="nourriture" {{ old('category', $service->category) == 'nourriture' ? 'selected' : '' }}>Nourriture</option>
                            <option value="electronique" {{ old('category', $service->category) == 'electronique' ? 'selected' : '' }}>Electronique</option>
                            <option value="appartement" {{ old('category', $service->category) == 'appartement' ? 'selected' : '' }}>Appartement</option>
                        </select>
                    </div>

                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700">Conditions Bénévoles :</label>
                        <textarea name="condition" id="condition" rows="4"
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('condition', $service->condition) }}</textarea>
                    </div>

                    <!-- Interface pour ajouter et supprimer des nouvelles compétences dynamiquement -->
                    <div>
                        <label for="new_skills" class="block text-sm font-medium text-gray-700">Ajouter de nouvelles compétences</label>
                        <div id="new_skills_container">
                            @foreach($skills as $skill)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                           {{ in_array($skill->id, old('skills', $service->skills->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                    <span class="ml-2 text-sm text-gray-700">{{ $skill->name }}</span>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add_new_skill_button" class="mt-2 py-2 px-4 border border-gray-300 rounded-md shadow-sm">Ajouter une compétence</button>
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Durée (minutes) (Optionnel)</label>
                        <input type="number" name="duration" id="duration"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('duration', $service->duration) }}">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addButton = document.getElementById('add_new_skill_button');
        const container = document.getElementById('new_skills_container');
        let index = 0;

        addButton.addEventListener('click', function() {
            const newDiv = document.createElement('div');
            newDiv.classList.add('flex', 'items-center', 'mb-2');

            const newInput = document.createElement('input');
            newInput.type = 'checkbox';
            newInput.name = `new_skills[${index}]`;
            newInput.classList.add('form-checkbox', 'h-4', 'w-4', 'text-indigo-600', 'transition', 'duration-150', 'ease-in-out');

            const newLabel = document.createElement('span');
            newLabel.classList.add('ml-2', 'text-sm', 'text-gray-700');
            newLabel.textContent = Nouvelle Compétence ${index + 1};
            newDiv.appendChild(newInput);
            newDiv.appendChild(newLabel);
            container.appendChild(newDiv);
            index++;
        });
    });
</script>
@endsection
