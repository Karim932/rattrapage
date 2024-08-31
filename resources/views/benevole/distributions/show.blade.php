<x-app-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-indigo-600">Détails de la Distribution</h1>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Informations sur la Distribution</h2>
                <hr class="my-2">
                <p><strong>Destinataire :</strong> {{ $distribution->destinataire }}</p>
                <p><strong>Date de Distribution :</strong> {{ $distribution->date_souhaitee }}</p>
                <p><strong>Adresse :</strong> {{ $distribution->adresse }}</p>
                <p><strong>Téléphone :</strong> {{ $distribution->telephone }}</p>
                <p><strong>Statut :</strong> 
                    <span class="px-2 py-1 rounded-lg text-white {{ $distribution->status === 'Planifié' ? 'bg-blue-500' : ($distribution->status === 'Attribué' ? 'bg-yellow-500' : ($distribution->status === 'Terminé' ? 'bg-green-500' : 'bg-gray-500')) }}">
                        {{ ucfirst($distribution->status) }}
                    </span>
                </p>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-700">Aliments à Distribuer</h2>
                <hr class="my-2">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 text-left text-gray-600 font-semibold">Produit</th>
                            <th class="py-2 text-left text-gray-600 font-semibold">Quantité</th>
                            <th class="py-2 text-left text-gray-600 font-semibold">Emplacement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($distribution->stocks as $stock)
                            <tr class="hover:bg-gray-100">
                                <td class="py-4 px-6 border-b border-gray-200">{{ $stock->produit->nom }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $stock->pivot->quantite }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $stock->emplacement }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-700">Actions</h2>
                <hr class="my-2">

                @if($distribution->status === 'Planifié')
                    <form action="{{ route('benevole.distributions.updateStatus', $distribution->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="status" value="En Cours" class="mt-4 bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">En route</button>
                    </form>
                @elseif($distribution->status === 'En Cours')
                    <form action="{{ route('benevole.distributions.confirm', $distribution->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="status" value="Terminé" class="mt-4 bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-green-700 transition duration-300">Confirmer la distribution</button>
                    </form>
                @endif
            </div>

            
            <div class="mt-8">
                <a href="{{ route('benevole.collectes.index') }}" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">Retour</a>
            </div>
        </div>
    </div>
</x-app-layout>
