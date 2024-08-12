<x-app-layout>
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Devenir Bénévole</h1>
            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('store.benevole') }}" class="bg-white p-8 shadow-lg rounded-lg space-y-6">
                @csrf

                <div>
                    <label for="motivation" class="block text-sm font-medium text-gray-700">Motivation</label>
                    <textarea name="motivation" id="motivation" required
                              placeholder="Expliquez ce qui vous motive à devenir bénévole..."
                              class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('motivation') }}</textarea>
                </div>

                <div>
                    <label for="experience" class="block text-sm font-medium text-gray-700">Expérience</label>
                    <textarea name="experience" id="experience" required
                              placeholder="Décrivez vos expériences antérieures en lien avec le bénévolat..."
                              class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('experience') }}</textarea>
                </div>

                <div>
                    <label for="old_benevole" class="block text-sm font-medium text-gray-700">Ancien bénévole</label>
                    <input type="checkbox" name="old_benevole" id="old_benevole" class="mt-2 align-middle" {{ old('old_benevole') ? 'checked' : '' }}>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="availability_begin" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="availability_begin" id="availability_begin" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('availability_begin') }}">
                    </div>
                    <div>
                        <label for="availability_end" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="availability_end" id="availability_end" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                               value="{{ old('availability_end') }}">
                    </div>
                </div>

                <div>
                    <label for="hour_month" class="block text-sm font-medium text-gray-700">Heures par mois</label>
                    <input type="number" name="hour_month" id="hour_month" required
                           placeholder="Nombre d'heures que vous pouvez consacrer par mois..."
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                           value="{{ old('hour_month') }}">
                </div>

                <div>
                    <label for="permis" class="block text-sm font-medium text-gray-700">Permis de conduire</label>
                    <input type="checkbox" name="permis" id="permis" class="mt-2 align-middle" {{ old('permis') ? 'true' : 'false' }}>
                </div>

                <div>
                    <label for="additional_notes" class="block text-sm font-medium text-gray-700">Notes supplémentaires</label>
                    <textarea name="additional_notes" id="additional_notes"
                              placeholder="Toute information supplémentaire que vous souhaitez ajouter..."
                              class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">{{ old('additional_notes') }}</textarea>
                </div>

                <div>
                    <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
