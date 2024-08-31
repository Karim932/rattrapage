@extends('layouts.templateAdmin')

@section('title', 'Modifier la Proposition de Service')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-gray-700 mb-6">Modifier la Proposition de Service</h1>

                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('update.propose', $proposal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du Service</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $proposal->name) }}" required class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('description', $proposal->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" id="status" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="en_attente" {{ $proposal->status == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                            <option value="accepté" {{ $proposal->status == 'accepté' ? 'selected' : '' }}>Accepté</option>
                            <option value="refusé" {{ $proposal->status == 'refusé' ? 'selected' : '' }}>Refusé</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition duration-300">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
