@extends('layouts.templateAdmin')
@section('title', 'Administrateur | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Candidatures des Utilisateurs</h1>
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Bouton Ajouter un Utilisateur -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('adhesion.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-blue-800 transition duration-300">
            <i class="fas fa-clipboard-list"></i>&nbsp; Faire une demande
        </a>
    </div>

    {{-- <!-- Conteneur pour les messages de succès -->
    <div id="success-message" class="hidden bg-green-700 text-white p-4 mb-4 rounded-lg shadow-md">
        <span id="success-text">{{ session('success') }}</span>
    </div> --}}
    <!-- Conteneur pour les messages de succès -->
    <div id="success-message" class="hidden bg-green-700 text-white p-4 mb-4 rounded-lg shadow-md">
        <span id="success-text"></span>
    </div>

    <!-- Barre de Recherche -->
    <div class="mb-5">
        <input type="text" id="search-users" placeholder="Rechercher des utilisateurs..." class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:shadow-outline">
    </div>

    <!-- Barre de filtrage -->
    <div class="my-6">
        <form action="{{ route('adhesion.index') }}" method="GET" class="flex flex-wrap items-center">
            <div class="mr-4 mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Statut :</label>
                <select class="mt-1 block w-full px-7 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="status" name="status">
                    <option value="">Tous</option>
                    <option value="en attente">En attente</option>
                    <option value="en cours">En cours</option>
                    <option value="accepté">Accepté</option>
                    <option value="refusé">Rejeté</option>
                </select>
            </div>

            <div class="mr-4 mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Rôle :</label>
                <select class="mt-1 block w-full px-7 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="type" name="type">
                    <option value="">Tous</option>
                    <option value="Bénévole">Bénévole</option>
                    <option value="Commerçant">Commerçant</option>
                </select>
            </div>

            <button type="submit" class="self-align-end px-6 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Tableau des candidatures -->
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div id="users-table" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom / Entreprise
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type de Demande
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date de Demande
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($allCandidatures as $candidature)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $candidature->id }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($candidature->fusion instanceof App\Models\AdhesionCommercant)
                                        {{ $candidature->fusion->company_name }}
                                    @elseif ($candidature->fusion instanceof App\Models\AdhesionBenevole)
                                        {{ $candidature->fusion->user->firstname }}
                                        {{ $candidature->fusion->user->lastname }}
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($candidature->fusion && $candidature->fusion->user)
                                        {{ $candidature->fusion->user->email }}
                                    @else
                                        Email non disponible
                                    @endif
                                </td>
                                <td class="text-sm text-gray-700">
                                    @if ($candidature->candidature_type == 'App\Models\AdhesionCommercant')
                                        &nbsp;&nbsp;&nbsp;&nbsp; Commerçant
                                    @elseif ($candidature->candidature_type == 'App\Models\AdhesionBenevole')
                                        &nbsp;&nbsp;&nbsp;&nbsp; Bénévole
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($candidature->fusion)
                                        {{ $candidature->fusion->status }}
                                    @else
                                        Statut non disponible
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($candidature->fusion)
                                        {{ $candidature->fusion->created_at->format('d/m/Y') }}
                                    @else
                                        Statut non disponible
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <!-- Boutons d'action ici -->
                                    <div class="flex items-center">
                                        <a href="{{ route('admin.adhesion.show', ['id' => $candidature->id, 'type' => $candidature->candidature_type]) }}">Voir</a>
                                        <a href="{{ route('adhesion.edit', $candidature->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Modifier</a>
                                        <form action="{{ route('adhesion.destroy', $candidature->id) }}" method="POST" class="delete-user-form inline">
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
                        {{ $allCandidatures->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
