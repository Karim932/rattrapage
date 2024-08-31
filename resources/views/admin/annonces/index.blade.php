@extends('layouts.templateAdmin')
@section('title', 'Administrateur | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Liste des Annonces</h1>
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div id="success-message" class="bg-green-500 text-white p-4 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex justify-end">
        <a href="{{ route('annonces.create') }}" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-800 transition duration-300">
            <i class="fas fa-clipboard-list"></i>&nbsp; Ajouter une nouvelle annonce
        </a>
    </div>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <tr>
                                <th scope="col" class="w-1/4 min-w-[200px] py-3 px-4 text-left font-medium">Titre</th>
                                <th scope="col" class="w-1/4 min-w-[300px] py-3 px-4 text-left font-medium">Description</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Lieu</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Prix</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Catégorie</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Utilisateur</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Service</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($annonces as $annonce)
                            <tr>
                                <td class="px-2 py-2 border-b border-gray-200 bg-white text-sm whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{ $annonce->title }}
                                </td>                                
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $annonce->description }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $annonce->location }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ number_format($annonce->price, 2, ',', '') }}€
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $annonce->category }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $annonce->user ? $annonce->user->firstname . ' ' . $annonce->user->lastname : 'Non assigné' }}
                                </td>
                                <td class="px-2 py-2 border-b border-gray-200 bg-white text-sm">
                                    {{ $annonce->service ? $annonce->service->name : 'Non assigné' }}
                                </td>                                
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('annonces.show', $annonce->id) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                        <a href="{{ route('annonces.edit', $annonce->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                        <form action="{{ route('annonces.destroy', $annonce->id) }}" method="POST" class="delete-user-form inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </div>
                                </td>                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
