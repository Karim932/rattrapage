<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Détails de la Collecte</h1>
        <div class="bg-white shadow-md rounded my-6 p-6">
            <p><strong>Commerçant :</strong> {{ $collecte->commercant->company_name }}</p>
            <p><strong>Date de Collecte :</strong> {{ $collecte->date_collecte }}</p>
            <p><strong>Instructions :</strong> {{ $collecte->instructions }}</p>
            <p><strong>Statut :</strong> {{ ucfirst($collecte->status) }}</p>

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
    </div>
</x-app-layout>
