@extends('layouts.templateAdmin')
@section('title', 'Sélectionner les Aliments | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Sélectionner les Aliments pour la Distribution</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Barre de recherche et filtres -->
    <div class="mb-6 flex justify-between">
        <div class="flex space-x-4">
            <form action="{{ route('admin.distributions.selectStock') }}" method="GET" class="flex space-x-4">
                <input type="hidden" name="emplacement" value="{{ request('emplacement') }}">
                <input type="hidden" name="expiring_soon" value="{{ request('expiring_soon') }}">
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom ou code-barres"
                       class="px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                    Rechercher
                </button>
            </form>
        </div>

        <!-- Formulaire pour les filtres -->
        <div class="flex space-x-4">
            <form action="{{ route('admin.distributions.selectStock') }}" method="GET" class="flex space-x-4">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <select name="emplacement" class="px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Emplacement --</option>
                    <option value="FROID" {{ request('emplacement') == 'FROID' ? 'selected' : '' }}>FROID</option>
                    <option value="STANDARD" {{ request('emplacement') == 'STANDARD' ? 'selected' : '' }}>STANDARD</option>
                </select>
                <label for="expiring_soon" class="inline-flex items-center">
                    <input type="checkbox" name="expiring_soon" id="expiring_soon" {{ request('expiring_soon') ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-indigo-600">
                    <span class="ml-2">Expiration Proche (7 jours)</span>
                </label>
                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                    Filtrer
                </button>
            </form>
        </div>
    </div>

    <form action="{{ route('admin.distributions.storeStep2') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="benevole_id" class="block text-sm font-medium text-gray-700">Attribuer à un Bénévole</label>
            <select id="benevole_id" name="benevole_id" required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Sélectionner un bénévole --</option>
                @foreach ($benevoles as $benevole)
                    <option value="{{ $benevole->id }}">{{ $benevole->user->firstname }} {{ $benevole->user->lastname }}</option>
                @endforeach
            </select>
        </div>

        <h2 class="text-xl font-bold text-gray-700 mb-4">Sélectionner les Aliments</h2>

        <div id="stocks_table" class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-4 text-left font-medium">
                            <a href="{{ route('admin.distributions.selectStock', array_merge(request()->query(), ['sort' => 'produit_nom', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Produit
                                @if(request('sort') == 'produit_nom')
                                    <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="py-3 px-4 text-left font-medium">
                            <a href="{{ route('admin.distributions.selectStock', array_merge(request()->query(), ['sort' => 'quantite', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Quantité Disponible
                                @if(request('sort') == 'quantite')
                                    <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="py-3 px-4 text-left font-medium">
                            <a href="{{ route('admin.distributions.selectStock', array_merge(request()->query(), ['sort' => 'date_expiration', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                Date d'Expiration
                                @if(request('sort') == 'date_expiration')
                                    <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="py-3 px-4 text-left font-medium">Quantité à Distribuer</th>
                    </tr>
                </thead>
                <tbody id="stocks_body" class="text-gray-700">
                    @foreach ($stocks as $stock)
                        @php
                            $disponible = $stock->quantite - $stock->quantite_reservee;
                        @endphp
                        @if ($disponible > 0)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-4">{{ $stock->produit->nom }}</td>
                                <td class="py-3 px-4">{{ $disponible }}</td>
                                <td class="py-3 px-4">{{ $stock->date_expiration ? \Carbon\Carbon::parse($stock->date_expiration)->format('d/m/Y') : 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <input type="number" name="stocks[{{ $loop->index }}][quantite]" min="1" max="{{ $disponible }}"
                                           class="w-24 px-3 py-2 border rounded" placeholder="Quantité">
                                    <input type="hidden" name="stocks[{{ $loop->index }}][stock_id]" value="{{ $stock->id }}">
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                
            </table>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('admin.distributions.create') }}" class="bg-gray-500 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">
                Retour
            </a>
            <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300 ml-4">
                Créer la Distribution
            </button>
        </div>
    </form>
</div>
@endsection
