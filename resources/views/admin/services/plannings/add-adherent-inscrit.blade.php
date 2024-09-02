@extends('layouts.templateAdmin')

@section('title', 'Ajouter un Adhérent')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="container mt-5">
        <h2 class="font-bold text-xl mb-4">Ajouter un Adhérent à {{ $planning->service->name }}</h2>

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

        <form action="{{ route('plannings.storeAdherent', $planning->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Sélectionner un Adhérent</label>
                <select class="form-control block w-full px-3 py-2 border border-solid border-gray-300 rounded" id="user_id" name="user_id">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</div>
@endsection
