@extends('layouts.templateAdmin')
@section('title', 'Ajouter un Utilisateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Ajouter un Utilisateur</h1>

    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="firstname" class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="firstname" id="firstname" value="{{ old('firstname') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('firstname')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="lastname" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('lastname')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('email')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('password')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date de Naissance</label>
                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('date_of_birth')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('address')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('city')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
                <input type="text" name="country" id="country" value="{{ old('country') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('country')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Numéro de Téléphone</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('phone_number')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                <select name="role" id="role" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Utilisateur</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="benevole" {{ old('role') === 'benevole' ? 'selected' : '' }}>Bénévole</option>
                    <option value="commercant" {{ old('role') === 'commercant' ? 'selected' : '' }}>Commerçant</option>
                    <option value="banned" {{ old('role') === 'banned' ? 'selected' : '' }}>Banni</option>
                </select>
                @error('role')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">Photo de Profil</label>
                <input type="file" name="profile_picture" id="profile_picture" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('profile_picture')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('users.index') }}" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-800 transition duration-200">Retour</a>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-800 transition duration-200">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
