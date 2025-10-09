<nav
    x-data="navbar()"
    x-init="init()"
    :class="[
        hiddenNav ? '-translate-y-full' : 'translate-y-0',
        scrolled
            ? 'backdrop-blur-md bg-black/50 border-b border-white/10 shadow-md'
            : 'bg-black',
        glow ? 'shadow-[0_0_15px_rgba(16,185,129,0.6)]' : ''
    ]"
    class="fixed top-0 left-0 w-full z-50 transition-all duration-500 ease-in-out select-none"
>
    @php
        $links = [
            ['route' => 'gallery',   'label' => 'Galerija'],
            ['route' => 'contacts',  'label' => 'Kontakti'],
            ['route' => 'calendar',  'label' => 'Kalendārs'],
            ['route' => 'map.index', 'label' => 'Karte'],
            ['route' => 'zoo',       'label' => 'Zoo'],
        ];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            :class="scrolled ? 'h-14' : 'h-20'"
            class="flex justify-between items-center transition-all duration-300 ease-in-out"
            style="transition-property: height, background, transform, box-shadow;"
        >
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}"
                   class="font-extrabold tracking-wider transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded"
                   :class="scrolled
                       ? 'text-gray-100 hover:text-emerald-400 text-lg'
                       : 'text-emerald-400 hover:text-emerald-300 text-xl'"
                >
                    HuntLog
                </a>
            </div>

            <!-- Desktop Links -->
            <div class="hidden sm:flex sm:items-center sm:space-x-2">
                @foreach($links as $link)
                    @php $active = request()->routeIs($link['route'] . '*'); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="group relative px-3 py-2 rounded-md uppercase font-semibold transition-colors duration-200"
                       :class="[
                           '{{ $active ? 'text-emerald-400' : 'text-white hover:text-emerald-300' }}',
                           scrolled ? 'text-gray-100 hover:text-emerald-300' : '',
                       ]"
                    >
                        {{ $link['label'] }}
                        <span class="pointer-events-none absolute left-3 right-3 -bottom-0.5 h-0.5 rounded bg-emerald-400 transition {{ $active ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0 group-hover:opacity-100 group-hover:scale-x-100' }}"></span>
                    </a>
                @endforeach

                @auth
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-2 uppercase font-semibold text-white hover:text-emerald-300 transition">
                            Admin
                        </a>
                    @endif
                    @if(Auth::user()->is_leader)
                        <a href="{{ route('leader.dashboard') }}"
                           class="px-3 py-2 uppercase font-semibold text-white hover:text-emerald-300 transition">
                            Vadītājs
                        </a>
                    @endif
                @endauth
            </div>

            @auth
            <div class="hidden sm:flex sm:items-center sm:space-x-2">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            :class="scrolled ? 'text-gray-100 hover:text-emerald-400' : 'text-white hover:text-emerald-300'"
                            class="flex items-center rounded-lg transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            aria-label="Lietotāja izvēlne"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                            </svg>
                        </button>
                    </x-slot>

                    
                    <x-slot name="content">
                        <div class="relative">
                            <span class="pointer-events-none absolute -top-2 right-6 h-4 w-4 rotate-45
                                         bg-black/60 backdrop-blur-md border-l border-t border-white/10"></span>
                            <div class="w-64 rounded-2xl border border-white/10 bg-black/60 backdrop-blur-md
                                        shadow-2xl shadow-black/50 p-4 text-gray-100">
                                <div class="flex items-center gap-3 pb-3 border-b border-white/10">
                                    <div class="h-10 w-10 rounded-full bg-emerald-500/20 text-emerald-300
                                                flex items-center justify-center font-bold">
                                        @php
                                            $initials = collect(explode(' ', Auth::user()->name ?? ''))
                                                ->filter()
                                                ->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))
                                                ->join('');
                                        @endphp
                                        {{ $initials ?: '👤' }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold truncate">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-300 truncate">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>

                                <div class="mt-2 space-y-1">
                                    <a href="{{ route('profile.edit') }}"
                                       class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium
                                              hover:bg-white/10 hover:text-emerald-300 transition">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4 0-8 2-8 5v3h16v-3c0-3-4-5-8-5Z"/>
                                        </svg>
                                        Profils
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium
                                                       text-red-300 hover:text-red-200 hover:bg-red-900/20 transition">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M10 3h4a2 2 0 0 1 2 2v4h-2V5h-4v14h4v-4h2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm9 8-3-3v2h-6v2h6v2l3-3Z"/>
                                            </svg>
                                            Izrakstīties
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            <div class="sm:hidden flex items-center">
                <button @click="toggle()" :aria-expanded="open.toString()" aria-controls="mobile-menu"
                        :class="scrolled ? 'text-gray-100' : 'text-white'"
                        class="inline-flex items-center justify-center p-2 rounded-md hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                        <path x-show="open"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 17L18 7M6 7l12 10"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu"
         x-show="open"
         x-transition.opacity
         @keydown.escape.window="open=false"
         @click.self="open=false"
         class="sm:hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm">
        <div class="mx-4 mt-24 rounded-2xl border border-white/10 bg-gray-900/95 p-6 shadow-2xl">
            <div class="flex flex-col items-stretch gap-2">
                @foreach($links as $link)
                    <a href="{{ route($link['route']) }}" @click="open=false"
                       class="block w-full rounded-lg px-4 py-3 text-lg font-semibold text-white hover:bg-white/10 hover:text-emerald-300 transition">
                        {{ $link['label'] }}
                    </a>
                @endforeach

                @auth
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" @click="open=false"
                           class="block w-full rounded-lg px-4 py-3 text-lg font-semibold text-white hover:bg-white/10 hover:text-emerald-300 transition">
                            Admin
                        </a>
                    @endif

                    @if(Auth::user()->is_leader)
                        <a href="{{ route('leader.dashboard') }}" @click="open=false"
                           class="block w-full rounded-lg px-4 py-3 text-lg font-semibold text-white hover:bg-white/10 hover:text-emerald-300 transition">
                            Vadītājs
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" @click="open=false"
                       class="block w-full rounded-lg px-4 py-3 text-lg font-semibold text-white hover:bg-white/10 hover:text-emerald-300 transition">
                        Profils
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-lg px-4 py-3 text-lg font-semibold text-red-300 hover:text-red-200 hover:bg-red-900/20 transition">
                            Izrakstīties
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div class="pt-20">
    @yield('content')
</div>

<script>
function navbar() {
    return {
        open: false,
        scrolled: false,
        hiddenNav: false,
        glow: false,
        lastY: 0,
        raf: null,
        toggle() { this.open = !this.open },
        init() {
            const onScroll = () => {
                if (this.raf) return;
                this.raf = requestAnimationFrame(() => {
                    this.scrolled = window.scrollY > 50;

                    this.hiddenNav = window.scrollY > this.lastY && window.scrollY > 80;

                    if (!this.hiddenNav && window.scrollY < this.lastY) {
                        this.glow = true;
                        clearTimeout(this._glowTimer);
                        this._glowTimer = setTimeout(() => this.glow = false, 600);
                    }

                    this.lastY = window.scrollY;
                    this.raf = null;
                });
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            this.scrolled = window.scrollY > 50;
        }
    }
}
</script>
