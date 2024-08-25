@extends('layouts.templateAdmin')

@section('title', 'Administrateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="flex justify-end mt-6">
        <a href="{{ route('plannings.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-lg text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Ajouter un événement
        </a>
    </div>

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

