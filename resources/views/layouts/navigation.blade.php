<nav x-data="{ open: false, scrolled: false }" 
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
     :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900'" 
     class="fixed top-0 left-0 w-full z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" 
                   class="font-bold text-lg tracking-wider text-emerald-400 hover:text-emerald-500 transition-colors duration-300">
                    HuntLog
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex sm:items-center sm:space-x-8">
                <x-nav-link :href="route('join')" :active="request()->routeIs('join')" class="uppercase font-semibold text-gray-200 hover:text-emerald-500">Pieteikties</x-nav-link>
                <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="uppercase font-semibold text-gray-200 hover:text-emerald-500">Par mums</x-nav-link>
                <x-nav-link :href="route('gallery')" :active="request()->routeIs('gallery')" class="uppercase font-semibold text-gray-200 hover:text-emerald-500">Gallerija</x-nav-link>
                <x-nav-link :href="route('contacts')" :active="request()->routeIs('contacts')" class="uppercase font-semibold text-gray-200 hover:text-emerald-500">Kontakti</x-nav-link>
                <x-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')" class="uppercase font-semibold text-gray-200 hover:text-emerald-500">Kalendārs</x-nav-link>
                <x-nav-link :href="route('maps.index')" :active="request()->routeIs('maps.index')" class="uppercase font-semibold text-gray-200 hover:text-emerald-500">Karte</x-nav-link>

                @auth
                    @if(Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="uppercase font-semibold text-gray-200 hover:text-emerald-500">Admin</x-nav-link>
                    @endif
                @endauth
            </div>

            <!-- User Dropdown -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10B981&color=fff&bold=true" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="h-10 w-10 rounded-full border-2 border-emerald-500 shadow-md hover:scale-105 transition-transform" />
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2 text-sm text-gray-700 font-semibold">
                            <div class="font-bold">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-emerald-100 hover:text-emerald-700">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-red-100 hover:text-red-600">Log Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            <!-- Mobile Burger -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:bg-gray-700 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="open ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0'" class="sm:hidden overflow-hidden transition-all duration-500 bg-gray-900 shadow-md">
        <div class="px-4 pt-4 pb-6 space-y-3">
            <x-responsive-nav-link :href="route('join')" class="text-gray-200 hover:text-emerald-400">Pieteikties</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('about')" class="text-gray-200 hover:text-emerald-400">Par mums</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('gallery')" class="text-gray-200 hover:text-emerald-400">Gallerija</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contacts')" class="text-gray-200 hover:text-emerald-400">Kontakti</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('calendar')" class="text-gray-200 hover:text-emerald-400">Kalendārs</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('maps.index')" class="text-gray-200 hover:text-emerald-400">Karte</x-responsive-nav-link>
            @auth
                @if(Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" class="text-gray-200 hover:text-emerald-400">Admin</x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
        <div class="border-t border-gray-700 px-4 py-4">
            <div class="flex items-center space-x-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10B981&color=fff&bold=true" alt="{{ Auth::user()->name }}" class="h-10 w-10 rounded-full border-2 border-emerald-500"/>
                <div>
                    <div class="font-bold text-gray-100">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-200 hover:text-emerald-400">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400 hover:text-red-600">Log Out</x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>

<!-- Add padding-top for content so it doesn't hide behind fixed navbar -->
<div class="pt-16">
    @yield('content')
</div>
