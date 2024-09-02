<x-app-layout>
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                Demande de Collecte
            </h1>

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

            <form method="POST" action="{{ route('commercant.demande_collecte.store') }}"
                  class="bg-white p-8 shadow-lg rounded-lg space-y-6">
                @csrf

                <div class="mb-6">
                    <label for="date_collecte" class="block text-sm font-medium text-gray-700">Date de Collecte</label>
                    <input type="date" id="date_collecte" name="date_collecte" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                </div>

                <div class="mb-6">
                    <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions Spécifiques</label>
                    <textarea id="instructions" name="instructions"
                              placeholder="Ajoutez des instructions spécifiques pour la collecte..."
                              class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('instructions') }}</textarea>
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('commercant.dashboard') }}" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-300 ease-in-out">
                        Retour
                    </a>
                    <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                        Soumettre
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
