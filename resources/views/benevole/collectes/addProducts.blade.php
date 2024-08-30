<x-app-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-indigo-600">Ajouter des Produits Manquants</h1>

            <form action="{{ route('benevole.collectes.storeNewProducts', $id) }}" method="POST">
                @csrf

                <div id="missing-products">
                    @foreach($missingProducts as $index => $product)
                        <div class="product-entry flex space-x-4 mb-4 items-center bg-gray-50 p-4 rounded-md shadow-sm">
                            <input type="text" name="products[{{ $index }}][barcode]" value="{{ $product['barcode'] }}" placeholder="Code-barres" class="w-1/4 px-3 py-2 border rounded bg-gray-200" readonly>
                            <input type="text" name="products[{{ $index }}][name]" placeholder="Nom du produit" class="w-1/4 px-3 py-2 border rounded" required>
                            <input type="text" name="products[{{ $index }}][marque]" placeholder="Marque" class="w-1/4 px-3 py-2 border rounded">
                            <input type="text" name="products[{{ $index }}][categorie]" placeholder="Catégorie" class="w-1/4 px-3 py-2 border rounded" required>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Enregistrer les Produits</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
