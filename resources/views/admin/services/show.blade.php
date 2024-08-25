@extends('layouts.templateAdmin')

@section('title', 'Détail du Service')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="container mx-auto px-4 sm:px-8 max-w-3xl">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">{{ $service->name }}</h2>
            </div>
            <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Information sur le Service</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Détails et informations.</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Nom du Service</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $service->name }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $service->description }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $service->category }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $service->status }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Conditions Bénévoles</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $service->condition }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Durée</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $service->duration }} minutes</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Compétences Associées</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @forelse($service->skills as $skill)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $skill->name }}
                                    </span>
                                @empty
                                    <span class="text-sm">Aucune compétence associée</span>
                                @endforelse
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Bénévoles Assignés</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Liste des bénévoles assignés à ce service.</p>
                </div>
                <div class="border-t border-gray-200">
                    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                        @forelse ($service->adhesionBenevole as $adhesion)
                            <li class="bg-white p-4 shadow-sm rounded-lg flex items-center space-x-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $adhesion->user->firstname }} {{ $adhesion->user->lastname }}
                                </div>
                            </li>
                        @empty
                            <li class="col-span-full text-center text-sm text-gray-500">
                                Aucun bénévole assigné à ce service.
                            </li>
                        @endforelse
                    </ul>
                </div>                
            </div>            
            <div class="flex py-4 mt-5 space-x-3">
                <a href="{{ route('services.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-md text-sm font-medium">Retour à la liste des services</a>
            </div>
        </div>
    </div>
</div>
@endsection






