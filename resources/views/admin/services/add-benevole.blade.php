@extends('layouts.templateAdmin')

@section('title', 'Administrateur | Assigner un Bénévole')

@section('content')

<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-2xl font-semibold text-gray-800">Assigner un Bénévole à un Service</h1>
        <div class="mt-8 max-w-2xl">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <form action="{{ route('services.save') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    <div class="mb-6">
                        <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
                        <select id="service_id" name="service_id" class="mt-1 block w-full py-3 px-4 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="skills-container" class="mb-6 hidden">
                        <label class="block text-sm font-medium text-gray-700">Compétences requises</label>
                        <div id="skills-list" class="mt-1">
                            <!-- Les compétences seront ajoutées ici dynamiquement -->
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Bénévole</label>
                        <select id="user_id" name="user_id" class="mt-1 block w-full py-3 px-4 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach ($benevoles as $benevole)
                                <option value="{{ $benevole->id }}">{{ $benevole->id }} - {{ $benevole->firstname }}{{ $benevole->lastname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-between items-center">
                        <button type="button" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out" onclick="location.href='{{ route('services.index') }}';">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Retour
                        </button>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Assigner le bénévole
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('service_id').addEventListener('change', function() {
    var serviceId = this.value;
    fetch(`/api/services/${serviceId}/skills`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('skills-list');
            container.innerHTML = '';
            data.skills.forEach(skill => {
                const div = document.createElement('div');
                div.textContent = skill.name;
                container.appendChild(div);
            });
            document.getElementById('skills-container').classList.remove('hidden');
        })
        .catch(error => console.error('Erreur lors de la récupération des compétences:', error));
});
</script>

@endsection
