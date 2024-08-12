<x-app-layout>
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Devenir Commerçant</h1>

            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="#" class="bg-white p-8 shadow-lg rounded-lg space-y-6">
                @csrf

                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                    <input type="text" name="company_name" id="company_name" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           value="{{ old('company_name') }}">
                </div>

                <div>
                    <label for="siret" class="block text-sm font-medium text-gray-700">SIRET</label>
                    <input type="text" name="siret" id="siret" required
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           value="{{ old('siret') }}">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                    <textarea name="address" id="address" required
                              class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                        <input type="text" name="city" id="city" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('city') }}">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Code Postal</label>
                        <input type="text" name="postal_code" id="postal_code" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('postal_code') }}">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
                        <input type="text" name="country" id="country" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('country') }}">
                    </div>
                </div>

                <div>
                    <label for="product_type" class="block text-sm font-medium text-gray-700">Type de produits offerts</label>
                    <input type="text" name="product_type" id="product_type"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           value="{{ old('product_type') }}">
                </div>

                <div>
                    <label for="opening_hours" class="block text-sm font-medium text-gray-700">Horaire d'ouverture</label>
                    <input type="text" name="opening_hours" id="opening_hours"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           value="{{ old('opening_hours') }}">
                </div>

                <div>
                    <label for="participation_frequency" class="block text-sm font-medium text-gray-700">Fréquence de participation</label>
                    <input type="text" name="participation_frequency" id="participation_frequency"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           value="{{ old('participation_frequency') }}">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes"
                              class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
