@extends('layouts.templateAdmin')
@section('title', 'Détails de l\'Annonce')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-5">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Détails de l'Annonce</h1>

            <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="w-full">
                    <h3 class="text-2xl font-bold text-indigo-600 mb-6">Informations Générales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <p class="text-lg"><span class="font-medium text-gray-900">Titre :</span> {{ $annonce->title }}</p>
                        <p class="text-lg"><span class="font-medium text-gray-900">ID :</span> {{ $annonce->id }}</p>
                        <p class="text-lg"><span class="font-medium text-gray-900">Prix :</span> {{ number_format($annonce->price, 2) }} €</p>
                        <p class="text-lg"><span class="font-medium text-gray-900">Catégorie :</span> {{ $annonce->category }}</p>
                        <p class="text-lg"><span class="font-medium text-gray-900">Lieu :</span> {{ $annonce->location }}</p>
                        <p class="text-lg"><span class="font-medium text-gray-900">Statut :</span>
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-sm font-medium {{ $annonce->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($annonce->status) }}
                            </span>
                        </p>
                        <p class="text-lg"><span class="font-medium text-gray-900">Utilisateur :</span> {{ $annonce->user ? $annonce->user->firstname . ' ' . $annonce->user->lastname : 'Non assigné' }}</p>
                        <p class="text-lg"><span class="font-medium text-gray-900">Service :</span> {{ $annonce->service ? $annonce->service->name : 'Non assigné' }}</p>
                        <p class="text-lg"><span class="font-medium text-gray-900">Date de création :</span> {{ $annonce->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-lg mt-8">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Description de l'Annonce :</h3>
                <p>{{ $annonce->description }}</p>
            </div>

            @if ($annonce->image)
                <div class="bg-white p-6 rounded-lg shadow-lg mt-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Image :</h3>
                    <img src="{{ asset('storage/'.$annonce->image) }}" alt="Image de l'annonce" class="rounded-md">
                </div>
            @endif

            <div class="flex justify-between mt-8">
                <a href="{{ route('annonces.index') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('annonces.edit', $annonce->id) }}" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 18H9v-3L16.732 7.268z"></path>
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
