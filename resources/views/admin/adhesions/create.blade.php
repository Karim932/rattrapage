{{-- Vue pour ajouter une nouvelle candidature --}}
@extends('layouts.templateAdmin')

@section('title', 'Ajouter une candidature')

@section('content')
<div class="container mx-auto px-4 mt-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Ajouter une candidature</h1>
    <form method="POST" action="{{ route('applications.store') }}">
        @csrf
        {{-- Formulaire ici --}}
        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Soumettre
        </button>
    </form>
</div>
@endsection
