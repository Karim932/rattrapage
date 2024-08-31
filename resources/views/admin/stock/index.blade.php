@extends('layouts.templateAdmin')
@section('title', 'Gestion de Stock | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
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

    <h1 class="text-2xl font-bold text-gray-800 mb-4">Gestion du Stock</h1>
    
    <div class="mb-6 flex justify-between">
        <div class="flex space-x-4">
            <a href="{{ route('admin.stock.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-blue-800 transition duration-300">
                <i class="fas fa-plus"></i>&nbsp; Ajouter au stock
            </a>
            <a href="{{ route('admin.produits.index') }}" class="bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-800 transition duration-300">
                <i class="fas fa-barcode"></i>&nbsp; Gérer les produits (codes-barres)
            </a>
        </div>
        <!-- Formulaire pour la barre de recherche indépendante -->
        <form action="{{ route('admin.stock.index') }}" method="GET" class="flex space-x-4">
            <!-- Inclure les filtres existants lors de la recherche -->
            <input type="hidden" name="emplacement" value="{{ request('emplacement') }}">
            <input type="hidden" name="expiring_soon" value="{{ request('expiring_soon') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom ou code-barres" 
                   class="px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Formulaire pour les filtres -->
    <div class="my-6">
        <form action="{{ route('admin.stock.index') }}" method="GET" class="flex space-x-4">
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

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div id="stocks-table" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <tr class="border-b border-gray-200">
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    <a href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'produit_nom', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Produit
                                        @if(request('sort') == 'produit_nom')
                                            <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    <a href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'quantite', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Quantité
                                        @if(request('sort') == 'quantite')
                                            <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    <a href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'emplacement', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Emplacement
                                        @if(request('sort') == 'emplacement')
                                            <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    <a href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'date_entree', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Date d'entrée
                                        @if(request('sort') == 'date_entree')
                                            <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    <a href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'date_expiration', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Date d'expiration
                                        @if(request('sort') == 'date_expiration')
                                            <i class="fas fa-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($stocks as $stock)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $stock->produit->nom }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $stock->quantite }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $stock->emplacement }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ \Carbon\Carbon::parse($stock->date_entree)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $stock->date_expiration ? \Carbon\Carbon::parse($stock->date_expiration)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center">
                                        <a href="{{ route('admin.stock.edit', $stock->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                        <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément du stock ? Cela supprimera tout.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="pagination-container" class="mt-4">
                        {{ $stocks->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
