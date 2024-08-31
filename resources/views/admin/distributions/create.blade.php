@extends('layouts.templateAdmin')
@section('title', 'Créer une Distribution | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Créer une Distribution</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.distributions.storeStep1') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="destinataire" class="block text-sm font-medium text-gray-700">Destinataire</label>
            <input type="text" id="destinataire" name="destinataire" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
            <input type="text" id="adresse" name="adresse" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
            <input type="text" id="telephone" name="telephone" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label for="date_distribution" class="block text-sm font-medium text-gray-700">Date de Distribution</label>
            <input type="date" id="date_distribution" name="date_distribution" required
                   class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Suivant</button>
        </div>
    </form>
</div>
@endsection
