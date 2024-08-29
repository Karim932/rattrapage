<x-app-layout>
    <div id="main-content" class="flex-1 p-10 transition-all bg-gray-100">
        <div class="container mx-auto py-24 max-w-7xl sm:px-6 lg:px-8">
            <h2 class="font-bold text-xl mb-4">Mon Historique</h2>
            
            <!-- Notification de succès -->
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

            @if($plannings->isEmpty())
                <p class="text-gray-700">Vous n'êtes inscrit à aucun événement pour le moment.</p>
            @else
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Service</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plannings as $planning)
                            <tr>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $planning->service->name ?? 'Service non spécifié' }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($planning->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $planning->start_time }} - {{ $planning->end_time }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $planning->city }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    @php
                                        try {
                                            // Ici, on combine la date avec uniquement l'heure de début sans le "00:00:00" supplémentaire
                                            $dateTimeString = \Carbon\Carbon::parse($planning->date)->format('Y-m-d') . ' ' . substr($planning->start_time, 0, 5);
                                            // echo "DateTime String: " . $dateTimeString . "<br>";

                                            // Créez un objet Carbon avec la date et l'heure combinées
                                            $eventTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $dateTimeString);

                                            // Calculer le nombre d'heures restantes
                                            $hoursLeft = \Carbon\Carbon::now()->diffInHours($eventTime, false);
                                            // echo "Hours left: " . $hoursLeft . "<br>";
                                        } catch (\Exception $e) {
                                            // Si une erreur se produit, on peut gérer l'exception ici
                                            // echo "Exception caught: " . $e->getMessage() . "<br>";
                                            $hoursLeft = null;
                                        }
                                    @endphp

                                    @if($hoursLeft > 48)
                                        <form action="{{ route('plannings.cancel', $planning->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Annuler
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">Engagé</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
