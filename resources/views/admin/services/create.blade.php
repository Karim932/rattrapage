@extends('layouts.templateAdmin')

@section('title', 'Administrateur | NoMoreWaste')

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

                    @if(session('success'))
                        <div id="success-message" class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h1 class="text-2xl font-semibold text-gray-700">Ajouter un Nouveau Service</h1>
                    <a href="{{ route('services.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-gray-700 transition duration-300">
                        Retour à la Liste des Services
                    </a>
                </div>

                <!-- Formulaire de création de service -->
                <form action="{{ route('services.store') }}" method="POST" class="space-y-6">
                    @csrf

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
                            <option value="en_attente" {{ old('status') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                            <option value="complet" {{ old('status') == 'complet' ? 'selected' : '' }}>Complet</option>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                        <select name="category" id="category" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="" disabled selected>Choisir une catégorie</option>
                            <option value="nourriture">Nourriture</option>
                            <option value="electronique">Electronique</option>
                            <option value="appartement">Appartement</option>
                        </select>
                    </div>

                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700">Conditions Bénévoles :</label>
                        <textarea name="condition" id="condition" rows="4"
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('condition') }}</textarea>
                    </div>

                    {{-- <!-- Section pour ajouter des compétences dynamiquement -->
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700">Compétences Requises</label>
                        <div id="skills-wrapper" class="space-y-2">
                            <div class="flex items-center">
                                <input type="text" name="skills[]" id="skills" placeholder="Nom de la compétence"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                                <button type="button" id="add-skill" class="ml-2 bg-green-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-600 transition duration-300">Ajouter</button>
                            </div>
                        </div>
                    </div> --}}

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Durée (minutes) (Optionnel)</label>
                        <input type="number" name="duration" id="duration"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('duration') }}">
                    </div>

                    {{-- <div>
                        <label for="image_path" class="block text-sm font-medium text-gray-700">Image du Service</label>
                        <input type="file" name="image_path" id="image_path"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    </div> --}}

                    <div>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 transition duration-300">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-skill').addEventListener('click', function() {
        var skillsWrapper = document.getElementById('skills-wrapper');
        var newSkillInput = document.createElement('div');
        newSkillInput.classList.add('flex', 'items-center', 'mt-2');
        newSkillInput.innerHTML = `
            <input type="text" name="skills[]" placeholder="Nom de la compétence"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
            <button type="button" class="ml-2 remove-skill bg-red-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-600 transition duration-300">Supprimer</button>
        `;
        skillsWrapper.appendChild(newSkillInput);
    });

    document.getElementById('skills-wrapper').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-skill')) {
            e.target.parentElement.remove();
        }
    });
</script>
@endsection


