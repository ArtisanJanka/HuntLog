<x-app-layout>
    {{-- Page styles (scoped) --}}
    <style>
        /* Pop-in reveal */
        @keyframes fadeUp { from {opacity:0; transform:translateY(14px)} to {opacity:1; transform:none} }
        .reveal { opacity: 0; transform: translateY(14px); }
        .reveal.show { animation: fadeUp .6s ease forwards; }

        /* Tabs, cards, inputs */
        .tab-btn { border: 1px solid rgba(255,255,255,.08); background: rgba(0,0,0,.35); transition: all .25s ease; }
        .tab-btn.active { background: linear-gradient(90deg, rgba(16,185,129,.20), rgba(16,185,129,.10)); border-color: rgba(16,185,129,.45); color: #d1fae5; }
        .card { transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease, border-color .25s ease; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 20px 40px -18px rgba(0,0,0,.6); }
        .field { background: #111827; border: 1px solid #374151; color: #e5e7eb; transition: all .25s ease; }
        .field:hover { border-color: #10b981; }
        .field:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.25); }
        .badge { font-size: .7rem; padding: .2rem .45rem; border-radius: .5rem; }

        @media (prefers-reduced-motion: reduce) {
            .reveal.show { animation: none !important; opacity: 1 !important; transform: none !important; }
        }
    </style>

    {{-- BACKGROUND LAYERS (fixed, above body, below content) --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <!-- Your animal image -->
        <div
            class="absolute inset-0"
            style="
                background-image: url('https://pbs.twimg.com/media/GzgU0ojXwAAAb9J.jpg');
                background-size: cover;
                background-position: center 40%;
                filter: saturate(1) contrast(1.05) brightness(0.95);
            "
        ></div>

        <!-- Soft overlay for readability (kept light so the animal stays visible) -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-black/35 to-black/55"></div>

        <!-- Very subtle radial glow for depth -->
        <div class="absolute inset-0"
             style="background:
                 radial-gradient(1100px 520px at 20% 0%, rgba(16,185,129,.10), transparent 60%),
                 radial-gradient(1200px 600px at 80% 100%, rgba(255,255,255,.05), transparent 65%);
             ">
        </div>
    </div>

    {{-- CONTENT --}}
    <div 
        class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-gray-100"
        x-data="leaderPanel()"
        x-init="onInit()"
    >
        {{-- Header --}}
        <header class="reveal flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Vadītāja panelis</h1>
                <p class="text-gray-200/90 mt-1">Pievieno lietotājus un apstrādā pieprasījumus savā komandā.</p>
            </div>

            {{-- Quick stats --}}
            <div class="grid grid-cols-2 gap-3 min-w-[260px] sm:min-w-[300px]">
                <div class="reveal card rounded-xl border border-white/10 bg-black/40 backdrop-blur-sm p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-300">Gaidošie pieprasījumi</div>
                    <div class="mt-1 text-2xl font-bold">
                        {{ $requests->count() }}
                    </div>
                </div>
                <div class="reveal card rounded-xl border border-white/10 bg-black/40 backdrop-blur-sm p-4" style="animation-delay:.08s">
                    <div class="text-xs uppercase tracking-wide text-gray-300">Statuss</div>
                    <div class="mt-1 text-2xl font-bold text-emerald-400">Aktīvs</div>
                </div>
            </div>
        </header>

        {{-- Tabs --}}
        <div class="reveal mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
            <button 
                @click="tab='users'" 
                :class="tab==='users' ? 'active' : ''"
                class="tab-btn px-4 py-2 rounded-lg font-semibold"
            >
                Jūsu lietotāji
            </button>
            <button 
                @click="tab='requests'" 
                :class="tab==='requests' ? 'active' : ''"
                class="tab-btn px-4 py-2 rounded-lg font-semibold"
            >
                Gaidošie pieprasījumi
                <span class="badge bg-emerald-600/30 border border-emerald-500/40 text-emerald-200 ml-2">
                    {{ $requests->count() }}
                </span>
            </button>
        </div>

        {{-- Tab: Add user (pop-in, no slide) --}}
        <section x-show="tab==='users'" x-transition.opacity class="mt-6">
            <div class="reveal card rounded-2xl border border-white/10 bg-black/45 backdrop-blur-xl p-6 sm:p-8">
                <div class="flex items-start gap-4 mb-6">
                    <div class="h-12 w-12 rounded-xl bg-emerald-500/15 text-emerald-300 flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Pievienot jaunu lietotāju</h2>
                        <p class="text-gray-300 text-sm">Aizpildi datus un nospied “Pievienot lietotāju”. Parole tiek validēta serverī.</p>
                    </div>
                </div>

                <form action="{{ route('leader.users.store') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Vārds</label>
                            <input type="text" name="name" class="field w-full rounded-lg p-3" placeholder="Jānis Jansons" required>
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">E-pasts</label>
                            <input type="email" name="email" class="field w-full rounded-lg p-3" placeholder="user@example.com" required>
                            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-sm text-gray-300 mb-1">Parole</label>
                            <input :type="showPass ? 'text' : 'password'" name="password" id="leader-pass" class="field w-full rounded-lg p-3 pr-12" placeholder="********" required>
                            <button type="button" @click="showPass=!showPass" class="absolute right-2.5 top-[34px] text-gray-300 hover:text-emerald-400">
                                <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 17 4.5 12 4.5Zm0 12a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9Z"/></svg>
                                <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3.53 2.47 2.47 3.53 5.3 6.36A11.77 11.77 0 0 0 1 12c1.73 4.39 6 7.5 11 7.5 2.1 0 4.08-.54 5.82-1.49l2.35 2.35 1.06-1.06L3.53 2.47ZM12 6.5c1.18 0 2.26.4 3.12 1.06l-1.11 1.11A3 3 0 0 0 9.7 12l-1.1 1.1A4.5 4.5 0 0 1 12 6.5Zm0 11a9.98 9.98 0 0 1-8.61-5.5 9.98 9.98 0 0 1 3.22-3.6l2.04 2.04c-.41.62-.65 1.36-.65 2.16a4.5 4.5 0 0 0 4.5 4.5c.8 0 1.54-.24 2.16-.65l1.51 1.51c-1.01.35-2.09.54-3.17.54Z"/></svg>
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Apstiprināt paroli</label>
                            <input :type="showPass ? 'text' : 'password'" name="password_confirmation" id="leader-pass2" class="field w-full rounded-lg p-3" placeholder="********" required>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-between pt-2">
                        <div class="flex items-center gap-3">
                            <button type="button" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15"
                                    @click="generate()">
                                Ģenerēt spēcīgu paroli
                            </button>
                            <span class="text-xs text-gray-300">Min. 12 simboli, jaukti rakstzīmju tipi.</span>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold">
                                Pievienot lietotāju
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- Tab: Requests (pop-in, no slide) --}}
        <section x-show="tab==='requests'" x-transition.opacity class="mt-6">
            <div class="reveal card rounded-2xl border border-white/10 bg-black/45 backdrop-blur-xl p-6 sm:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold">Gaidošie pieprasījumi</h2>
                        <p class="text-gray-300 text-sm">Apstiprini vai noraidi jaunus dalībniekus.</p>
                    </div>
                    <div class="relative w-full md:w-80">
                        <input
                            x-model="q"
                            type="text"
                            class="field w-full rounded-lg py-2.5 pl-10 pr-3"
                            placeholder="Meklēt pēc vārda vai e-pasta…"
                        >
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-300" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Zm11 19-5.2-5.2"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse($requests as $user)
                        @php
                            // These fields are optional; show if present
                            $requestedAt = $user->requested_at
                                ?? ($user->pivot->created_at ?? null)
                                ?? ($user->created_at ?? null);

                            $note = $user->request_note
                                ?? ($user->pivot->note ?? null)
                                ?? null;

                            $initials = collect(explode(' ', $user->name ?? ''))
                                        ->filter()
                                        ->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))
                                        ->join('');
                        @endphp

                        <div 
                            class="rounded-xl border border-white/10 bg-black/40 p-4 sm:p-5"
                            x-show="matches('{{ addslashes($user->name ?? '') }}','{{ addslashes($user->email ?? '') }}')"
                            x-data="{ open:false }"
                        >
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    {{-- Avatar / initials (falls back if no data yet) --}}
                                    <div class="h-12 w-12 rounded-full bg-emerald-500/15 text-emerald-300 flex items-center justify-center font-bold">
                                        {{ $initials ?: '👤' }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white leading-tight">{{ $user->name ?? '—' }}</div>
                                        <div class="text-gray-300 text-sm">{{ $user->email ?? '—' }}</div>

                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-300">
                                            @if($requestedAt)
                                                <span class="badge bg-white/10 border border-white/15">
                                                    Pieteicies: {{ \Carbon\Carbon::parse($requestedAt)->format('d.m.Y H:i') }}
                                                </span>
                                            @endif
                                            @if($note)
                                                <button @click="open=!open" class="underline hover:text-emerald-300">Skatīt piezīmi</button>
                                            @endif
                                        </div>

                                        @if($note)
                                            <div x-show="open" x-transition class="mt-2 text-gray-100 text-sm border border-white/10 bg-black/40 rounded-lg p-3">
                                                {{ $note }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <form action="{{ route('leader.requests.approve', $user) }}" method="POST">
                                        @csrf
                                        <button class="w-full sm:w-auto px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold">
                                            Apstiprināt
                                        </button>
                                    </form>
                                    <form action="{{ route('leader.requests.reject', $user) }}" method="POST" onsubmit="return confirm('Vai tiešām noraidīt šo pieprasījumu?')">
                                        @csrf
                                        <button class="w-full sm:w-auto px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 font-semibold">
                                            Noraidīt
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-white/10 bg-black/40 p-6 text-gray-200 text-center">
                            Nav gaidošo pieprasījumu
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

    {{-- Alpine helpers --}}
    <script>
        function leaderPanel(){
            return {
                tab: 'users',
                q: '',
                onInit(){
                    // Stagger reveals
                    const items = document.querySelectorAll('.reveal');
                    items.forEach((el, i) => setTimeout(() => el.classList.add('show'), 70 * i));
                },
                matches(name, email){
                    const s = this.q.trim().toLowerCase();
                    if(!s) return true;
                    return (name || '').toLowerCase().includes(s) || (email || '').toLowerCase().includes(s);
                },
                generate(){
                    // Strong password generator
                    const upp = "ABCDEFGHJKLMNPQRSTUVWXYZ";
                    const low = "abcdefghijkmnpqrstuvwxyz";
                    const num = "23456789";
                    const sym = "!@#$%^&*()-_=+[]{}";
                    const all = upp + low + num + sym;

                    const pick = (str, n) => Array.from({length:n}, () => str[Math.floor(Math.random()*str.length)]).join('');
                    let pwd = pick(upp, 2) + pick(low, 6) + pick(num, 2) + pick(sym, 2) + pick(all, 4);
                    pwd = pwd.split('').sort(() => Math.random() - 0.5).join(''); // shuffle

                    const p1 = document.getElementById('leader-pass');
                    const p2 = document.getElementById('leader-pass2');
                    if(p1 && p2){ p1.value = pwd; p2.value = pwd; }
                }
            }
        }
    </script>
</x-app-layout>
