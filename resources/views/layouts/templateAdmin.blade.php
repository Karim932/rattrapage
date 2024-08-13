<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts and Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"> --}}
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css"> --}}


        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        @vite(['resources/js/app.js', 'resources/css/app.css', 'resources/css/sidebarAdmin.css', 'resources/js/admin.js', 'resources/js/filterUsers.js', 'resources/js/adminTabs.js'])
    </head>

    <body class="flex min-h-screen bg-gray-100">

        @include('layouts/sidebar')

        <div class="container">
            @yield('content')
        </div>

        <!-- Modal Background -->
        <div id="confirmation-modal" class="fixed hidden flex inset-0 bg-gray-900 bg-opacity-50 items-center justify-center">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg  overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Icon -->
                            <svg class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.366-.446.977-.547 1.48-.234l.084.07 5 5c.401.402.401 1.058 0 1.46l-5 5c-.484.485-1.283.485-1.767 0-.484-.485-.484-1.283 0-1.767l.07-.084 3.302-3.301H4a1 1 0 01-.117-1.993L4 8h7.586L8.257 4.471a1.017 1.017 0 01-.234-1.48z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmation
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-message">
                                    Êtes-vous sûr de vouloir effectuer cette action ?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="confirm-btn" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmer
                    </button>
                    <button id="cancel-btn" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>

        <!-- Success message container with improved styling and accessibility -->
        <div id="success-message" class="hidden fixed top-10 left-1/2 transform -translate-x-1/2 bg-green-600 text-white p-4 rounded-lg shadow-md max-w-lg text-center z-50 transition-opacity duration-300 opacity-0">
            <span id="success-text">Votre action a été réussie!</span>
        </div>

    </body>
    {{-- <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2024 NO MORE WASTE. Tous droits réservés.</p>
        </div>
    </footer> --}}
</html>
