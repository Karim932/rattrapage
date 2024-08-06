<x-app-layout>
    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('message.log-test') }}
                </div>
                <div class="p-6 text-gray-900">
                    {{ __('log') }}
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-2 sm:justify-end">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                            <div>{{ __('Locale')}}</div>


                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                    </x-slot>
                </x-dropdown>
            </div>


            {{-- <li>
                <a href="{{ route('changeLang', ['locale' => 'en']) }}">
                    <span class="flag-icon flag-icon-us"></span> English
                </a>
            </li>
            <li>
                <a href="{{ route('changeLang', ['locale' => 'es']) }}">
                    <span class="flag-icon flag-icon-es"></span> Espagnol
                </a>
            </li>
            <li>
                <a href="{{ route('changeLang', ['locale' => 'fr']) }}">
                    <span class="flag-icon flag-icon-fr"></span> Français
                </a>
            </li> --}}

        </div>
    </div>
</x-app-layout>
