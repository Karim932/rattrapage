<x-app-layout>
<header class="relative w-full h-screen bg-cover bg-center" style="background-image: url('picture/header.png');">
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-4 rounded-lg shadow-md">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4 rounded-lg shadow-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-4 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="container mx-auto h-full flex flex-col justify-center items-center relative z-10">
        <h1 class="text-5xl font-bold text-white">NO MORE WASTE</h1>
        <p class="mt-4 text-2xl text-gray-200 text-center">{{ __('message.accueil.presentation') }}</p>
        <div class="mt-8">
            <a href="{{ route('services') }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">{{ __('message.accueil.join') }}</a>
        </div>
    </div>
</header>


<main class="container mx-auto px-6 py-12">
    <section class="my-12">
        <h2 class="text-3xl font-bold text-gray-800 text-center">{{ __('message.accueil.titre1') }}</h2>
        <p class="mt-4 text-gray-600 text-center">{{ __('message.accueil.mission') }}</p>
    </section>

    <section class="my-12">
        <h2 class="text-3xl font-bold text-gray-800 text-center">{{ __('message.accueil.agence') }}</h2>
        <div class="flex flex-wrap justify-center mt-4 text-gray-600">
            <div class="m-4">
                <img src="picture/paris.png" alt="Paris" class="w-32 h-32 object-cover rounded-full mx-auto">
                <p class="mt-2 text-center">Paris</p>
            </div>
            <div class="m-4">
                <img src="picture/nantes.png" alt="Nantes" class="w-32 h-32 object-cover rounded-full mx-auto">
                <p class="mt-2 text-center">Nantes</p>
            </div>
            <div class="m-4">
                <img src="picture/marseille.png" alt="Marseille" class="w-32 h-32 object-cover rounded-full mx-auto">
                <p class="mt-2 text-center">Marseille</p>
            </div>
            <div class="m-4">
                <img src="picture/limoges.png" alt="Limoges" class="w-32 h-32 object-cover rounded-full mx-auto">
                <p class="mt-2 text-center">Limoges</p>
            </div>
        </div>
    </section>

    <section class="my-12">
        <h2 class="text-3xl font-bold text-gray-800 text-center">{{ __('message.accueil.team') }}</h2>
        <p class="mt-4 text-gray-600 text-center">{{ __('message.accueil.teamTexte') }}</p>
    </section>
</main>
</x-app-layout>