<x-app-layout>
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Mes Collectes</h1>

            <div class="bg-white p-8 shadow-lg rounded-lg space-y-6">
                @if ($collectes->isEmpty())
                    <p class="text-gray-600">Vous n'avez aucune collecte en cours.</p>
                @else
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 text-left text-gray-600 font-semibold">Commerçant</th>
                                <th class="py-2 text-left text-gray-600 font-semibold">Date de Collecte</th>
                                <th class="py-2 text-left text-gray-600 font-semibold">Statut</th>
                                <th class="py-2 text-left text-gray-600 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collectes as $collecte)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-4 px-6 border-b border-gray-200">{{ $collecte->commercant->company_name }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200">{{ $collecte->date_collecte }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200">
                                        <span class="inline-block px-2 py-1 font-semibold text-white rounded-full {{ $collecte->status === 'Attribué' ? 'bg-blue-500' : ($collecte->status === 'En Cours' ? 'bg-yellow-500' : ($collecte->status === 'En Attente de Stockage' ? 'bg-green-500' : 'bg-gray-500')) }}">
                                            {{ ucfirst($collecte->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 border-b border-gray-200">
                                        <a href="{{ route('benevole.collectes.show', $collecte->id) }}" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Voir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
