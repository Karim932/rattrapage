<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Enregistrement de la collecte dans le stock</h1>

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
            <h2 class="text-xl font-bold mb-4">Produits Standards</h2>
            <div id="products">
                @if(session('originalProducts') && is_array(session('originalProducts')))
                    @foreach(session('originalProducts') as $index => $product)
                        <div class="product-entry"> 
                            <div class="flex space-x-4 mb-4">
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
                                <select name="products[{{ $index }}][froid]" class="w-1/4 px-3 py-2 border rounded">
                                    <option value="">Emplacement Froid</option>
                                    <option value="FRIGO_A" {{ $product['froid'] == 'FRIGO_A' ? 'selected' : '' }}>FRIGO A</option>
                                    <option value="FRIGO_B" {{ $product['froid'] == 'FRIGO_B' ? 'selected' : '' }}>FRIGO B</option>
                                    <option value="FRIGO_C" {{ $product['froid'] == 'FRIGO_C' ? 'selected' : '' }}>FRIGO C</option>
                                    <option value="CONGELATEUR_A" {{ $product['froid'] == 'CONGELATEUR_A' ? 'selected' : '' }}>CONGÉLATEUR A</option>
                                    <option value="CONGELATEUR_B" {{ $product['froid'] == 'CONGELATEUR_B' ? 'selected' : '' }}>CONGÉLATEUR B</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <button type="button" id="add-product" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">+ Ajouter une ligne</button>
            <h2 class="text-xl font-bold mb-4 mt-8">Produits Frais</h2>
            <div id="frais-products">
                <button type="button" id="add-frais-product" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">+ Ajouter un produit frais</button>
            </div>

            <button type="submit" class="mt-4 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">Confirmer</button>
            <a href="{{ route('benevole.collectes.show', $collecte->id) }}" class="mt-4 bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">Retour</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('add-product').addEventListener('click', function() {
                let productEntries = document.getElementById('products');
                let index = productEntries.children.length;

                let newEntry = `
                    <div class="product-entry">
                        <div class="flex space-x-4 mb-4">
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
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                            <select name="products[${index}][location_etagere]" class="w-1/8 px-3 py-2 border rounded">
                                <option value="">Étagère</option>
                                @foreach(range('A', 'Z') as $etagere)
                                    <option value="{{ $etagere }}">{{ $etagere }}</option>
                                @endforeach
                            </select>
                            <select name="products[${index}][location_position]" class="w-1/8 px-3 py-2 border rounded">
                                <option value="">Position</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                    </div>`;
                
                productEntries.insertAdjacentHTML('beforeend', newEntry);
            });

            document.getElementById('add-frais-product').addEventListener('click', function() {
                let fraisEntries = document.getElementById('frais-products');
                let index = fraisEntries.children.length;

                let newFraisEntry = `
                    <div class="product-entry">
                        <div class="flex space-x-4 mb-4">
                            <select name="" class="w-1/4 px-3 py-2 border rounded" required>
                                <option value="">Sélectionner un produit frais</option>
                                <option value="1">Salade (batavia)</option>
                                <option value="2">Tomates</option>
                                <option value="3">Pommes de terre</option>
                                <option value="4">Carottes</option>
                                <option value="5">Oignons</option>
                                <option value="6">Laitue</option>
                                <option value="7">Choux</option>
                                <option value="8">Pommes</option>
                                <option value="9">Bananes</option>
                                <option value="10">Oranges</option>
                                <option value="11">Viande de bœuf (1kg)</option>
                                <option value="12">Viande de poulet (1kg)</option>
                                <option value="13">Poisson (1kg)</option>
                                <option value="14">Pain</option>
                                <option value="15">Fromage</option>
                                <option value="16">Lait (sans code-barres)</option>
                                <option value="17">Yaourt (sans code-barres)</option>
                                <option value="18">Œufs</option>
                                <option value="19">Riz (1kg)</option>
                                <option value="20">Pâtes (500g)</option>
                            </select>                            <input type="number" name="products_frais[${index}][poids]" placeholder="Poids (en kg)" class="w-1/4 px-3 py-2 border rounded" required>
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
                        </div>
                    </div>`;
                
                fraisEntries.insertAdjacentHTML('beforeend', newFraisEntry);
            });
        });
    </script>
</x-app-layout>
