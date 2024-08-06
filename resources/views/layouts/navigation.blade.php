<!-- resources/views/components/navbar.blade.php -->
<nav x-data="{ open: false }" class="bg-white shadow-md fixed w-full z-30 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('accueil') }}" class="text-gray-900 no-underline hover:no-underline font-bold text-2xl lg:text-4xl">
                        NoMoreWaste
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('accueil')" :active="request()->routeIs('accueil')">
                        {{ __('message.Accueil') }}
                    </x-nav-link>
                    <x-nav-link :href="route('services')" :active="request()->routeIs('services')">
                        {{ __('Services') }}
                    </x-nav-link>
                    <x-nav-link :href="route('adhesions')" :active="request()->routeIs('adhesions')">
                        {{ __('message.Adhésions') }}
                    </x-nav-link>
                    @auth
                    <x-nav-link :href="route('collectes')" :active="request()->routeIs('collectes')">
                        {{ __('message.Collectes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('stocks')" :active="request()->routeIs('stocks')">
                        {{ __('Stocks') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tournees')" :active="request()->routeIs('tournees')">
                        {{ __('message.Tournées') }}
                    </x-nav-link>
                    @endauth
                    <x-nav-link :href="route('benevoles')" :active="request()->routeIs('benevoles')">
                        {{ __('message.Bénévoles') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                        {{ __('Contact') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            <div>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                            <div class="ml-2">
                                <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @can('is-admin')
                            <x-dropdown-link :href="route('admin.dashboard')" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Administrateur') }}
                            </x-dropdown-link>
                        @endcan

                        @foreach (config('localization.locales') as $locale)
                            <x-dropdown-link :href="route('localization', $locale)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                @switch($locale)
                                    @case('en')
                                        <i class="flag-icon flag-icon-us mr-2"></i>
                                        @break
                                    @case('fr')
                                        <i class="flag-icon flag-icon-fr mr-2"></i>
                                        @break
                                    @default
                                        <i class="flag-icon flag-icon-us mr-2"></i>
                                @endswitch
                                {{ __('message.' . $locale) }}
                            </x-dropdown-link>
                        @endforeach

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-600">
                                {{ __('Se déconnecter') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 no-underline hover:text-gray-900 hover:underline py-2 px-4">Se connecter</a>
                @endguest
            </div>

            <!-- Settings Dropdown -->
            @guest
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="12">
                    <x-slot name="trigger">
                        <!-- Bouton simplifié avec chapeau "^" -->
                        <button class="inline-flex items-center p-2 bg-white border border-gray-300 rounded-full text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            <div class="text-lg font-bold">↓</div> <!-- Chapeau plus stylé et centré -->
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @foreach (config('localization.locales') as $locale)
                            <x-dropdown-link :href="route('localization', $locale)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                @switch($locale)
                                    @case('en')
                                        <i class="flag-icon flag-icon-us mr-2"></i>
                                        @break
                                    @case('fr')
                                        <i class="flag-icon flag-icon-fr mr-2"></i>
                                        @break
                                    @default
                                        <i class="flag-icon flag-icon-us mr-2"></i>
                                @endswitch
                                {{ __('message.' . $locale) }}
                            </x-dropdown-link>
                        @endforeach
                    </x-slot>
                </x-dropdown>
            </div>
            @endguest


            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('accueil')" :active="request()->routeIs('accueil')">
                {{ __('Accueil') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services')" :active="request()->routeIs('services')">
                {{ __('services') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services')" :active="request()->routeIs('services')">
                {{ __('Services') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('adhesions')" :active="request()->routeIs('adhesions')">
                {{ __('Adhésions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('collectes')" :active="request()->routeIs('collectes')">
                {{ __('Collectes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stocks')" :active="request()->routeIs('stocks')">
                {{ __('Stocks') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tournees')" :active="request()->routeIs('tournees')">
                {{ __('Tournées') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('benevoles')" :active="request()->routeIs('benevoles')">
                {{ __('Bénévoles') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                {{ __('Contact') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            @endauth


            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Se déconnecter') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
