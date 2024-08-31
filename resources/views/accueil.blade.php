@extends('layouts.template')
@section('title', 'Accueil | NoMoreWaste')

@section('content')
<header class="relative bg-cover bg-center" style="background-image: url('picture/header.png'); height: 80vh;">
    @if(session('error'))
                    <div role="alert" class="bg-red-500 text-white p-4 rounded-md">
                        {{ session('error') }}
                    </div>
    @endif

    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto px-6 py-24 relative z-10">
        <h1 class="text-5xl font-bold text-center text-white">NO MORE WASTE</h1>
        <p class="mt-4 text-2xl text-center text-gray-200">{{ __('message.accueil.presentation') }}</p>
        <div class="mt-8 text-center">
            <a href="{{ route('services') }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">{{ __('message.accueil.join') }}</a>
        </div>
    </div>
</header>

<main class="container mx-auto px-6 py-12">

    <section class="my-12">
        <h2 class="text-3xl font-bold text-gray-800 text-center">Notre Mission</h2>
        <p class="mt-4 text-gray-600 text-center">L’idée de base de l’association est de récolter tous les jours les invendus commerciaux, ou les produits atteignant la date limite de consommation chez les particuliers...</p>
    </section>

    <section class="my-12">
        <h2 class="text-3xl font-bold text-gray-800 text-center">Nos Agences</h2>
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
        <h2 class="text-3xl font-bold text-gray-800 text-center">Notre Équipe</h2>
        <p class="mt-4 text-gray-600 text-center">NO MORE WASTE emploie aujourd'hui 14 salariés en CDI répartis entre son siège à Paris, et ses agences locales de Nantes, Marseille et Limoges. Plus de 200 bénévoles ont décidé d’aider l’association dans ses actions quotidiennes.</p>
    </section>
</main>
@endsection


