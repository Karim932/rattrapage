@extends('layouts.templateAdmin')
@section('title', 'Détails de l\'Utilisateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Détails de l'Utilisateur</h1>

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

    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <div class="flex items-center space-x-6 mb-4">
            
            <div class="h-20 w-20 flex items-center justify-center bg-gray-200 rounded-full text-gray-500">
                <span class="text-xl">{{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}</span>
            </div>
      
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $user->firstname }} {{ $user->lastname }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="card-text"><strong>Cotisation Payée :</strong> {{ $user->cotisation ? 'Oui' : 'Non' }}</p>
                  
                
                @if($user->banned)
                    <span class="text-red-600 font-semibold">Banni</span>
                @else
                    <span class="text-green-600 font-semibold">Actif</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-lg font-medium text-gray-700">Informations Personnelles</h3>
                <p class="mt-2 text-gray-600 flex items-center"><i class="fas fa-calendar-alt mr-2"></i> <strong>Date de Naissance: </strong>&nbsp; 
                    {{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}
                </p>
                <p class="mt-2 text-gray-600 flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> <strong>Adresse:</strong> {{ $user->address }}</p>
                <p class="mt-2 text-gray-600 flex items-center"><i class="fas fa-city mr-2"></i> <strong>Ville: </strong>&nbsp; {{ $user->city }}</p>
                <p class="mt-2 text-gray-600 flex items-center"><i class="fas fa-flag mr-2"></i> <strong>Pays: </strong>&nbsp; {{ $user->country }}</p>
                <p class="mt-2 text-gray-600 flex items-center"><i class="fas fa-phone mr-2"></i> <strong>Numéro de Téléphone: </strong>&nbsp; {{ $user->phone_number }}</p>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-700">Rôle et Statut</h3>
                <p class="mt-2 text-gray-600 flex items-center"><i class="fas fa-user-tag mr-2"></i> <strong>Rôle:</strong>&nbsp; {{ $user->role ? $user->role : 'Banni' }}</p>
                <p class="mt-2 text-gray-600 flex items-center"><i class="fas fa-user-check mr-2"></i> <strong>Status:</strong>&nbsp; {{ $user->banned ? 'Banni' : 'Actif' }}</p>
            </div>
            
        </div>

        <div class="mt-6 flex justify-between">

            <a href="{{ route('users.index') }}" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-800 transition duration-200">Retour</a>

            <div>
                <a href="{{ route('users.edit', $user->id) }}" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-800 transition duration-200">Modifier</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-user-form inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-800 transition duration-200 ml-4">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
