@extends('layouts.templateAdmin')
@section('title', 'Détails de la Collecte | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <a href="{{ route('admin.collectes.index') }}" class="inline-flex items-center bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste des collectes
    </a>
    <h1 class="text-2xl font-bold text-gray-800 mt-4 mb-4">Détails de la Collecte</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex">
        
        <div class="w-2/3 pr-4">
            <div class="mb-6">
                <h2 class="text-lg font-bold">Commerçant :</h2>
                <p>{{ $collecte->commercant->company_name }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-bold">Date de Collecte :</h2>
                <p>{{ $collecte->date_collecte }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-bold">Instructions :</h2>
                <p>{{ $collecte->instructions }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-bold">Statut :</h2>
                <form action="{{ route('admin.collectes.updateStatus', $collecte->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center">
                        <select id="status" name="status" required class="mt-1 block w-full max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="En Attente" {{ $collecte->status == 'En attente' ? 'selected' : '' }}>En attente</option>
                            <option value="Annulé" {{ $collecte->status == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                            <option value="Terminé" {{ $collecte->status == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                        </select>
                        <button type="submit" class="ml-4 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-bold">Bénévole Attribué :</h2>
                <form action="{{ route('admin.collectes.assign', $collecte->id) }}" method="POST">
                    @csrf
                    <label for="benevole_id" class="block text-sm font-medium text-gray-700">Attribuer ou Modifier le Bénévole</label>
                    <select id="benevole_id" name="benevole_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach($benevoles as $benevole)
                            <option value="{{ $benevole->id }}" 
                                {{ $collecte->benevole_id == $benevole->id ? 'selected' : '' }}>
                                {{ $benevole->user->firstname }} {{ $benevole->user->lastname }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="mt-4 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                        Attribuer
                    </button>
                </form>
            </div>
        </div>

        
        <div class="w-1/3 bg-white shadow-md rounded p-6">
            <h2 class="text-lg font-bold">Résumé des Produits Entrés en Stock :</h2>
            @if($collecte->stock_entries)
                <ul class="list-disc pl-4 mt-4">
                    @foreach(json_decode($collecte->stock_entries, true) as $product)
                        <li>{{ $product['nom'] ?? 'Produit inconnu' }} - {{ $product['quantite'] }} unités - Emplacement : {{ $product['emplacement'] }}</li>
                    @endforeach
                </ul>
            @else
                <p>Aucun produit entré en stock pour cette collecte.</p>
            @endif
        </div>
    </div>
</div>

@endsection
