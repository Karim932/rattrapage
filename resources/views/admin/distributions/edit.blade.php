@extends('layouts.templateAdmin')
@section('title', 'Modifier une Distribution | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier la Distribution</h1>

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

    <form action="{{ route('admin.distributions.update', $distribution->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="destinataire" class="block text-sm font-medium text-gray-700">Destinataire</label>
                <input type="text" id="destinataire" name="destinataire" value="{{ old('destinataire', $distribution->destinataire) }}" required
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $distribution->telephone) }}" required
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="col-span-2">
                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $distribution->adresse) }}" required
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="date_souhaitee" class="block text-sm font-medium text-gray-700">Date souhaitée</label>
                <input type="date" id="date_souhaitee" name="date_souhaitee" value="{{ old('date_souhaitee', $distribution->date_souhaitee) }}" required
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="benevole_id" class="block text-sm font-medium text-gray-700">Attribuer à un Bénévole</label>
                <select id="benevole_id" name="benevole_id" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">-- Sélectionner un bénévole --</option>
                    @foreach ($benevoles as $benevole)
                        <option value="{{ $benevole->id }}" {{ $benevole->id == old('benevole_id', $distribution->benevole_id) ? 'selected' : '' }}>
                            {{ $benevole->user->firstname }} {{ $benevole->user->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut de la Distribution</label>
                <select id="status" name="status" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="Planifié" {{ old('status', $distribution->status) == 'Planifié' ? 'selected' : '' }}>Planifié</option>
                    <option value="En Cours" {{ old('status', $distribution->status) == 'En Cours' ? 'selected' : '' }}>En Cours</option>
                    <option value="Terminé" {{ old('status', $distribution->status) == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                </select>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-700 mb-4">Modifier les Aliments à Distribuer</h2>

        <div class="mb-6">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-4 text-left font-medium">Produit</th>
                        <th class="py-3 px-4 text-left font-medium">Quantité Disponible</th>
                        <th class="py-3 px-4 text-left font-medium">Quantité Sélectionnée</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($stocks as $stock)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $stock->produit->nom }} ({{ $stock->produit->code_barre }})</td>
                        <td class="py-3 px-4">{{ $stock->quantite - $stock->quantite_reservee }}</td>                        
                        <td class="py-3 px-4">
                            <input type="number" name="stocks[{{ $stock->id }}][quantite]" 
                                   value="{{ old("stocks.{$stock->id}.quantite", $distribution->stocks->find($stock->id)->pivot->quantite ?? '') }}" 
                                   min="0" max="{{ $stock->quantite }}" 
                                   class="w-20 px-2 py-1 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <input type="hidden" name="stocks[{{ $stock->id }}][stock_id]" value="{{ $stock->id }}">
                        </td>
                    </tr>
                @endforeach
                
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('admin.distributions.index') }}" class="bg-gray-500 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">
                Retour
            </a>
            <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                Mettre à jour la Distribution
            </button>
        </div>
    </form>
</div>
@endsection
