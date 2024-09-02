@extends('layouts.templateAdmin')
@section('title', 'Modifier le Stock | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier le Stock</h1>

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

    <form action="{{ route('admin.stock.update', $stock->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="produit_frais" class="inline-flex items-center">
                <input type="checkbox" id="produit_frais" name="produit_frais" class="form-checkbox h-5 w-5 text-indigo-600" {{ old('produit_frais', $stock->produit_frais) == 1 ? 'checked' : '' }}>
                <span class="ml-2 text-gray-700">Produit Frais</span>
            </label>
        </div>

        <div class="mb-6">
            <label for="produit_id" class="block text-sm font-medium text-gray-700">Sélectionner un Produit</label>
            <select id="produit_id" name="produit_id" required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Sélectionner un produit --</option>
                @foreach ($produits->sortBy('nom', SORT_NATURAL | SORT_FLAG_CASE) as $produit)
                    <option value="{{ $produit->id }}" data-frais="{{ in_array($produit->code_barre, range(1000000001, 1000000020)) ? '1' : '0' }}" {{ old('produit_id', $stock->produit_id) == $produit->id ? 'selected' : '' }}>
                        {{ $produit->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6" id="quantity_section">
            <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
            <input type="number" id="quantite" name="quantite" value="{{ old('quantite', $stock->quantite) }}" min="1" step="1" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6 hidden" id="poids_section">
            <label for="poids" class="block text-sm font-medium text-gray-700">Poids (en kg)</label>
            <input type="number" id="poids" name="poids" value="{{ old('poids', $stock->quantite) }}" min="0.01" step="0.01" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6" id="emplacement_section">
            <label for="location_section" class="block text-sm font-medium text-gray-700">Emplacement</label>
            <div class="flex space-x-4">
                <select id="location_section" name="location_section" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Section</option>
                    @foreach(range('A', 'D') as $section)
                        <option value="{{ $section }}" {{ old('location_section', substr($stock->emplacement, 0, 1)) == $section ? 'selected' : '' }}>{{ $section }}</option>
                    @endforeach
                </select>
                <select id="location_allee" name="location_allee" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Allée</option>
                    @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}" {{ old('location_allee', substr($stock->emplacement, 1, 1)) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <select id="location_etagere" name="location_etagere" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Étagère</option>
                    @foreach(range('A', 'Z') as $etagere)
                        <option value="{{ $etagere }}" {{ old('location_etagere', substr($stock->emplacement, 2, 1)) == $etagere ? 'selected' : '' }}>{{ $etagere }}</option>
                    @endforeach
                </select>
                <select id="location_position" name="location_position" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Position</option>
                    @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}" {{ old('location_position', substr($stock->emplacement, 3, 1)) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="mb-6 hidden" id="froid_section">
            <label for="emplacement_froid" class="block text-sm font-medium text-gray-700">Emplacement Froid</label>
            <select id="emplacement_froid" name="emplacement" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Sélectionner un emplacement froid --</option>
                <option value="FRIGO_A" {{ old('emplacement', $stock->emplacement) == 'FRIGO_A' ? 'selected' : '' }}>FRIGO A</option>
                <option value="FRIGO_B" {{ old('emplacement', $stock->emplacement) == 'FRIGO_B' ? 'selected' : '' }}>FRIGO B</option>
                <option value="FRIGO_C" {{ old('emplacement', $stock->emplacement) == 'FRIGO_C' ? 'selected' : '' }}>FRIGO C</option>
                <option value="CONGELATEUR_A" {{ old('emplacement', $stock->emplacement) == 'CONGELATEUR_A' ? 'selected' : '' }}>CONGÉLATEUR A</option>
                <option value="CONGELATEUR_B" {{ old('emplacement', $stock->emplacement) == 'CONGELATEUR_B' ? 'selected' : '' }}>CONGÉLATEUR B</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="date_entree" class="block text-sm font-medium text-gray-700">Date d'entrée</label>
            <input type="date" id="date_entree" name="date_entree" value="{{ old('date_entree', $stock->date_entree) }}" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label for="date_expiration" class="block text-sm font-medium text-gray-700">Date d'expiration</label>
            <input type="date" id="date_expiration" name="date_expiration" value="{{ old('date_expiration', $stock->date_expiration) }}" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.stock.index') }}" class="bg-gray-500 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">
                Retour
            </a>
            <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                Mettre à jour le Stock
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const produitFraisCheckbox = document.getElementById('produit_frais');
        const quantitySection = document.getElementById('quantity_section');
        const poidsSection = document.getElementById('poids_section');
        const locationSection = document.getElementById('location_section');
        const locationAllee = document.getElementById('location_allee');
        const locationEtagere = document.getElementById('location_etagere');
        const locationPosition = document.getElementById('location_position');
        const emplacementFroid = document.getElementById('froid_section');
        const produitSelect = document.getElementById('produit_id');

        function toggleSections() {
            if (produitFraisCheckbox.checked) {
                quantitySection.classList.add('hidden');
                poidsSection.querySelector('input').required = true;
                poidsSection.classList.remove('hidden');
                emplacementFroid.classList.remove('hidden');
                locationSection.parentElement.classList.add('hidden');

                produitSelect.querySelectorAll('option').forEach(option => {
                    if (option.dataset.frais === '0') {
                        option.hidden = true;
                    } else {
                        option.hidden = false;
                    }
                });

                quantitySection.querySelector('input').required = false;
                locationSection.required = false;
                locationAllee.required = false;
                locationEtagere.required = false;
                locationPosition.required = false;
            } else {
                poidsSection.classList.add('hidden');
                poidsSection.querySelector('input').required = false;
                emplacementFroid.classList.add('hidden');
                quantitySection.classList.remove('hidden');
                locationSection.parentElement.classList.remove('hidden');

                produitSelect.querySelectorAll('option').forEach(option => {
                    if (option.dataset.frais === '1') {
                        option.hidden = true;
                    } else {
                        option.hidden = false;
                    }
                });

                quantitySection.querySelector('input').required = true;
                locationSection.required = true;
                locationAllee.required = true;
                locationEtagere.required = true;
                locationPosition.required = true;
            }
        }

        produitFraisCheckbox.addEventListener('change', toggleSections);

        toggleSections(); // Appel initial pour définir l'état correct
    });
</script>

@endsection
