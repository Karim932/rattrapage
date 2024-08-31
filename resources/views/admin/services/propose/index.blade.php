@extends('layouts.templateAdmin')

@section('title', 'Gestion des Propositions de Services')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-700">Propositions de Services</h1>
                    <a href="{{ route('propose.create') }}" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Créer une Proposition
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($proposals->isEmpty())
                    <p class="text-gray-700">Aucune proposition de service pour le moment.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Service</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bénévole</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proposals as $proposal)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-200">{{ $proposal->name }}</td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            @if($proposal->user)
                                                {{ $proposal->user->firstname }} {{ $proposal->user->lastname }}
                                            @else
                                                Bénévole non attribué
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">{{ ucfirst($proposal->status) }}</td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <div class="flex items-center space-x-4">
                                                <a href="{{ route('propose.show', $proposal->id) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                                    Voir
                                                </a>
                                                <a href="{{ route('propose.edit', $proposal->id) }}" class="text-yellow-600 hover:text-yellow-900 flex items-center">
                                                    Modifier
                                                </a>
                                                <form action="{{ route('propose.update', $proposal->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="accepté" class="accept-user-form inline">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 flex items-center">
                                                        Accepter
                                                    </button>
                                                </form>
                                                <form action="{{ route('propose.update', $proposal->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="refusé" class="refuse-user-form inline">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                                        Refuser
                                                    </button>
                                                </form>
                                                <form action="{{ route('propose.destroy', $proposal->id) }}" method="POST" class="delete-user-form inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $proposals->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
