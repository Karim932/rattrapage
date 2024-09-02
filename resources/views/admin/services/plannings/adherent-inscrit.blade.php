@extends('layouts.templateAdmin')

@section('title', 'Adhérents Inscrits')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all bg-gray-100">
    <div class="container mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Adhérents Inscrits pour {{ $planning->service->name ?? 'Service non spécifié' }}</h2>
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
            
            @if($planning->users->isEmpty())
                <p class="text-gray-600">Aucun adhérent inscrit pour ce planning.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($planning->users as $user)
                        <li class="py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <p class="text-gray-800">{{ $user->firstname }} {{ $user->lastname }}</p>
                                    <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('plannings.inscriptions.destroy', [$planning->id, $user->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Retirer</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-6">
                
                <a href="{{ route('plannings.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('plannings.addAdherent', $planning->id) }}" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-green-600 bg-white hover:bg-green-50">
                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                     </svg>
                     Ajouter un adhérent
                </a>
                 
            </div>
        </div>
    </div>
</div>
@endsection
