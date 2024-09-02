@extends('layouts.templateAdmin')

@section('title', 'Créer une Proposition de Service')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-gray-700 mb-6">Créer une Proposition de Service</h1>

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

                <form action="{{ route('propose.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Bénévole</label>
                        <select name="user_id" id="user_id" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="">Sélectionnez un bénévole</option>
                            @foreach($benevoles as $benevole)
                                <option value="{{ $benevole->user->id }}">{{ $benevole->user->firstname }} {{ $benevole->user->lastname }}</option>
                            @endforeach
                        </select>
                    </div>                    

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du Service</label>
                        <input type="text" name="name" id="name" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Créer la Proposition
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
