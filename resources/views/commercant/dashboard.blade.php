<x-app-layout>
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Mon Tableau de Bord</h1>

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

            <div class="bg-white p-8 shadow-lg rounded-lg space-y-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Mes Collectes</h2>
                    <a href="{{ route('commercant.demande_collecte.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                        Demander une collecte
                    </a>
                </div>

                @if ($collectes->isEmpty())
                    <p class="text-gray-600">Vous n'avez aucune collecte en cours.</p>
                @else
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 text-left">Date de Collecte</th>
                                <th class="py-2 text-left">Statut</th>
                                <th class="py-2 text-left">Bénévole Assigné</th>
                                <th class="py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collectes as $collecte)
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="py-2">{{ $collecte->date_collecte }}</td>
                                    <td class="py-2">{{ $collecte->status }}</td>
                                    <td class="py-2">
                                        @if($collecte->benevole_id && $collecte->status === 'Attribué')
                                            {{ $collecte->benevole->user->firstname }}
                                            {{ $collecte->benevole->user->lastname }}
                                        @else
                                            <span class="text-red-500">Non Assigné</span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        @if(in_array($collecte->status, ['En Attente', 'Attribué']))
                                            <form action="{{ route('commercant.collecte.cancel', $collecte->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette collecte ?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                    Annuler
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">Non annulable</span>
                                        @endif
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
