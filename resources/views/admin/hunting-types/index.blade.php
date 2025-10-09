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
            <header class="reveal mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Medību tipi</h1>
                    <p class="mt-2 text-gray-300">Pārvaldi tipa nosaukumu un slug. Meklē, kārto un rediģē.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.hunting-types.create') }}" class="btn-primary">
                        Pievienot jaunu tipu
                    </a>
                </div>
            </header>

            {{-- Controls --}}
            <div class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-4 sm:p-5 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input id="type-search" type="text" placeholder="Meklēt (nosaukums vai slug)…"
                                   class="w-80 max-w-[80vw] bg-gray-800/80 text-white rounded-lg pl-10 pr-3 py-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="sort-name" class="btn-ghost">Kārtot pēc nosaukuma</button>
                        <button id="sort-slug" class="btn-ghost">Kārtot pēc slug</button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <section class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden">
                @php $hasTypes = (isset($types) && count($types)); @endphp

                @if(!$hasTypes)
                    <div class="p-8 text-center text-gray-300">
                        Nav neviena tipa. <a href="{{ route('admin.hunting-types.create') }}" class="text-emerald-400 hover:text-emerald-300 underline">Pievieno pirmo</a>.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[720px]">
                            <thead class="sticky top-0 bg-black/30 backdrop-blur border-b border-white/10">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Nosaukums</th>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Slug</th>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Darbības</th>
                                </tr>
                            </thead>
                            <tbody id="type-tbody">
                                @foreach($types as $type)
                                    <tr class="type-row border-b border-white/10 hover:bg-white/[.06] transition"
                                        data-name="{{ strtolower($type->name) }}"
                                        data-slug="{{ strtolower($type->slug) }}">
                                        <td class="px-4 py-3 text-gray-100 font-medium">
                                            {{ $type->name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="inline-flex items-center gap-2">
                                                <span class="badge badge-slug">{{ $type->slug }}</span>
                                                <button class="btn-icon copy-slug" title="Kopēt slug" data-slug="{{ $type->slug }}">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8a2 2 0 002-2V7a2 2 0 00-2-2h-4l-2-2H8a2 2 0 00-2 2v2"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('admin.hunting-types.edit', $type) }}" class="btn-action bg-blue-600 hover:bg-blue-700">Rediģēt</a>
                                                <form action="{{ route('admin.hunting-types.destroy', $type) }}" method="POST"
                                                      onsubmit="return confirm('Vai tiešām dzēst tipu &quot;{{ $type->name }}&quot;?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn-action bg-red-600 hover:bg-red-700">Dzēst</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Optional pagination (only shows if $types is paginated) --}}
                    @if(method_exists($types, 'links'))
                        <div class="p-4 border-t border-white/10">
                            {{ $types->links() }}
                        </div>
                    @endif
                @endif
            </section>
        </div>

        {{-- Toast (copy feedback) --}}
        <div id="toast-root" class="pointer-events-none fixed top-4 right-4 z-[60] space-y-2"></div>
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

        const search = document.getElementById('type-search');
        const sortName = document.getElementById('sort-name');
        const sortSlug = document.getElementById('sort-slug');
        const tbody = document.getElementById('type-tbody');
        const rows = tbody ? Array.from(tbody.querySelectorAll('.type-row')) : [];

        function applySearch(){
            const q = (search?.value || '').trim().toLowerCase();
            rows.forEach(tr => {
                const name = tr.dataset.name || '';
                const slug = tr.dataset.slug || '';
                const show = !q || name.includes(q) || slug.includes(q);
                tr.style.display = show ? '' : 'none';
            });
        }
        search?.addEventListener('input', applySearch);

        function sortBy(key, asc=true){
            if (!tbody) return;
            const items = Array.from(tbody.children);
            items.sort((a,b)=>{
                const A = (a.dataset[key] || '').localeCompare(b.dataset[key] || '');
                return asc ? A : -A;
            });
            items.forEach(el => tbody.appendChild(el));
        }

        let nameAsc = true, slugAsc = true;
        sortName?.addEventListener('click', () => { sortBy('name', nameAsc); nameAsc = !nameAsc; sortName.classList.toggle('ring-2'); sortName.classList.toggle('ring-emerald-500'); });
        sortSlug?.addEventListener('click', () => { sortBy('slug', slugAsc); slugAsc = !slugAsc; sortSlug.classList.toggle('ring-2'); sortSlug.classList.toggle('ring-emerald-500'); });


        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.copy-slug');
            if (!btn) return;
            const slug = btn.dataset.slug || '';
            if (!slug) return;
            navigator.clipboard.writeText(slug).then(()=> toast('Slug nokopēts!'));
        });

        const toastRoot = document.getElementById('toast-root');
        function toast(msg){
            const div = document.createElement('div');
            div.className = 'pointer-events-auto rounded-lg px-3 py-2 text-sm shadow-lg border backdrop-blur bg-emerald-600/90 border-emerald-400 text-white';
            div.textContent = msg;
            toastRoot.appendChild(div);
            setTimeout(()=>{ div.style.opacity='0'; div.style.transform='translateY(-6px)'; }, 1800);
            setTimeout(()=> div.remove(), 2300);
        }
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
        background:#059669; /* emerald-600 */
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

    .btn-action{
        padding:.45rem .8rem; border-radius:.6rem; color:white; font-weight:700; transition: all .18s ease;
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
    .badge-slug{ color:#c7d2fe; background:rgba(59,130,246,.12); border-color:rgba(59,130,246,.35); }
    </style>
</x-app-layout>
