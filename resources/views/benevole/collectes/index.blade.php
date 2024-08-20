@extends('layouts.benevole')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Mes Collectes</h1>
    <div class="bg-white shadow-md rounded my-6">
        <table class="text-left w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-6 bg-gray-200 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Commerçant</th>
                    <th class="py-4 px-6 bg-gray-200 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Date de Collecte</th>
                    <th class="py-4 px-6 bg-gray-200 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Statut</th>
                    <th class="py-4 px-6 bg-gray-200 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($collectes as $collecte)
                    <tr class="hover:bg-gray-100">
                        <td class="py-4 px-6 border-b border-gray-200">{{ $collecte->commercant->company_name }}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{ $collecte->date_collecte }}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{ ucfirst($collecte->status) }}</td>
                        <td class="py-4 px-6 border-b border-gray-200">
                            <a href="{{ route('benevole.collectes.show', $collecte->id) }}" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
