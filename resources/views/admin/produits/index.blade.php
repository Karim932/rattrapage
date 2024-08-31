@extends('layouts.templateAdmin')
@section('title', 'Gestion des Produits | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Gestion des Produits</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded">
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

    <div class="mb-6">
        <a href="{{ route('admin.stock.index') }}" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">
            Retour à la gestion du stock
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Ajouter un Nouveau Produit</h2>
        <form action="{{ route('admin.produits.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="marque" class="block text-sm font-medium text-gray-700">Marque</label>
                    <input type="text" id="marque" name="marque" value="{{ old('marque') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700">Catégorie</label>
                    <input type="text" id="categorie" name="categorie" value="{{ old('categorie') }}" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="code_barre" class="block text-sm font-medium text-gray-700">Code-barres</label>
                    <input type="text" id="code_barre" name="code_barre" value="{{ old('code_barre') }}" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Ajouter</button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Liste des Produits</h2>
        <div class="mb-6">
            <form action="{{ route('admin.produits.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" placeholder="Rechercher un produit par nom ou code-barres"
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       value="{{ request('search') }}">
                <button type="submit" class="ml-4 bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                    Rechercher
                </button>
            </form>
        </div>
        <table class="min-w-full bg-white">
            <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-4 text-left font-medium">
                        <a href="{{ route('admin.produits.index', ['sort' => 'nom', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                            Nom
                            @if(request('sort') == 'nom')
                                <i class="fas fa-arrow-{{ request('direction') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 text-left font-medium">
                        <a href="{{ route('admin.produits.index', ['sort' => 'marque', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                            Marque
                            @if(request('sort') == 'marque')
                                <i class="fas fa-arrow-{{ request('direction') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 text-left font-medium">
                        <a href="{{ route('admin.produits.index', ['sort' => 'categorie', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                            Catégorie
                            @if(request('sort') == 'categorie')
                                <i class="fas fa-arrow-{{ request('direction') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 text-left font-medium">
                        <a href="{{ route('admin.produits.index', ['sort' => 'code_barre', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                            Code-barres
                            @if(request('sort') == 'code_barre')
                                <i class="fas fa-arrow-{{ request('direction') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 text-left font-medium">
                        <a href="{{ route('admin.produits.index', ['sort' => 'stocks_count', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                            Nombre de Lots
                            @if(request('sort') == 'stocks_count')
                                <i class="fas fa-arrow-{{ request('direction') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 text-left font-medium">Actions</th>
                </tr>
            </thead>
            
            <tbody class="text-gray-700">
                @foreach ($produits as $produit)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $produit->nom }}</td>
                        <td class="py-3 px-4">{{ $produit->marque ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $produit->categorie }}</td>
                        <td class="py-3 px-4">{{ $produit->code_barre }}</td>
                        <td class="py-3 px-4">{{ $produit->stocks_count }}</td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.produits.edit', $produit->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            <form action="{{ route('admin.produits.destroy', $produit->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">
            {{ $produits->links() }}
        </div>
    </div>
</div>
@endsection
