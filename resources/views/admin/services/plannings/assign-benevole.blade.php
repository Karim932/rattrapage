@extends('layouts.templateAdmin')

@section('title', 'Assigner des Bénévoles')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="container mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Gérer les Bénévoles pour l'Événement</h2>

        <!-- Section pour afficher les bénévoles déjà assignés -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-xl font-medium text-gray-700 mb-4">Bénévoles assignés</h3>
            @if($assignedBenevoles->isEmpty())
                <p class="text-gray-600">Aucun bénévole n'est encore assigné à cet événement.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($assignedBenevoles as $benevole)
                        <li class="py-4 flex justify-between items-center">
                            <div class="text-gray-700">
                                {{ $benevole->user->firstname }}
                                {{ $benevole->user->lastname }} 
                            </div>
                            <form action="{{ route('plannings.removeBenevole', [$planning->id, $benevole->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-md shadow-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Section pour ajouter un nouveau bénévole -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-medium text-gray-700 mb-4">Ajouter un Bénévole</h3>
            <form action="{{ route('plannings.addBenevole', $planning->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="benevole_id" class="block text-sm font-medium text-gray-700">Sélectionnez un Bénévole</label>
                    <select name="benevole_id" id="benevole_id" class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach($benevoles as $benevole)
                            <option value="{{ $benevole->id }}">{{ $benevole->user->firstname }}{{ $benevole->user->lastname }} - {{-- $benevole->availability --}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-md shadow-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
