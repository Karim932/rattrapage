@extends('layouts.templateAdmin')
@section('title', 'Modifier la Collecte | NoMoreWaste')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier la Collecte</h1>

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

    <form action="{{ route('admin.collectes.update', $collecte->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="commercant_id" class="block text-sm font-medium text-gray-700">Commerçant</label>
            <select id="commercant_id" name="commercant_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @foreach($commercants as $commercant)
                    <option value="{{ $commercant->id }}" {{ $commercant->id == $collecte->commercant_id ? 'selected' : '' }}>{{ $commercant->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label for="date_collecte" class="block text-sm font-medium text-gray-700">Date de Collecte</label>
            <input type="date" id="date_collecte" name="date_collecte" value="{{ $collecte->date_collecte }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
            <textarea id="instructions" name="instructions" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" rows="4">{{ $collecte->instructions }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                Mettre à jour la Collecte
            </button>
        </div>
    </form>
</div>

@endsection
