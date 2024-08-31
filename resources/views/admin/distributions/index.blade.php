@extends('layouts.templateAdmin')
@section('title', 'Gestion des Distributions | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Gestion des Distributions</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between">
        <a href="{{ route('admin.distributions.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-blue-800 transition duration-300">
            <i class="fas fa-plus"></i>&nbsp; Créer une Distribution
        </a>

        <form action="{{ route('admin.distributions.index') }}" method="GET" class="flex space-x-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            
            <select name="status" class="px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Statut --</option>
                <option value="Planifié" {{ request('status') == 'Planifié' ? 'selected' : '' }}>Planifié</option>
                <option value="Attribué" {{ request('status') == 'Attribué' ? 'selected' : '' }}>Attribué</option>
                <option value="Terminé" {{ request('status') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
            </select>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 transition duration-300">Filtrer</button>
        </form>
    </div>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <tr>
                                <th class="py-3 px-4 text-left font-medium">Destinataire</th>
                                <th class="py-3 px-4 text-left font-medium">Adresse</th>
                                <th class="py-3 px-4 text-left font-medium">Téléphone</th>
                                <th class="py-3 px-4 text-left font-medium">Date souhaitée</th>
                                <th class="py-3 px-4 text-left font-medium">Statut</th>
                                <th class="py-3 px-4 text-left font-medium">Bénévole</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($distributions as $distribution)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $distribution->destinataire }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $distribution->adresse }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $distribution->telephone }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ \Carbon\Carbon::parse($distribution->date_souhaitee)->format('d/m/Y') }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $distribution->status }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if($distribution->benevole)
                                        {{ $distribution->benevole->user->firstname }} {{ $distribution->benevole->user->lastname }}
                                    @else
                                        Non attribué
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <a href="{{ route('admin.distributions.edit', $distribution->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                    <form action="{{ route('admin.distributions.destroy', $distribution->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette distribution ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="pagination-container" class="mt-4">
                        {{ $distributions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
