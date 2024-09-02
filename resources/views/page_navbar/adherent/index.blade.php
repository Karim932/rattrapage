<x-app-layout>
    <div id="main-content" class="flex-1 p-10 transition-all bg-gray-100">
        <div class="container mx-auto py-24 max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Inscription aux créneaux</h2>
    
            <form method="GET" action="{{ route('adherent.filter') }}" class="mb-6">
                <input type="hidden" name="service_id" value="{{ request('service_id') }}">

                <div class="flex flex-wrap gap-4 mb-4">
                    <input type="date" name="date" class="form-input border p-2 rounded-lg" placeholder="Date" value="{{ request('date') }}">
                    <input type="time" name="start_time" class="form-input border p-2 rounded-lg" placeholder="Heure de début" value="{{ request('start_time') }}">
                    <input type="time" name="end_time" class="form-input border p-2 rounded-lg" placeholder="Heure de fin" value="{{ request('end_time') }}">
                    <input type="text" name="city" class="form-input border p-2 rounded-lg" placeholder="Ville" value="{{ request('city') }}">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    Filtrer
                </button>
            </form>

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
    
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($plannings as $planning)
                    <div class="bg-white p-6 rounded-lg shadow-md flex flex-col h-full justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">{{ $planning->service->name }}</h3>
                            <p class="text-gray-600 mb-1">Date: {{ $planning->date->format('d/m/Y') }}</p>
                            <p class="text-gray-600 mb-1">Heure: {{ $planning->start_time }} - {{ $planning->end_time }}</p>
                            <p class="text-gray-600 mb-1">Ville: {{ $planning->city }}</p>
                            <p class="text-gray-600 mb-4">Adresse: {{ $planning->address }}</p>
                            @if($planning->max_inscrit)
                            <p class="text-red-600 mb-4">Limite inscrit: {{ $planning->inscriptions_count }}/{{ $planning->max_inscrit }}</p>
                            @endif
                        </div>

                        @if(auth()->user()->isRegisteredFor($planning->id))
                            <button class="w-full bg-green-500 text-white py-2 rounded-lg">
                                Inscrit
                            </button>
                        @else
                            <form action="{{ route('inscriptions.store') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="planning_id" value="{{ $planning->id }}">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                                    S'inscrire
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
