<x-app-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-indigo-600">Enregistrement de la Collecte dans le Stock</h1>

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

            <form action="{{ route('benevole.collectes.checkProducts', $collecte->id) }}" method="POST">
                @csrf
                
                <h2 class="text-2xl font-bold mb-4 text-gray-700">Produits Standards</h2>
                <div id="products">
                    @if(session('originalProducts') && is_array(session('originalProducts')))
                        @foreach(session('originalProducts') as $index => $product)
                            <div class="product-entry flex space-x-4 mb-4 items-center bg-gray-50 p-4 rounded-md shadow-sm"> 
                                <input type="text" name="products[{{ $index }}][barcode]" value="{{ $product['barcode'] }}" placeholder="Code-barres" class="w-1/4 px-3 py-2 border rounded">
                                <input type="number" name="products[{{ $index }}][quantity]" value="{{ $product['quantity'] }}" placeholder="Quantité" class="w-1/4 px-3 py-2 border rounded">
                                <input type="date" name="products[{{ $index }}][expiration_date]" value="{{ $product['expiration_date'] }}" placeholder="Date d'expiration" class="w-1/4 px-3 py-2 border rounded">
                                
                                <select name="products[{{ $index }}][location_section]" class="w-1/8 px-3 py-2 border rounded">
                                    <option value="">Section</option>
                                    <option value="A" {{ $product['location_section'] == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $product['location_section'] == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ $product['location_section'] == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ $product['location_section'] == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                                <select name="products[{{ $index }}][location_allee]" class="w-1/8 px-3 py-2 border rounded">
                                    <option value="">Allée</option>
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ $product['location_allee'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <select name="products[{{ $index }}][location_etagere]" class="w-1/8 px-3 py-2 border rounded">
                                    <option value="">Étagère</option>
                                    @foreach(range('A', 'Z') as $etagere)
                                        <option value="{{ $etagere }}" {{ $product['location_etagere'] == $etagere ? 'selected' : '' }}>{{ $etagere }}</option>
                                    @endforeach
                                </select>
                                <select name="products[{{ $index }}][location_position]" class="w-1/8 px-3 py-2 border rounded">
                                    <option value="">Position</option>
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ $product['location_position'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <button type="button" class="remove-product bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">X</button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" id="add-product" class="mt-4 bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">+ Ajouter une ligne</button>
                
                <h2 class="text-2xl font-bold mb-4 mt-8 text-gray-700">Produits Frais</h2>
                <div id="frais-products">
                    <button type="button" id="add-frais-product" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">+ Ajouter un produit frais</button>
                </div>

                <button type="submit" class="mt-8 bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Confirmer</button>
                <a href="{{ route('benevole.collectes.show', $collecte->id) }}" class="ml-4 mt-8 bg-gray-500 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">Retour</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('add-product').addEventListener('click', function() {
                let productEntries = document.getElementById('products');
                let index = productEntries.children.length;

                let newEntry = `
                    <div class="product-entry flex space-x-4 mb-4 items-center bg-gray-50 p-4 rounded-md shadow-sm">
                        <input type="text" name="products[${index}][barcode]" placeholder="Code-barres" class="w-1/4 px-3 py-2 border rounded">
                        <input type="number" name="products[${index}][quantity]" placeholder="Quantité" class="w-1/4 px-3 py-2 border rounded">
                        <input type="date" name="products[${index}][expiration_date]" placeholder="Date d'expiration" class="w-1/4 px-3 py-2 border rounded">
                        
                        <!-- Sélecteurs pour l'emplacement standard -->
                        <select name="products[${index}][location_section]" class="w-1/8 px-3 py-2 border rounded">
                            <option value="">Section</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                        <select name="products[${index}][location_allee]" class="w-1/8 px-3 py-2 border rounded">
                            <option value="">Allée</option>
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <select name="products[${index}][location_etagere]" class="w-1/8 px-3 py-2 border rounded">
                            <option value="">Étagère</option>
                            @foreach(range('A', 'Z') as $etagere)
                                <option value="{{ $etagere }}">{{ $etagere }}</option>
                            @endforeach
                        </select>
                        <select name="products[${index}][location_position]" class="w-1/8 px-3 py-2 border rounded">
                            <option value="">Position</option>
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <button type="button" class="remove-product bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">X</button>
                    </div>`;
                
                productEntries.insertAdjacentHTML('beforeend', newEntry);
            });

            document.getElementById('add-frais-product').addEventListener('click', function() {
                let fraisEntries = document.getElementById('frais-products');
                let index = fraisEntries.children.length;

                let newFraisEntry = `
                    <div class="product-entry flex space-x-4 mb-4 items-center bg-gray-50 p-4 rounded-md shadow-sm">
                        <select name="products_frais[${index}][barcode]" class="w-1/4 px-3 py-2 border rounded" required>
                            <option value="">Sélectionner un produit frais</option>
                            @foreach(range(1000000001, 1000000020) as $codeBarre)
                                @php
                                    $nomProduit = [
                                        '1000000001' => 'Salade (batavia)',
                                        '1000000002' => 'Tomates',
                                        '1000000003' => 'Pommes de terre',
                                        '1000000004' => 'Carottes',
                                        '1000000005' => 'Oignons',
                                        '1000000006' => 'Laitue',
                                        '1000000007' => 'Choux',
                                        '1000000008' => 'Pommes',
                                        '1000000009' => 'Bananes',
                                        '1000000010' => 'Oranges',
                                        '1000000011' => 'Viande de bœuf (1kg)',
                                        '1000000012' => 'Viande de poulet (1kg)',
                                        '1000000013' => 'Poisson (1kg)',
                                        '1000000014' => 'Pain',
                                        '1000000015' => 'Fromage',
                                        '1000000016' => 'Lait (sans code-barres)',
                                        '1000000017' => 'Yaourt (sans code-barres)',
                                        '1000000018' => 'Œufs',
                                        '1000000019' => 'Riz (1kg)',
                                        '1000000020' => 'Pâtes (500g)',
                                    ][$codeBarre];
                                @endphp
                                <option value="{{ $codeBarre }}">{{ $nomProduit }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="products_frais[${index}][poids]" placeholder="Poids (en kg)" class="w-1/4 px-3 py-2 border rounded" required>
                        <input type="date" name="products_frais[${index}][expiration_date]" placeholder="Date d'expiration" class="w-1/4 px-3 py-2 border rounded" value="{{ \Carbon\Carbon::now()->addDays(5)->format('Y-m-d') }}" required>

                        <!-- Sélecteurs pour l'emplacement froid -->
                        <select name="products_frais[${index}][froid]" class="w-1/4 px-3 py-2 border rounded" required>
                            <option value="">Emplacement Froid</option>
                            <option value="FRIGO_A">FRIGO A</option>
                            <option value="FRIGO_B">FRIGO B</option>
                            <option value="FRIGO_C">FRIGO C</option>
                            <option value="CONGELATEUR_A">CONGÉLATEUR A</option>
                            <option value="CONGELATEUR_B">CONGÉLATEUR B</option>
                        </select>
                        <button type="button" class="remove-frais-product bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">X</button>
                    </div>`;
                
                fraisEntries.insertAdjacentHTML('beforeend', newFraisEntry);
            });

            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-product')) {
                    event.target.closest('.product-entry').remove();
                }
            });

            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-frais-product')) {
                    event.target.closest('.product-entry').remove();
                }
            });
        });
    </script>
</x-app-layout>
