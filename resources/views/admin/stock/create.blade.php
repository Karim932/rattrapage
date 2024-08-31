@extends('layouts.templateAdmin')
@section('title', 'Ajouter au Stock | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Ajouter au Stock</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.stock.store') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="produit_frais" class="inline-flex items-center">
                <input type="checkbox" id="produit_frais" name="produit_frais" class="form-checkbox h-5 w-5 text-indigo-600">
                <span class="ml-2 text-gray-700">Produit Frais</span>
            </label>
        </div>

        <div class="mb-6">
            <label for="produit_id" class="block text-sm font-medium text-gray-700">Sélectionner un Produit</label>
            <select id="produit_id" name="produit_id" required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Sélectionner un produit --</option>
                @foreach ($produits->sortBy('nom', SORT_NATURAL | SORT_FLAG_CASE) as $produit)
                    <option value="{{ $produit->id }}" data-frais="{{ in_array($produit->code_barre, range(1000000001, 1000000020)) ? '1' : '0' }}">{{ $produit->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6" id="quantity_section">
            <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
            <input type="number" id="quantite" name="quantite" min="1" step="1" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6 hidden" id="poids_section">
            <label for="poids" class="block text-sm font-medium text-gray-700">Poids (en kg)</label>
            <input type="number" id="poids" name="quantite" min="0.01" step="0.01"
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label for="emplacement_section" class="block text-sm font-medium text-gray-700">Emplacement</label>
            <div class="flex space-x-4">
                <select id="location_section" name="location_section" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Section</option>
                    @foreach(range('A', 'D') as $section)
                        <option value="{{ $section }}">{{ $section }}</option>
                    @endforeach
                </select>
                <select id="location_allee" name="location_allee" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Allée</option>
                    @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <select id="location_etagere" name="location_etagere" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Étagère</option>
                    @foreach(range('A', 'Z') as $etagere)
                        <option value="{{ $etagere }}">{{ $etagere }}</option>
                    @endforeach
                </select>
                <select id="location_position" name="location_position" class="w-1/4 px-3 py-2 border rounded">
                    <option value="">Position</option>
                    @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="mb-6 hidden" id="froid_section">
            <label for="emplacement_froid" class="block text-sm font-medium text-gray-700">Emplacement Froid</label>
            <select id="emplacement_froid" name="emplacement" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Sélectionner un emplacement froid --</option>
                <option value="FRIGO_A">FRIGO A</option>
                <option value="FRIGO_B">FRIGO B</option>
                <option value="FRIGO_C">FRIGO C</option>
                <option value="CONGELATEUR_A">CONGÉLATEUR A</option>
                <option value="CONGELATEUR_B">CONGÉLATEUR B</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="date_entree" class="block text-sm font-medium text-gray-700">Date d'entrée</label>
            <input type="date" id="date_entree" name="date_entree" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label for="date_expiration" class="block text-sm font-medium text-gray-700">Date d'expiration</label>
            <input type="date" id="date_expiration" name="date_expiration" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.stock.index') }}" class="bg-gray-500 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">
                Retour
            </a>
            <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                Ajouter au Stock
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
        produitSelect.value = ""; // Réinitialiser la sélection du produit

        if (produitFraisCheckbox.checked) {
            quantitySection.classList.add('hidden');
            quantitySection.querySelector('input').removeAttribute('name');
            poidsSection.querySelector('input').setAttribute('name', 'quantite');
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
            quantitySection.classList.remove('hidden');
            quantitySection.querySelector('input').setAttribute('name', 'quantite');
            poidsSection.querySelector('input').removeAttribute('name');
            poidsSection.querySelector('input').required = false;
            poidsSection.classList.add('hidden');
            emplacementFroid.classList.add('hidden');
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
    toggleSections();
});
</script>

@endsection
