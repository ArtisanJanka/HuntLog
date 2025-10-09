<x-app-layout>
    {{-- Background + fog --}}
    <section class="relative min-h-screen pt-20 sm:pt-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-gray-900 to-black"></div>
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="fog fog-1"></div>
            <div class="fog fog-2"></div>
            <div class="fog fog-3"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" data-reveal-group>
            {{-- Header --}}
            <header class="reveal mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Galerijas vienības</h1>
                    <p class="mt-2 text-gray-300">Pievieno, meklē, kārto un pārvaldi attēlus.</p>
                </div>
                <a href="{{ route('admin.gallery.create') }}" class="btn-primary w-full sm:w-auto">
                    Pievienot jaunu
                </a>
            </header>

            {{-- Controls --}}
            @php
                // Extract unique type names for the filter (fallback to "Cits")
                $typeNames = collect($galleryItems)->map(function($i){
                    return optional($i->huntingType)->name ?: 'Cits';
                })->unique()->values();
            @endphp
            <div class="reveal sticky top-16 sm:top-20 z-10 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-4 sm:p-5 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <div class="flex flex-1 items-center gap-3">
                        <div class="relative w-full max-w-xl">
                            <input id="search" type="text" placeholder="Meklēt (nosaukums / tips)…"
                                   class="w-full bg-gray-800/80 text-white rounded-lg pl-10 pr-3 py-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg>
                        </div>
                        <select id="filterType" class="bg-gray-800/80 text-white rounded-lg border border-gray-700 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Visi tipi</option>
                            @foreach($typeNames as $t)
                                <option value="{{ strtolower($t) }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="sortNew" class="btn-ghost">Jaunākie vispirms</button>
                        <button id="sortAZ" class="btn-ghost">A→Z</button>
                    </div>
                </div>
            </div>

            {{-- Grid --}}
            @php $hasItems = (isset($galleryItems) && count($galleryItems)); @endphp
            <section class="reveal">
                @if(!$hasItems)
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 text-center text-gray-300">
                        Galerijā nav vienību. <a href="{{ route('admin.gallery.create') }}" class="text-emerald-400 hover:text-emerald-300 underline">Pievieno pirmo</a>.
                    </div>
                @else
                    <div id="grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($galleryItems as $item)
                            @php
                                $title = $item->title ?? optional($item->huntingType)->name ?? '—';
                                $typeLabel = optional($item->huntingType)->name ?? 'Cits';
                                $createdAtSort = optional($item->created_at)->timestamp ?? 0;
                            @endphp
                            <article class="card group rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden"
                                     data-title="{{ strtolower($title) }}"
                                     data-type="{{ strtolower($typeLabel) }}"
                                     data-date="{{ $createdAtSort }}">
                                <div class="relative">
                                    <img
                                        src="{{ asset('storage/' . $item->image_path) }}"
                                        alt="{{ $title }}"
                                        loading="lazy"
                                        class="w-full h-48 object-cover transition duration-700 ease-out opacity-0 blur-md"
                                        onload="this.classList.remove('opacity-0','blur-md')"
                                        data-full="{{ asset('storage/' . $item->image_path) }}"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 pointer-events-none"></div>

                                    {{-- Hover overlay actions --}}
                                    <div class="absolute inset-0 flex items-end p-3 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.gallery.edit', $item) }}" class="btn-chip bg-emerald-600 hover:bg-emerald-700">Rediģēt</a>
                                            <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Vai tiešām dzēst šo vienību?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-chip bg-red-600 hover:bg-red-700">Dzēst</button>
                                            </form>
                                            <button class="btn-icon open-lightbox" title="Skatīt">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-4.553a1 1 0 00-1.414-1.414L13.586 8.586M21 21H3V3h7l11 11v7z"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <h3 class="text-white font-semibold truncate" title="{{ $title }}">{{ $title }}</h3>
                                        <span class="badge badge-type">{{ $typeLabel }}</span>
                                    </div>
                                    @if($item->created_at)
                                        <p class="text-xs text-gray-400">Pievienots: {{ $item->created_at->format('d.m.Y H:i') }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Optional pagination --}}
                    @if(method_exists($galleryItems, 'links'))
                        <div class="mt-6">
                            {{ $galleryItems->links() }}
                        </div>
                    @endif
                @endif
            </section>
        </div>

        {{-- Lightbox --}}
        <div id="lightbox" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/90 p-4">
            <button id="lb-close" class="absolute top-4 right-4 btn-icon" title="Aizvērt">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <img id="lb-img" src="" alt="" class="max-h-[90vh] max-w-[90vw] rounded-xl shadow-2xl">
        </div>
    </section>

    {{-- Scripts --}}
    <script>
    (function(){
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

        const grid = document.getElementById('grid');
        const search = document.getElementById('search');
        const filterType = document.getElementById('filterType');
        const sortNew = document.getElementById('sortNew');
        const sortAZ = document.getElementById('sortAZ');

        function applyFilters(){
            const q = (search?.value || '').trim().toLowerCase();
            const t = (filterType?.value || '').trim();
            const cards = grid ? Array.from(grid.querySelectorAll('.card')) : [];
            let visible = 0;

            cards.forEach(card => {
                const title = card.dataset.title || '';
                const type  = card.dataset.type || '';
                const matchQ = !q || title.includes(q) || type.includes(q);
                const matchT = !t || type === t;
                const show = matchQ && matchT;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            if (grid && !visible) {
                if (!grid._empty) {
                    const div = document.createElement('div');
                    div.className = 'col-span-full rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 text-center text-gray-300';
                    div.textContent = 'Nekas neatbilst filtriem.';
                    grid.appendChild(div);
                    grid._empty = div;
                }
            } else if (grid && grid._empty) {
                grid._empty.remove();
                grid._empty = null;
            }
        }

        function sortBy(selector, compareFn){
            if (!grid) return;
            const items = Array.from(grid.children).filter(el => el.classList.contains('card'));
            items.sort(compareFn).forEach(el => grid.appendChild(el));
        }

        sortNew?.addEventListener('click', () => {
            sortBy('.card', (a,b) => Number(b.dataset.date||0) - Number(a.dataset.date||0));
            sortNew.classList.add('ring-2','ring-emerald-500'); sortAZ.classList.remove('ring-2','ring-emerald-500');
        });
        sortAZ?.addEventListener('click', () => {
            sortBy('.card', (a,b) => (a.dataset.title||'').localeCompare(b.dataset.title||''));
            sortAZ.classList.add('ring-2','ring-emerald-500'); sortNew.classList.remove('ring-2','ring-emerald-500');
        });

        search?.addEventListener('input', applyFilters);
        filterType?.addEventListener('change', applyFilters);


        const lb = document.getElementById('lightbox');
        const lbImg = document.getElementById('lb-img');
        const lbClose = document.getElementById('lb-close');

        document.addEventListener('click', (e) => {
            const cardImg = e.target.closest('img[data-full]');
            const btnOpen = e.target.closest('.open-lightbox');
            if (cardImg && !btnOpen) return;
            if (btnOpen) {
                const art = btnOpen.closest('.card');
                const img = art?.querySelector('img[data-full]');
                if (!img) return;
                lbImg.src = img.dataset.full;
                lb.classList.remove('hidden');
                lb.classList.add('flex');
            }
        });
        function closeLB(){ lb.classList.add('hidden'); lb.classList.remove('flex'); lbImg.src=''; }
        lbClose?.addEventListener('click', closeLB);
        lb?.addEventListener('click', (e)=> { if (e.target === lb) closeLB(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeLB(); });
    })();
    </script>

    {{-- Styles --}}
    <style>

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

    [data-reveal-group] .reveal { opacity:0; transform: translateY(14px) scale(.98); transition: opacity .6s ease, transform .6s ease; }
    [data-reveal-group] .reveal.show { opacity:1; transform: none; }

    .btn-primary{
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.6rem 1rem; border-radius:.8rem; font-weight:700; color:white;
        background:#059669;
        box-shadow: 0 10px 24px rgba(16,185,129,.25);
        transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
    }
    .btn-primary:hover{ background:#047857; transform: translateY(-1px); box-shadow: 0 18px 40px rgba(16,185,129,.35); }

    .btn-ghost{
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.5rem .9rem; border-radius:.7rem;
        background: rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12);
        color:#e5e7eb; font-weight:600; transition: all .2s ease;
    }
    .btn-ghost:hover{ color:#34d399; border-color: rgba(16,185,129,.5); background: rgba(255,255,255,.12); }

    .btn-chip{
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.35rem .6rem; border-radius:.6rem; color:white; font-weight:700; transition: all .18s ease;
        box-shadow: 0 10px 24px rgba(0,0,0,.25);
    }
    .btn-icon{
        display:inline-flex; align-items:center; justify-content:center;
        width:30px; height:30px; border-radius:.55rem;
        background: rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12);
        color:#e5e7eb; transition: all .18s ease;
    }
    .btn-icon:hover{ color:#34d399; border-color: rgba(16,185,129,.5); background: rgba(255,255,255,.12); }

    .badge{
        display:inline-flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:700;
        padding:.25rem .5rem; border-radius:.5rem; border:1px solid;
    }
    .badge-type{ color:#c7d2fe; background:rgba(59,130,246,.12); border-color:rgba(59,130,246,.35); }


    .card { transition: transform .22s ease, box-shadow .25s ease, border-color .25s ease; }
    .card:hover{ transform: translateY(-2px); box-shadow: 0 18px 40px rgba(0,0,0,.45); border-color: rgba(16,185,129,.35); }
    </style>
</x-app-layout>
