@extends('layouts.templateAdmin')

@section('title', 'Détails de la Proposition de Service')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-700">Détails de la Proposition de Service</h1>
                    <a href="{{ route('propose.index') }}" class="flex items-center bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-gray-700 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mt-6">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Nom du Service</h2>
                        <p class="text-gray-700">{{ $proposal->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Bénévole</h2>
                        <p class="text-gray-700">
                            @if($proposal->user)
                                {{ $proposal->user->firstname }} {{ $proposal->user->lastname }}
                            @else
                                Bénévole non attribué
                            @endif
                        </p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Catégorie</h2>
                        <p class="text-gray-700">{{ $proposal->category }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Description</h2>
                        <p class="text-gray-700">{{ $proposal->description ?? 'Aucune description fournie' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Statut</h2>
                        <p class="text-gray-700">{{ ucfirst($proposal->status) }}</p>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('propose.edit', $proposal->id) }}" class="flex items-center bg-yellow-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-yellow-700 transition duration-300">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <form action="{{ route('propose.update', $proposal->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="accepté">
                            <button type="submit" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-700 transition duration-300">
                                <i class="fas fa-check mr-2"></i> Accepter
                            </button>
                        </form>
                        <form action="{{ route('propose.update', $proposal->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="refusé">
                            <button type="submit" class="flex items-center bg-red-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-700 transition duration-300">
                                <i class="fas fa-times mr-2"></i> Refuser
                            </button>
                        </form>
                        <form action="{{ route('propose.destroy', $proposal->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center bg-red-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-700 transition duration-300">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
