@extends('layouts.templateAdmin')

@section('title', 'Administrateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-700">Gestion des Services</h1>
                    <a href="{{ route('services.create') }}" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Ajouter un Service
                    </a>
                </div>

                @if($services->isEmpty())
                    <p class="text-gray-700">Aucun service disponible pour le moment.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom du Service
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catégorie
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date de Création
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('services.show', $service->id) }}" class="text-indigo-600 hover:text-indigo-900">{{ $service->name }}</a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <div class="text-sm text-gray-500">{{ $service->category }}</div>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <div class="text-sm text-gray-500">{{ $service->status }}</div>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <!-- Boutons d'action ici -->
                                            <div class="flex items-center">
                                                <a href="{{ route('services.show', $service->id) }}">Voir</a>
                                                <a href="{{ route('services.edit', $service->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Modifier</a>
                                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="deletes-user-form inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div id="pagination-container" class="mt-4">
                            {{ $services->links() }}
                        </div>
                        <div class="flex justify-between pt-8 items-center mb-6">
                            <div class="flex space-x-4">
                                <a href="{{ route('services.affecte', $service->id) }}" class="flex items-center bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:shadow-md transition duration-300 hover:bg-blue-700">
                                    <i class="fas fa-plus-circle mr-2"></i> Assigner un bénévole
                                </a>
                                <a href="{{ route('skills.index') }}" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-md shadow hover:shadow-md transition duration-300 hover:bg-green-700">
                                    <i class="fas fa-wrench mr-2"></i> Gérer les Compétences
                                </a>
                                <a href="{{ route('plannings.index') }}" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:shadow-md transition duration-300 hover:bg-indigo-700">
                                    <i class="fas fa-calendar mr-2"></i>Voir les plannings
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
