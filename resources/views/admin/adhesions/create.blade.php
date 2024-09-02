@extends('layouts.templateAdmin')

@section('title', 'Ajouter une candidature')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">
            Créer une candidature
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

        <form method="POST" action="{{ route('adhesion.store') }}" class="bg-white p-8 shadow-lg rounded-lg space-y-6">
            @csrf

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Sélectionnez un Utilisateur :</label>
                <select name="user_id" id="user_id" class="form-select mt-1 block w-full">
                    @foreach($usersWithoutCandidature as $user)
                        <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }} - {{ $user->email }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="form-label block text-gray-700 text-sm font-bold mb-2">Type d'Adhésion :</label>
                <select name="type" id="type-user" class="form-select mt-1 block w-full">
                    <option value="">Sélectionnez le type</option>
                    <option value="benevole">Bénévole</option>
                    <option value="commercant">Commerçant</option>
                </select>
            </div>

            <div id="benevole-section" style="display: none;">

                <div id="skills-section" class="mb-6">
                    <label class="form-label block text-gray-700 text-sm font-bold mb-4">Compétences :</label>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($skills as $skill)
                            <div class="flex items-center space-x-3">
                                <input
                                    class="form-check-input h-4 w-4 text-indigo-600 transition duration-150 ease-in-out rounded border-gray-300 focus:ring-indigo-500"
                                    type="checkbox"
                                    name="skills[]"
                                    value="{{ $skill->id }}"
                                    {{ in_array($skill->id, old('skills', $selectedSkills ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-gray-600 ml-2">
                                    {{ $skill->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="motivation" class="block text-sm font-medium text-gray-700">Motivation</label>
                    <textarea name="motivation" id="motivation" required
                                placeholder="Expliquez ce qui vous motive à devenir bénévole..."
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('motivation') }}</textarea>
                </div>

                <div>
                    <label for="experience" class="block text-sm font-medium text-gray-700 mt-6">Expérience</label>
                    <textarea name="experience" id="experience" required
                                placeholder="Décrivez vos expériences antérieures en lien avec le bénévolat..."
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('experience') }}</textarea>
                </div>

                <div>
                    <label for="old_benevole" class="block text-sm font-medium text-gray-700 mt-6">Ancien bénévole</label>
                    <input type="hidden" name="old_benevole" value="0"> 
                    <input type="checkbox" name="old_benevole" id="old_benevole" value="1" class="mt-2 align-middle"
                            {{ old('old_benevole') ? 'checked' : '' }}>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mt-6">Jours et moments de disponibilité :</label>
                    <div class="availability-grid">
                        @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $day)
                        <div class="day">
                            <strong>{{ ucfirst($day) }} :</strong>
                            <label><input type="checkbox" name="availability[{{ $day }}][matin]" value="1" {{ (old("availability.$day.matin", $availability[$day]['matin'] ?? false) == '1') ? 'checked' : '' }}> Matin (9h-12h)</label>
                            <label><input type="checkbox" name="availability[{{ $day }}][midi]" value="1" {{ (old("availability.$day.midi", $availability[$day]['midi'] ?? false) == '1') ? 'checked' : '' }}> Après-midi (14h-17h)</label>
                            <label><input type="checkbox" name="availability[{{ $day }}][soir]" value="1" {{ (old("availability.$day.soir", $availability[$day]['soir'] ?? false) == '1') ? 'checked' : '' }}> Soir (19h-23h)</label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="availability_begin" class="block text-sm font-medium text-gray-700 mt-6">Date de début</label>
                        <input type="date" name="availability_begin" id="availability_begin" required
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                value="{{ old('availability_begin') }}">
                    </div>
                    <div>
                        <label for="availability_end" class="block text-sm font-medium text-gray-700 mt-6">Date de fin</label>
                        <input type="date" name="availability_end" id="availability_end" required
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                value="{{ old('availability_end') }}">
                    </div>
                </div>

                <div>
                    <label for="permis" class="block text-sm font-medium text-gray-700 mt-6">Permis de conduire</label>
                    <input type="hidden" name="permis" value="0">
                    <input type="checkbox" name="permis" id="permis" value="1" class="mt-2 align-middle"
                            {{ (old('permis')) ? 'checked' : '' }}>
                </div>

                <div>
                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 mt-6">Notes supplémentaires</label>
                    <textarea name="additional_notes" id="additional_notes"
                                placeholder="Toute information supplémentaire que vous souhaitez ajouter..."
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('additional_notes') }}</textarea>
                </div>
            </div>

            <div id="commercant-section" style="display: none;">
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mt-6">Nom de l'entreprise</label>
                    <input type="text" name="company_name" id="company_name" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           placeholder="Entrez le nom de votre entreprise"
                           value="{{ old('company_name') }}">
                </div>

                <div>
                    <label for="siret" class="block text-sm font-medium text-gray-700 mt-6">SIRET</label>
                    <input type="text" name="siret" id="siret" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           placeholder="Numéro SIRET de l'entreprise"
                           value="{{ old('siret') }}">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mt-6">Adresse</label>
                    <textarea name="address" id="address" required
                              class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                              placeholder="Adresse complète de l'entreprise">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mt-6">Ville</label>
                        <input type="text" name="city" id="city" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               placeholder="Ville de l'entreprise"
                               value="{{ old('city') }}">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mt-6">Code Postal</label>
                        <input type="text" name="postal_code" id="postal_code" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               placeholder="Code postal"
                               value="{{ old('postal_code') }}">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mt-6">Pays</label>
                        <input type="text" name="country" id="country" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               placeholder="Pays de localisation"
                               value="{{ old('country') }}">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="contract_start_date" class="block text-sm font-medium text-gray-700 mt-6">Date de début</label>
                        <input type="date" name="contract_start_date" id="contract_start_date" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('contract_start_date') }}">
                    </div>
                    <div>
                        <label for="contract_end_date" class="block text-sm font-medium text-gray-700 mt-6">Date de fin</label>
                        <input type="date" name="contract_end_date" id="contract_end_date" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('contract_end_date') }}">
                    </div>
                </div>

                <div>
                    <label for="opening_hours" class="block text-sm font-medium text-gray-700 mt-6">Horaire d'ouverture</label>
                    <input type="text" name="opening_hours" id="opening_hours"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           placeholder="Par exemple : 9h - 18h, du lundi au vendredi"
                           value="{{ old('opening_hours') }}">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mt-6">Notes</label>
                    <textarea name="notes" id="notes"
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                        placeholder="Notes supplémentaires sur l'entreprise">{{ old('notes') }}</textarea>

                </div>
                
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
                    Soumettre le formulaire
                </button>
            </div>
        </form>
    </div>
</div>

<script>
        document.getElementById('type-user').addEventListener('change', function () {
        var type = this.value;
        console.log('Type sélectionné:', type); 

        var benevoleSection = document.getElementById('benevole-section');
        var commercantSection = document.getElementById('commercant-section');

        benevoleSection.style.display = (type === 'benevole' ? 'block' : 'none');
        commercantSection.style.display = (type === 'commercant' ? 'block' : 'none');

        var inputsBenevole = benevoleSection.querySelectorAll('input, textarea, select');
        inputsBenevole.forEach(function(input) {
            if (type === 'benevole') {
                if (input.type !== 'checkbox') {
                    input.setAttribute('required', '');
                }
            } else {
                input.removeAttribute('required');
            }
        });

        var inputsCommercant = commercantSection.querySelectorAll('input, textarea, select');
        inputsCommercant.forEach(function(input) {
            if (type === 'commercant') {
                input.setAttribute('required', '');
            } else {
                input.removeAttribute('required');
            }
        });
    });
</script>
@endsection


