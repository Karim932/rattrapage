<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Ajouter des Produits Manquants</h1>

        <form action="{{ route('benevole.collectes.storeNewProducts', $id) }}" method="POST">
            @csrf

            <div id="missing-products">
                @foreach($missingProducts as $index => $product)
                    <div class="product-entry">
                        <div class="flex space-x-4 mb-4">
                            <input type="text" name="products[{{ $index }}][barcode]" value="{{ $product['barcode'] }}" placeholder="Code-barres" class="w-1/4 px-3 py-2 border rounded" readonly>
                            <input type="text" name="products[{{ $index }}][name]" placeholder="Nom du produit" class="w-1/4 px-3 py-2 border rounded" required>
                            <input type="text" name="products[{{ $index }}][marque]" placeholder="Marque" class="w-1/4 px-3 py-2 border rounded">
                            <input type="text" name="products[{{ $index }}][categorie]" placeholder="Catégorie" class="w-1/4 px-3 py-2 border rounded" required>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="mt-4 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Enregistrer les Produits</button>
        </form>
    </div>
</x-app-layout>
