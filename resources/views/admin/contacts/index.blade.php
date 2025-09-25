<!-- resources/views/admin/contacts/index.blade.php -->
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
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-emerald-400">Kontaktformu ziņas</h1>
                    <p class="mt-2 text-gray-300">Skati, meklē un kārto ienākošās ziņas no lietotājiem.</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn-ghost inline-flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Atpakaļ uz paneli
                    </a>
                </div>
            </header>

            {{-- Controls --}}
            <div class="reveal sticky top-16 sm:top-20 z-10 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-4 sm:p-5 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <div class="relative w-full max-w-xl">
                        <input id="search" type="text" placeholder="Meklēt (vārds / e-pasts / ziņa)…"
                               class="w-full bg-gray-800/80 text-white rounded-lg pl-10 pr-3 py-2 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="sortDate" class="btn-ghost">Jaunākie vispirms</button>
                        <button id="sortName" class="btn-ghost">A→Z pēc vārda</button>
                    </div>
                </div>
            </div>

            {{-- Table / Empty state --}}
            <section class="reveal">
                @if($messages->isEmpty())
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 text-center text-gray-300">
                        Pagaidām nav nevienas ziņas.
                    </div>
                @else
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[900px]">
                                <thead class="sticky top-0 bg-black/30 backdrop-blur border-b border-white/10">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Vārds</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">E-pasts</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Ziņa</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Datums</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-300">Darbības</th>
                                    </tr>
                                </thead>
                                <tbody id="msg-tbody">
                                    @foreach ($messages as $msg)
                                        @php
                                            $short = \Illuminate\Support\Str::limit($msg->message, 100);
                                        @endphp
                                        <tr class="msg-row border-b border-white/10 hover:bg-white/[.06] transition"
                                            data-name="{{ strtolower($msg->name) }}"
                                            data-email="{{ strtolower($msg->email) }}"
                                            data-body="{{ e($msg->message) }}"
                                            data-date="{{ optional($msg->created_at)->timestamp ?? 0 }}">
                                            <td class="px-4 py-3 text-gray-100 font-medium">{{ $msg->name }}</td>
                                            <td class="px-4 py-3">
                                                <div class="inline-flex items-center gap-2">
                                                    <span class="badge badge-mail">{{ $msg->email }}</span>
                                                    <button class="btn-icon copy-email" title="Kopēt e-pastu" data-email="{{ $msg->email }}">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8a2 2 0 002-2V7a2 2 0 00-2-2h-4l-2-2H8a2 2 0 00-2 2v2"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-gray-200">{{ $short }}</td>
                                            <td class="px-4 py-3 text-gray-400 whitespace-nowrap">
                                                {{ $msg->created_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap gap-2">
                                                    <button class="btn-chip bg-emerald-600 hover:bg-emerald-700 view-msg">Skatīt</button>
                                                    <a class="btn-chip bg-blue-600 hover:bg-blue-700"
                                                       href="mailto:{{ $msg->email }}?subject={{ rawurlencode('Atbilde par saziņu ar HuntLog') }}">
                                                       Atbildēt
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination (server-side) --}}
                        @if(method_exists($messages, 'links'))
                            <div class="p-4 border-t border-white/10">
                                {{ $messages->links() }}
                            </div>
                        @endif
                    </div>
                @endif
            </section>
        </div>

        {{-- Message Modal --}}
        <div id="msg-modal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/90 p-4">
            <div class="relative w-full max-w-2xl rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-6 shadow-2xl">
                <button id="modal-close" class="btn-icon absolute top-3 right-3" title="Aizvērt">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <h2 class="text-xl font-bold text-white mb-1" id="m-name">—</h2>
                <p class="text-sm text-gray-300 mb-4" id="m-email">—</p>
                <div class="rounded-lg border border-white/10 bg-black/30 p-4 text-gray-100 leading-relaxed max-h-[50vh] overflow-auto" id="m-body">—</div>

                <div class="mt-4 flex items-center justify-end gap-2">
                    <a id="m-mail" class="btn-ghost" href="#" target="_blank" rel="noopener">Rakstīt e-pastu</a>
                    <button id="m-copy" class="btn-ghost">Kopēt e-pastu</button>
                </div>
            </div>
        </div>

        {{-- Toasts --}}
        <div id="toast-root" class="pointer-events-none fixed top-4 right-4 z-[80] space-y-2"></div>
    </section>

    {{-- Scripts --}}
    <script>
    (function(){
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Reveal
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

        // Search + sort (client-side, current page)
        const search = document.getElementById('search');
        const tbody  = document.getElementById('msg-tbody');
        const rows   = tbody ? Array.from(tbody.querySelectorAll('.msg-row')) : [];
        function applySearch(){
            const q = (search?.value || '').trim().toLowerCase();
            rows.forEach(tr => {
                const name  = tr.dataset.name || '';
                const email = tr.dataset.email || '';
                const body  = (tr.dataset.body || '').toLowerCase();
                const show = !q || name.includes(q) || email.includes(q) || body.includes(q);
                tr.style.display = show ? '' : 'none';
            });
        }
        search?.addEventListener('input', applySearch);

        function sortBy(fn){
            if (!tbody) return;
            const items = Array.from(tbody.children);
            items.sort(fn).forEach(el => tbody.appendChild(el));
        }
        let dateAsc = false, nameAsc = true;
        document.getElementById('sortDate')?.addEventListener('click', (e)=>{
            e.currentTarget.classList.add('ring-2','ring-emerald-500');
            document.getElementById('sortName')?.classList.remove('ring-2','ring-emerald-500');
            sortBy((a,b)=> dateAsc ? (a.dataset.date - b.dataset.date) : (b.dataset.date - a.dataset.date));
            dateAsc = !dateAsc;
        });
        document.getElementById('sortName')?.addEventListener('click', (e)=>{
            e.currentTarget.classList.add('ring-2','ring-emerald-500');
            document.getElementById('sortDate')?.classList.remove('ring-2','ring-emerald-500');
            sortBy((a,b)=> nameAsc
                ? (a.dataset.name||'').localeCompare(b.dataset.name||'')
                : (b.dataset.name||'').localeCompare(a.dataset.name||''));
            nameAsc = !nameAsc;
        });

        // Modal
        const modal = document.getElementById('msg-modal');
        const mName = document.getElementById('m-name');
        const mEmail= document.getElementById('m-email');
        const mBody = document.getElementById('m-body');
        const mMail = document.getElementById('m-mail');
        const mCopy = document.getElementById('m-copy');
        const mClose= document.getElementById('modal-close');

        function openModal(name,email,body){
            mName.textContent = name || '—';
            mEmail.textContent = email || '—';
            mBody.textContent = body || '—';
            mMail.href = `mailto:${encodeURIComponent(email)}?subject=${encodeURIComponent('Atbilde par saziņu ar HuntLog')}`;
            modal.classList.remove('hidden'); modal.classList.add('flex');
        }
        function closeModal(){ modal.classList.add('hidden'); modal.classList.remove('flex'); }
        mClose?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (e)=> { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', (e)=> { if (e.key === 'Escape') closeModal(); });

        document.addEventListener('click', (e)=>{
            const rowBtn = e.target.closest('.view-msg');
            if (rowBtn){
                const tr = rowBtn.closest('.msg-row');
                openModal(tr?.dataset.name, tr?.dataset.email, tr?.dataset.body);
            }
        });

        // Copy email (table + modal)
        const toastRoot = document.getElementById('toast-root');
        function toast(msg){
            const div = document.createElement('div');
            div.className = 'pointer-events-auto rounded-lg px-3 py-2 text-sm shadow-lg border backdrop-blur bg-emerald-600/90 border-emerald-400 text-white';
            div.textContent = msg;
            toastRoot.appendChild(div);
            setTimeout(()=>{ div.style.opacity='0'; div.style.transform='translateY(-6px)'; }, 1800);
            setTimeout(()=> div.remove(), 2300);
        }
        document.addEventListener('click', (e)=>{
            const btn = e.target.closest('.copy-email');
            if (btn){
                const email = btn.dataset.email || '';
                if (email) { navigator.clipboard.writeText(email).then(()=> toast('E-pasts nokopēts!')); }
            }
        });
        mCopy?.addEventListener('click', ()=>{
            const email = mEmail.textContent || '';
            if (email) { navigator.clipboard.writeText(email).then(()=> toast('E-pasts nokopēts!')); }
        });
    })();
    </script>

    {{-- Styles --}}
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
    [data-reveal-group] .reveal { opacity:0; transform: translateY(14px) scale(.98); transition: opacity .6s ease, transform .6s ease; }
    [data-reveal-group] .reveal.show { opacity:1; transform: none; }

    /* Buttons */
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

    /* Badges */
    .badge{
        display:inline-flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:700;
        padding:.25rem .5rem; border-radius:.5rem; border:1px solid;
    }
    .badge-mail{ color:#c7d2fe; background:rgba(59,130,246,.12); border-color:rgba(59,130,246,.35); }
    </style>
</x-app-layout>
