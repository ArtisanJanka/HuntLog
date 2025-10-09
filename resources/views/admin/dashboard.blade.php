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
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Administrācijas panelis</h1>
                    <p class="mt-2 text-gray-300">Sveicināts, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>! Pārvaldi saturu, lietotājus un ziņas.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.gallery.index') }}" class="btn-ghost">Galerija</a>
                    <a href="{{ route('admin.hunting-types.index') }}" class="btn-ghost">Medību veidi</a>
                    <a href="{{ route('admin.messages.index') }}" class="btn-ghost">Ziņas</a>
                </div>
            </header>

            {{-- KPIs --}}
            @php
                $cards = [
                    ['title'=>'Galerijas vienības','count'=>$galleryCount,'link'=>route('admin.gallery.index'),'desc'=>'Augšupielādētie attēli un video'],
                    ['title'=>'Medību veidi','count'=>$huntingTypeCount,'link'=>route('admin.hunting-types.index'),'desc'=>'Konfigurēti tipi sistēmā'],
                    ['title'=>'Ziņas','count'=>$messageCount,'link'=>route('admin.messages.index'),'desc'=>'Saņemtās kontaktu formas ziņas'],
                ];
            @endphp
            <div class="reveal grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($cards as $i => $card)
                    <a href="{{ $card['link'] }}"
                       class="kpi-card group rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-6 hover:border-emerald-300/40 transition">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm text-gray-400">{{ $card['title'] }}</p>
                                <div class="mt-1 text-3xl font-extrabold text-white tracking-tight">{{ $card['count'] }}</div>
                            </div>
                            <div class="h-10 w-10 rounded-xl bg-emerald-500/15 text-emerald-300 flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8M3 7h6M3 7v6M3 7l6 6"/></svg>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-400">{{ $card['desc'] }}</p>
                    </a>
                @endforeach
            </div>

            {{-- Users --}}
            <section class="reveal mt-10 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4 border-b border-white/10">
                    <h2 class="text-lg sm:text-xl font-bold text-white">Lietotāji</h2>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative">
                            <input id="user-search" type="text" placeholder="Meklēt (vārds, e-pasts)…"
                                   class="w-72 max-w-[80vw] bg-gray-800/80 text-white rounded-lg pl-10 pr-3 py-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg>
                        </div>
                        <select id="role-filter" class="bg-gray-800/80 text-white rounded-lg border border-gray-700 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Visas lomas</option>
                            <option value="admin">Administrators</option>
                            <option value="leader">Līderis</option>
                            <option value="user">Lietotājs</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[720px]">
                        <thead class="sticky top-0 bg-black/30 backdrop-blur border-b border-white/10">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Vārds</th>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">E-pasts</th>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Loma</th>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Darbības</th>
                            </tr>
                        </thead>
                        <tbody id="user-tbody">
                            @foreach($users as $u)
                                @php
                                    $role = $u->is_admin ? 'admin' : ($u->is_leader ? 'leader' : 'user');
                                @endphp
                                <tr class="user-row border-b border-white/10 hover:bg-white/[.06] transition"
                                    data-name="{{ strtolower($u->name) }}"
                                    data-email="{{ strtolower($u->email) }}"
                                    data-role="{{ $role }}">
                                    <td class="px-4 py-3 text-gray-100">{{ $u->name }}</td>
                                    <td class="px-4 py-3 text-gray-300">{{ $u->email }}</td>
                                    <td class="px-4 py-3">
                                        @if($u->is_admin)
                                            <span class="badge badge-admin">Administrators</span>
                                        @elseif($u->is_leader)
                                            <span class="badge badge-leader">Līderis</span>
                                        @else
                                            <span class="badge badge-user">Lietotājs</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            @if(!$u->is_leader && !$u->is_admin)
                                                <form action="{{ route('admin.users.makeLeader', $u) }}" method="POST" onsubmit="return confirm('Padarīt {{ $u->name }} par līderi?')">
                                                    @csrf
                                                    <button class="btn-action bg-emerald-600 hover:bg-emerald-700">Padarīt par līderi</button>
                                                </form>
                                            @elseif($u->is_leader && !$u->is_admin)
                                                <form action="{{ route('admin.users.removeLeader', $u) }}" method="POST" onsubmit="return confirm('Noņemt līdera lomu {{ $u->name }}?')">
                                                    @csrf
                                                    <button class="btn-action bg-red-600 hover:bg-red-700">Noņemt līderi</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">Admin lomu nevar mainīt</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Latest messages --}}
            <section class="reveal mt-10 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-white/10">
                    <h2 class="text-lg sm:text-xl font-bold text-white">Pēdējās ziņas</h2>
                    <a href="{{ route('admin.messages.index') }}" class="text-emerald-300 hover:text-emerald-200 underline">Skatīt visas</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[720px]">
                        <thead class="sticky top-0 bg-black/30 backdrop-blur border-b border-white/10">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Vārds</th>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">E-pasts</th>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Ziņa</th>
                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Datums</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                                <tr class="border-b border-white/10 hover:bg-white/[.06] transition">
                                    <td class="px-4 py-3 text-gray-100">{{ $msg->name }}</td>
                                    <td class="px-4 py-3 text-gray-300">{{ $msg->email }}</td>
                                    <td class="px-4 py-3 text-gray-200">
                                        {{ \Illuminate\Support\Str::limit($msg->message, 80) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-300">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">Nav vēl ziņu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Toasts (optional show after post redirects) --}}
        @if(session('success'))
            <div id="flash-success" data-msg="{{ session('success') }}"></div>
        @endif
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


        const search = document.getElementById('user-search');
        const filter = document.getElementById('role-filter');
        const rows = Array.from(document.querySelectorAll('#user-tbody .user-row'));

        function applyFilter(){
            const q = (search?.value || '').trim().toLowerCase();
            const role = (filter?.value || '').trim();
            rows.forEach(tr => {
                const name = tr.dataset.name || '';
                const email = tr.dataset.email || '';
                const r = tr.dataset.role || '';
                const matchText = !q || name.includes(q) || email.includes(q);
                const matchRole = !role || r === role;
                tr.style.display = (matchText && matchRole) ? '' : 'none';
            });
        }
        search?.addEventListener('input', applyFilter);
        filter?.addEventListener('change', applyFilter);


        const f = document.getElementById('flash-success');
        if (f?.dataset.msg) toast(f.dataset.msg);


        function toast(msg){
            const t = document.createElement('div');
            t.className = 'fixed top-4 right-4 z-[60] rounded-lg px-3 py-2 text-sm bg-emerald-600/90 text-white border border-emerald-400 shadow-xl';
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateY(-6px)'; }, 1800);
            setTimeout(()=> t.remove(), 2300);
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


    .btn-ghost{
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.5rem .9rem; border-radius:.7rem;
        background: rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12);
        color:#e5e7eb; font-weight:600; transition: all .2s ease;
    }
    .btn-ghost:hover{ color:#34d399; border-color: rgba(16,185,129,.5); background: rgba(255,255,255,.12); }

    .btn-action{
        padding:.4rem .7rem; border-radius:.5rem; color:white; font-weight:600; transition: all .2s ease;
        box-shadow: 0 8px 18px rgba(0,0,0,.25);
    }


    .badge{
        display:inline-flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:700;
        padding:.25rem .5rem; border-radius:.5rem; border:1px solid;
    }
    .badge-admin  { color:#fde68a; background:rgba(251,191,36,.1); border-color:rgba(251,191,36,.35); }
    .badge-leader { color:#86efac; background:rgba(16,185,129,.12); border-color:rgba(16,185,129,.4); }
    .badge-user   { color:#c7d2fe; background:rgba(59,130,246,.12); border-color:rgba(59,130,246,.35); }


    .kpi-card{ transition: transform .22s ease, box-shadow .25s ease, border-color .25s ease; }
    .kpi-card:hover{ transform: translateY(-2px); box-shadow: 0 18px 40px rgba(0,0,0,.45); }
    </style>
</x-app-layout>
