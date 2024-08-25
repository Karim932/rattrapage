<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Entrée en Stock</h1>

        <form action="{{ route('benevole.collectes.checkProducts', $collecte->id) }}" method="POST">
            @csrf

            <div id="products">
                @if(session('originalProducts') && is_array(session('originalProducts')))
                    @foreach(session('originalProducts') as $index => $product)
                        <div class="product-entry">
                            <div class="flex space-x-4 mb-4">
                                <input type="text" name="products[{{ $index }}][barcode]" value="{{ $product['barcode'] }}" placeholder="Code-barres" class="w-1/4 px-3 py-2 border rounded" readonly>
                                <input type="text" name="products[{{ $index }}][name]" value="{{ $product['name'] }}" placeholder="Nom du produit (optionnel)" class="w-1/4 px-3 py-2 border rounded">
                                <input type="number" name="products[{{ $index }}][quantity]" value="{{ $product['quantity'] }}" placeholder="Quantité" class="w-1/4 px-3 py-2 border rounded">
                                <input type="date" name="products[{{ $index }}][expiration_date]" value="{{ $product['expiration_date'] }}" placeholder="Date d'expiration" class="w-1/4 px-3 py-2 border rounded">
                                <input type="text" name="products[{{ $index }}][location]" value="{{ $product['location'] }}" placeholder="Emplacement" class="w-1/4 px-3 py-2 border rounded">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <button type="button" id="add-product" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">+ Ajouter une ligne</button>

            <button type="submit" class="mt-4 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Confirmer</button>
        </form>
    </div>

    <script>
        document.getElementById('add-product').addEventListener('click', function() {
            let productEntries = document.getElementById('products');
            let index = productEntries.children.length;

            let newEntry = `
                <div class="product-entry">
                    <div class="flex space-x-4 mb-4">
                        <input type="text" name="products[${index}][barcode]" placeholder="Code-barres" class="w-1/4 px-3 py-2 border rounded">
                        <input type="text" name="products[${index}][name]" placeholder="Nom du produit (optionnel)" class="w-1/4 px-3 py-2 border rounded">
                        <input type="number" name="products[${index}][quantity]" placeholder="Quantité" class="w-1/4 px-3 py-2 border rounded">
                        <input type="date" name="products[${index}][expiration_date]" placeholder="Date d'expiration" class="w-1/4 px-3 py-2 border rounded">
                        <input type="text" name="products[${index}][location]" placeholder="Emplacement" class="w-1/4 px-3 py-2 border rounded">
                    </div>
                </div>`;
            
            productEntries.insertAdjacentHTML('beforeend', newEntry);
        });
    </script>
</x-app-layout>
