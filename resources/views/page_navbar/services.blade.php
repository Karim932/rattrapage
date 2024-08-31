<x-app-layout>
    <div class="py-24 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <div class="mb-4">
                <!-- Affichage du message d'erreur -->
                @if(session('error'))
                    <div role="alert" class="bg-red-500 text-white p-4 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif
                <header class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900">Inscription aux Services</h1>
                    <p class="text-md text-gray-600 mt-2">Choisissez parmi une gamme variée de services adaptés à vos besoins.</p>
                </header>
                <main class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Boucle pour afficher les services -->
                        @foreach ($services as $service)
                        @if($service->status === 'disponible')
                            <div class="flex flex-col bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300">
                                <div class="p-4 flex-grow">
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h2>
                                    <div class="text-gray-600 overflow-auto" style="scrollbar-width: thin;">{{ $service->description }}</div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 text-right">
                                    @if($service->type === 'reservations')
                                    <a href="{{ route('adherent.index', ['service_id' => $service->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                        Voir les créneaux disponibles
                                    </a>
                                    @elseif($service->type === 'postes')
                                    <a href="{{ route('annonces.adherent.index', ['service_id' => $service->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                        Accéder aux Annonces
                                    </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @endforeach
                    </div>
                </main>                    
            </div>
        </div>
    </div>
</x-app-layout>

