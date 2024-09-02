<x-app-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-indigo-600">Détails de la Collecte</h1>

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

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Informations sur la Collecte</h2>
                <hr class="my-2">
                <p><strong>Commerçant :</strong> {{ $collecte->commercant->company_name }}</p>
                <p><strong>Date de Collecte :</strong> {{ $collecte->date_collecte }}</p>
                <p><strong>Instructions :</strong> {{ $collecte->instructions }}</p>
                <p><strong>Statut :</strong> 
                    <span class="px-2 py-1 rounded-lg text-white {{ $collecte->status === 'Attribué' ? 'bg-yellow-500' : ($collecte->status === 'En Cours' ? 'bg-blue-500' : ($collecte->status === 'En Attente de Stockage' ? 'bg-red-500' : 'bg-green-500')) }}">
                        {{ ucfirst($collecte->status) }}
                    </span>
                </p>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-700">Actions</h2>
                <hr class="my-2">

                @if($collecte->status === 'Attribué')
                    <form action="{{ route('benevole.collectes.updateStatus', $collecte->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="status" value="En Cours" class="mt-4 bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">En route</button>
                    </form>
                @elseif($collecte->status === 'En Cours')
                    <form action="{{ route('benevole.collectes.updateStatus', $collecte->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="status" value="En Attente de Stockage" class="mt-4 bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-green-700 transition duration-300">Confirmer la collecte</button>
                    </form>
                @elseif($collecte->status === 'En Attente de Stockage')
                    <a href="{{ route('benevole.collectes.stock', $collecte->id) }}" class="mt-4 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Entrée en stock</a>
                @endif
            </div>

            
            <div class="mt-8">
                <a href="{{ route('benevole.collectes.index') }}" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">Retour</a>
            </div>
        </div>
    </div>
</x-app-layout>
