@extends('layouts.templateAdmin')
@section('title', 'Modifier Ticket | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Modifier Ticket</h1>

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

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('admin.contact.update', $ticket->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="message" id="message" rows="5" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('message', $ticket->message) }}</textarea>
                @error('message')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" id="statut" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="ouvert" {{ $ticket->statut == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                    <option value="en cours" {{ $ticket->statut == 'en cours' ? 'selected' : '' }}>En cours</option>
                    <option value="fermé" {{ $ticket->statut == 'fermé' ? 'selected' : '' }}>Fermé</option>
                </select>
                @error('statut')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.contact.index') }}" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-800 transition duration-200">Retour</a>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-800 transition duration-200">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
