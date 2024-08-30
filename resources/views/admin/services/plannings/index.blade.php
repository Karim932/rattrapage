@extends('layouts.templateAdmin')

@section('title', 'Administrateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="flex justify-between items-center mt-6">
        <form method="GET" action="{{ route('plannings.index') }}" class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-md">
            <div>
                <label for="city" class="block text-sm font-semibold text-gray-700 mb-1">Filtrer par Ville</label>
                <select name="city" id="city" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    <option value="">Toutes les villes</option>
                    @foreach($cities as $c)
                        <option value="{{ $c->city }}" {{ $c->city == $city ? 'selected' : '' }}>
                            {{ $c->city }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div>
                <label for="service_id" class="block text-sm font-semibold text-gray-700 mb-1">Filtrer par Service</label>
                <select name="service_id" id="service_id" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ $service->id == $serviceId ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="block">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Appliquer
                </button>
            </div>
            
        </form>
    </div>
    <div class="flex justify-between items-center mt-6">
        <a href="{{ route('plannings.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-md shadow-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Ajouter un événement
        </a>
    </div>
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div id="success-message" class="bg-green-500 text-white p-4 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif
    

    <div class="container mt-6">
        <div id="calendar"></div>  
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/calendar.js') }}"></script>
@endpush
@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endpush
@endsection
