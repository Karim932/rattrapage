@extends('layouts.templateAdmin')

@section('title', 'Détails du Planning')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="container mt-5">
        <h2 class="font-bold text-xl mb-4">Détails du Planning</h2>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Service</label>
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->service->name ?? 'Service non spécifié' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->service->category ?? 'Catégorie non spécifiée' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->service->description ?? 'Description non spécifiée' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->date->format('d/m/Y') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Heure de début</label>
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->start_time }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Heure de fin</label>
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->end_time }}</p>
            </div>

            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Adresse</label>
                @if ($planning->address)
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->address }}</p>
                @else
                <p class="text-base font-normal text-gray-700 bg-white">Pas d'adresse donnée</p>
                @endif
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ville</label>
                @if ($planning->city)
                <p class="text-base font-normal text-gray-700 bg-white">{{ $planning->city }}</p>
                @else
                <p class="text-base font-normal text-gray-700 bg-white">Pas de ville donnée</p>
                @endif
            </div>

            

            <div class="mt-8">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('plannings.inscrits', $planning->id) }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">
                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1119.071 3.98M12 7v5l4 2"></path>
                         </svg>
                         Voir les inscrits
                     </a>                     
                     
                    <a href="{{ route('plannings.benevole', $planning->id) }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-md shadow-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.38 18.06a9 9 0 0011.24 0M7.46 9a5 5 0 119.08 0M8 14l-1.5 3M16 14l1.5 3" />
                        </svg>
                        Gérer les bénévoles
                    </a>
                                                        
                    <button type="button" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out" 
                            onclick="window.history.back();">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </button>
            
                    <!-- Edit Button -->
                    <a href="{{ route('plannings.edit', $planning->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 rounded-md shadow-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Modifier
                    </a>
            
                    <!-- Delete Button -->
                    <form action="{{ route('plannings.destroy', $planning->id) }}" method="POST" class="delete-user-form inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 rounded-md shadow-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>            
        </div>
    </div>
</div>
@endsection
