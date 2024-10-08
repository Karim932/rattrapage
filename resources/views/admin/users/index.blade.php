@extends('layouts.templateAdmin')
@section('title', 'Administrateur | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Gestion des Utilisateurs</h1>

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

    <div class="mb-6 flex justify-end">
        <a href="{{ route('users.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-blue-800 transition duration-300">
            <i class="fas fa-user-plus mr-2"></i> Ajouter un Utilisateur
        </a>
    </div>

    <div id="success-message" class="hidden bg-green-700 text-white p-4 mb-4 rounded-lg shadow-md">
        <span id="success-text"></span>
    </div>

    <div class="mb-5">
        <input type="text" id="search-users" placeholder="Rechercher des utilisateurs..." class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:shadow-outline">
    </div>

    <div class="mb-5">
        <button data-role="benevole" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Bénévoles
        </button>
        <button data-role="commercant" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Commerçants
        </button>
        <button data-role="user" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Utilisateurs
        </button>
        <button id="cancel-role" class="bg-gray-500 text-white px-4 py-2 rounded">
            Annuler rôle
        </button>
        <button data-role="banned" class="bg-red-500 text-white px-4 py-2 rounded">
            Banissement
        </button>
    </div>

    <div id="users-table">
        @include('admin.users.partialsTable.user-and-role', ['users' => $users])
    </div>
</div>
@endsection
