@extends('layouts.templateAdmin')
@section('title', 'Administrateur | NoMoreWaste')

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

    <h1 class="text-2xl font-bold text-gray-800 mb-4">Gestion des Collectes</h1>
    
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.collectes.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-blue-800 transition duration-300">
            <i class="fas fa-clipboard-list"></i>&nbsp; Créer une collecte
        </a>
    </div>

    
    <div class="my-6">
        <form action="{{ route('admin.collectes.index') }}" method="GET" class="flex flex-wrap items-center">
            <div class="mr-4 mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Statut :</label>
                <select class="mt-1 block w-full px-7 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="status" name="status">
                    <option value="">Tous</option>
                    <option value="En attente">En attente</option>
                    <option value="Attribué">Attribué</option>
                    <option value="Annulé">Annulé</option>
                    <option value="Terminé">Terminé</option>
                </select>
            </div>

            <div class="mr-4 mb-4">
                <label for="commercant" class="block text-sm font-medium text-gray-700">Commerçant :</label>
                <select class="mt-1 block w-full px-7 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="commercant" name="commercant">
                    <option value="">Tous</option>
                    @foreach($commercants as $commercant)
                        <option value="{{ $commercant->id }}">{{ $commercant->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mr-4 mb-4">
                <label for="benevole" class="block text-sm font-medium text-gray-700">Bénévole :</label>
                <select class="mt-1 block w-full px-7 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="benevole" name="benevole">
                    <option value="">Tous</option>
                    @foreach($benevoles as $benevole)
                        <option value="{{ $benevole->id }}">{{ $benevole->user->firstname }} {{ $benevole->user->lastname }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="self-align-end px-6 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Filtrer
            </button>
        </form>
    </div>

    
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div id="collectes-table" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <tr class="border-b border-gray-200">
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    <a href="{{ route('admin.collectes.index', ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                        ID
                                        @if(request('sort') == 'id')
                                            @if(request('direction') == 'asc')
                                                <i class="fas fa-arrow-up"></i>
                                            @else
                                                <i class="fas fa-arrow-down"></i>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    Commerçant
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    Date de Collecte
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    Statut
                                </th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">
                                    Bénévole Attribué
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($collectes as $collecte)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $collecte->id }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $collecte->commercant->company_name }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $collecte->date_collecte }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $collecte->status }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($collecte->benevole)
                                        {{ $collecte->benevole->user->firstname }}
                                        {{ $collecte->benevole->user->lastname }}
                                    @else
                                        Non attribué
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center">
                                        <a href="{{ route('admin.collectes.show', $collecte->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        <a href="{{ route('admin.collectes.edit', $collecte->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Modifier</a>
                                        <form action="{{ route('admin.collectes.destroy', $collecte->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette collecte ?');" class="inline">
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
                        {{ $collectes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
th {
    background-color: #f9fafb;
}

th a {
    display: inline-flex;
    align-items: center;
    color: #4a5568;
    text-decoration: none;
    font-weight: 100;
    transition: color 0.3s ease;
}

th a:hover {
    color: #283e64;
}

th i.fas {
    color: #a0aec0;
}

th i.fas.fa-arrow-up,
th i.fas.fa-arrow-down {
    margin-left: 8px;
}

tr.border-b {
    border-bottom-width: 1px;
}
</style>
@endsection
