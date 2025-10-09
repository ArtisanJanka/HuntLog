<x-app-layout>
    {{-- Background + fog (VIDEO) --}}
    <section class="relative min-h-screen pt-20 sm:pt-24 overflow-hidden">
        {{-- Video layer --}}
        <video
            class="absolute inset-0 w-full h-full object-cover"
            src="{{ asset('storage/videos/fire.mp4') }}"
            playsinline
            muted
            loop
            autoplay
            preload="metadata"
            aria-hidden="true"
            tabindex="-1"
        ></video>

        {{-- Cinematic tint --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-gray-900/70 to-black/90"></div>

        {{-- Fog --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="fog fog-1"></div>
            <div class="fog fog-2"></div>
            <div class="fog fog-3"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" data-reveal-group>
            {{-- Header --}}
            <header class="reveal flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Profila iestatījumi</h1>
                    <p class="mt-2 text-gray-300">Atjauno savu informāciju, paroli un pārvaldi saglabātos poligonus.</p>
                </div>
                <a href="{{ route('map.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-lg shadow-emerald-900/30 focus:ring-2 focus:ring-emerald-500 transition">
                    Atvērt karti
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </header>

            {{-- Forms grid --}}
            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                {{-- Profile info --}}
                <section class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-6 sm:p-8">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="h-12 w-12 rounded-xl bg-emerald-500/15 text-emerald-300 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Profila informācija</h2>
                            <p class="text-gray-300 text-sm">Vārds, e-pasts un citi pamata dati.</p>
                        </div>
                    </div>
                    @include('profile.partials.update-profile-information-form', ['user' => $user])
                </section>

                {{-- Password --}}
                <section class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-6 sm:p-8">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="h-12 w-12 rounded-xl bg-emerald-500/15 text-emerald-300 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 10-6 0v2c0 1.657 1.343 3 3 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 11h14v10H5z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Paroles maiņa</h2>
                            <p class="text-gray-300 text-sm">Iesakām izmantot spēcīgu, unikālu paroli.</p>
                        </div>
                    </div>
                    @include('profile.partials.update-password-form', ['user' => $user])
                </section>
            </div>

            {{-- Polygons --}}
            <section class="mt-10">
                <div class="reveal flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Jūsu poligoni</h2>
                        <p class="text-gray-300 text-sm">Meklē, kopē koordinātas, skatīt kartē vai lejupielādē GeoJSON.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input id="poly-search" type="text" placeholder="Meklēt pēc nosaukuma..."
                                   class="w-72 max-w-[80vw] bg-gray-800/80 text-white rounded-lg pl-10 pr-3 py-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg>
                        </div>
                        <button id="poly-sort" class="px-3 py-2 rounded-lg text-sm font-semibold bg-white/10 text-white border border-white/10 hover:border-emerald-400/50 hover:text-emerald-300 hover:bg-white/15 transition">
                            Kārtot A→Z
                        </button>
                    </div>
                </div>

                @if($polygons->isEmpty())
                    <div class="reveal mt-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-6 text-gray-300">
                        Jums vēl nav saglabātu poligonu. <a href="{{ route('map.index') }}" class="text-emerald-400 hover:text-emerald-300 underline">Izveidot kartē</a>.
                    </div>
                @else
                    <ul id="poly-list" class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($polygons as $index => $polygon)
                            @php
                                $pts = 0;
                                try {
                                    $arr = json_decode($polygon->coordinates, true);
                                    if (is_array($arr)) { $pts = count($arr); }
                                } catch (\Throwable $e) {}
                            @endphp

                            <li class="reveal polygon-card group rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-xl p-5 text-gray-100"
                                data-name="{{ strtolower($polygon->name) }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-lg font-semibold text-white group-hover:text-emerald-300 transition-colors">
                                            {{ $polygon->name }}
                                        </h3>
                                        <p class="mt-1 text-xs text-gray-400">Punkti: {{ $pts > 0 ? $pts : '—' }}</p>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        {{-- Show on Map (focus this polygon) --}}
                                        <a href="{{ route('polygons.show', $polygon) }}" class="btn-icon" title="Skatīt kartē">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M9 20l-5-2V6l5 2 6-2 5 2v12l-5-2-6 2z"/>
                                            </svg>
                                        </a>

                                        {{-- Copy coordinates --}}
                                        <button class="btn-icon copy-btn" title="Kopēt koordinātas"
                                                data-coords='@json($polygon->coordinates)'>
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M8 16h8a2 2 0 002-2V7a2 2 0 00-2-2h-4l-2-2H8a2 2 0 00-2 2v2"/>
                                            </svg>
                                        </button>

                                        {{-- Download GeoJSON (client-side) --}}
                                        <button class="btn-icon dl-btn" title="Lejupielādēt GeoJSON"
                                                data-name="{{ $polygon->name }}"
                                                data-coords='@json($polygon->coordinates)'>
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                            </svg>
                                        </button>

                                        {{-- Toggle details --}}
                                        <button class="btn-icon toggle-btn" title="Rādīt detaļas">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4 hidden details">
                                    <pre class="text-xs text-gray-300 bg-black/30 rounded-lg p-3 overflow-auto max-h-48"></pre>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        {{-- Toasts --}}
        <div id="toast-root" class="pointer-events-none fixed top-4 right-4 z-[60] space-y-2"></div>
    </section>

    {{-- Interactivity --}}
    <script>
    (function(){
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Reveal stagger
        if (!prefersReduced) {
            const groupObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const items = entry.target.querySelectorAll('.reveal');
                    items.forEach((el, i) => {
                        el.style.transitionDelay = `${i * 110}ms`;
                        el.classList.add('show');
                    });
                    groupObserver.unobserve(entry.target);
                });
            }, { threshold: 0.15 });
            document.querySelectorAll('[data-reveal-group]').forEach(g => groupObserver.observe(g));
        } else {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('show'));
        }

        // Helpers
        const toastRoot = document.getElementById('toast-root');
        function toast(msg, ok=true){
            const div = document.createElement('div');
            div.className = `pointer-events-auto rounded-lg px-3 py-2 text-sm shadow-lg border backdrop-blur
                ${ok ? 'bg-emerald-600/90 border-emerald-400 text-white' : 'bg-red-600/90 border-red-400 text-white'}`;
            div.textContent = msg;
            toastRoot.appendChild(div);
            setTimeout(()=>{ div.style.opacity='0'; div.style.transform='translateY(-6px)'; }, 1800);
            setTimeout(()=> div.remove(), 2300);
        }

        // Polygon filter + sort
        const search = document.getElementById('poly-search');
        const sortBtn = document.getElementById('poly-sort');
        const list = document.getElementById('poly-list');
        if (search && list) {
            search.addEventListener('input', () => {
                const q = search.value.trim().toLowerCase();
                list.querySelectorAll('.polygon-card').forEach(li => {
                    const show = li.dataset.name?.includes(q);
                    li.style.display = show ? '' : 'none';
                });
            });
        }

        if (sortBtn && list) {
            let asc = true;
            sortBtn.addEventListener('click', () => {
                const items = Array.from(list.children);
                items.sort((a,b)=> (a.dataset.name > b.dataset.name ? 1 : -1) * (asc?1:-1));
                items.forEach(el => list.appendChild(el));
                asc = !asc;
                sortBtn.textContent = asc ? 'Kārtot A→Z' : 'Kārtot Z→A';
            });
        }

        // Copy + Download + Toggle details
        document.addEventListener('click', (e) => {
            const copyBtn = e.target.closest('.copy-btn');
            const dlBtn   = e.target.closest('.dl-btn');
            const toggle  = e.target.closest('.toggle-btn');

            if (copyBtn) {
                const raw = copyBtn.dataset.coords || '';
                const txt = tryPretty(raw);
                navigator.clipboard.writeText(txt).then(()=> toast('Koordinātas nokopētas!'));
            }

            if (dlBtn) {
                const name = (dlBtn.dataset.name || 'poligons').toString().trim() || 'poligons';
                const raw = dlBtn.dataset.coords || '[]';
                const geojson = buildGeoJSON(name, raw);
                if (!geojson) return toast('Neizdevās sagatavot GeoJSON', false);
                const blob = new Blob([JSON.stringify(geojson,null,2)], {type:'application/geo+json'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url; a.download = `${sanitize(name)}.geojson`;
                document.body.appendChild(a); a.click(); a.remove();
                URL.revokeObjectURL(url);
            }

            if (toggle) {
                const card = toggle.closest('.polygon-card');
                const box  = card.querySelector('.details');
                const pre  = box.querySelector('pre');
                if (box.classList.contains('hidden')) {
                    pre.textContent = tryPretty(card.querySelector('.copy-btn').dataset.coords || '[]');
                    box.classList.remove('hidden');
                } else {
                    box.classList.add('hidden');
                }
            }
        });

        function tryPretty(raw){
            try{
                const data = typeof raw === 'string' ? JSON.parse(raw) : raw;
                return JSON.stringify(data, null, 2);
            }catch(e){ return String(raw ?? ''); }
        }
        function buildGeoJSON(name, raw){
            try{
                const arr = typeof raw === 'string' ? JSON.parse(raw) : raw;
                if (!Array.isArray(arr) || arr.length < 3) return null;
                const ring = arr.map(p => [Number(p.lng), Number(p.lat)]);
                const first = ring[0], last = ring[ring.length-1];
                if (first[0] !== last[0] || first[1] !== last[1]) ring.push([...first]);
                return {
                    type: 'FeatureCollection',
                    features: [{
                        type: 'Feature',
                        properties: { name },
                        geometry: { type: 'Polygon', coordinates: [ ring ] }
                    }]
                };
            }catch(e){ return null; }
        }
        function sanitize(s){ return s.replace(/[^\p{L}\p{N}_-]+/gu,'_').slice(0,60) || 'poligons'; }
    })();
    </script>

    {{-- Styles (fog + reveal + buttons) --}}
    <style>
    /* Fog / Smoke */
    .fog {
        position:absolute; width:40vw; height:40vw; min-width:360px; min-height:360px;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 60%);
        filter: blur(60px); opacity:.25; transform: translateZ(0);
        animation: fogDrift 36s ease-in-out infinite;
    }
    .fog-1{ top:8%; left:-12%; }
    .fog-2{ bottom:-12%; right:-10%; animation-duration:42s; opacity:.22; }
    .fog-3{ top:40%; right:20%; animation-duration:38s; opacity:.18; }
    @keyframes fogDrift { 0%{transform:translate(0,0) scale(1)} 50%{transform:translate(60px,-40px) scale(1.12)} 100%{transform:translate(0,0) scale(1)} }

    /* Reveal */
    .reveal { opacity:0; transform: translateY(14px) scale(.98); transition: opacity .6s ease, transform .6s ease; }
    .reveal.show { opacity:1; transform: none; }

    /* Icon buttons */
    .btn-icon {
        display:inline-flex; align-items:center; justify-content:center;
        width:34px; height:34px; border-radius:.6rem;
        background: rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12);
        color:#e5e7eb; transition: all .2s ease;
    }
    .btn-icon:hover { color:#34d399; border-color: rgba(16,185,129,.5); background: rgba(255,255,255,.12); }

    /* Polygon cards hover */
    .polygon-card { transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
    .polygon-card:hover { transform: translateY(-2px); box-shadow: 0 18px 40px rgba(0,0,0,.45); border-color: rgba(16,185,129,.35); }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .fog { animation:none; opacity:.18; }
        .reveal { transition:none; opacity:1 !important; transform:none !important; }
    }
    </style>
</x-app-layout>
