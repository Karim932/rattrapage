@extends('layouts.templateAdmin')
@section('title', 'Voir Ticket | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Détails du Ticket</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <p><strong>Nom :</strong> {{ $ticket->user->firstname }} {{ $ticket->user->lastname }}</p>
        <p><strong>Numéro de Téléphone :</strong> {{ $ticket->user->phone_number }}</p>
        <p><strong>Statut :</strong> {{ $ticket->statut }}</p>
        <p><strong>Message :</strong></p>
        <p>{{ $ticket->message }}</p>

        <div class="mt-4">
            <a href="{{ route('admin.contact.index') }}" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-800 transition duration-200">Retour</a>
        </div>
    </div>
</div>
@endsection
