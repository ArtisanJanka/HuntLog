<x-app-layout>
    {{-- Styles (adds slow pop-in like the leader dashboard) --}}
    <style>
        @keyframes kenBurns { 0%{transform:scale(1) translateZ(0)} 100%{transform:scale(1.08) translateZ(0)} }
        @keyframes fogDrift { 0%{transform:translate(0,0) scale(1);opacity:.25} 50%{transform:translate(60px,-40px) scale(1.12);opacity:.35} 100%{transform:translate(0,0) scale(1);opacity:.25} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }

        .reveal { opacity:0; transform:translateY(14px) }
        .reveal.show { animation: fadeUp .6s ease forwards } /* same as leader dashboard */

        .tab-btn{border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.06);color:#e5e7eb;border-radius:.75rem;padding:.6rem 1rem;font-weight:600;transition:.2s}
        .tab-btn:hover{border-color:rgba(16,185,129,.55)}
        .tab-btn.active{background:linear-gradient(90deg,rgba(16,185,129,.22),rgba(16,185,129,.12));color:#d1fae5;border-color:rgba(16,185,129,.55)}

        .field{background:#0b1220;border:1px solid #2a3443;color:#e5e7eb;border-radius:.75rem;padding:.625rem .9rem;width:100%;transition:.2s}
        .field:focus{outline:none;border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.25)}
        .card{border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.05);backdrop-filter:blur(10px);box-shadow:0 25px 40px -20px rgba(0,0,0,.55);border-radius:1rem}
        .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.7rem 1rem;border-radius:.75rem;font-weight:600}
        .btn-emerald{background:#059669;color:#fff}
        .btn-emerald:hover{background:#047857}
        .btn-muted{background:rgba(255,255,255,.08);color:#e5e7eb;border:1px solid rgba(255,255,255,.12)}
        .btn-muted:hover{border-color:rgba(16,185,129,.45)}
        .ring-animated{position:relative;border-radius:1.25rem;isolation:isolate}
        .ring-animated::before{content:"";position:absolute;inset:-1px;border-radius:inherit;z-index:-1;background:conic-gradient(from 0deg,#10b981,#22d3ee,#a78bfa,#10b981);animation:spin 8s linear infinite;filter:blur(8px);opacity:.45}
        @keyframes spin{to{transform:rotate(360deg)}}

        #particles{position:fixed;inset:0;z-index:0;pointer-events:none}
        .fog{position:absolute;border-radius:9999px;will-change:transform}

        @media (prefers-reduced-motion:reduce){
            .parallax,.fog,.ring-animated::before{animation:none!important}
            .reveal{transition:none;opacity:1!important;transform:none!important}
        }
    </style>

    <section class="relative min-h-[92vh] sm:min-h-screen overflow-hidden text-gray-100"
             x-data="contactsState({ types: @js($types ?? []), groups: @js($groups ?? []) })"
             x-init="init()">

        {{-- HERO background --}}
        <div id="hero" class="absolute inset-0 -z-10 parallax">
            <img
                src="https://upload.wikimedia.org/wikipedia/commons/9/94/Eurasian_brown_bear_%28Ursus_arctos_arctos%29_female_1.jpg"
                alt="Meža fons"
                class="w-full h-full object-cover will-change-transform"
                style="animation: kenBurns 22s ease-in-out infinite alternate"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/60 to-black/80"></div>
        </div>

        {{-- Fog orbs --}}
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="fog absolute top-[8%] -left-[10%] w-[42vw] h-[42vw] min-w-[320px] min-h-[320px]"
                 style="background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 60%);filter:blur(60px);animation:fogDrift 36s ease-in-out infinite;"></div>
            <div class="fog absolute bottom-[-12%] right-[-10%] w-[40vw] h-[40vw] min-w-[320px] min-h-[320px]"
                 style="background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 60%);filter:blur(60px);animation:fogDrift 42s ease-in-out infinite;"></div>
            <div class="fog absolute top-[40%] right-[20%] w-[34vw] h-[34vw] min-w-[280px]"
                 style="background:radial-gradient(circle,rgba(16,185,129,.12) 0%,transparent 60%);filter:blur(70px);animation:fogDrift 38s ease-in-out infinite;"></div>
        </div>

        {{-- Particles --}}
        <canvas id="particles" aria-hidden="true"></canvas>

        <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 py-16 sm:py-24" data-reveal-group>
            {{-- Header + tabs --}}
            <div class="reveal flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-white">Sazinies ar <span class="text-emerald-400">HuntLog</span></h1>
                    <p class="mt-2 text-gray-200/90">Raksti mums vai nosūti pieprasījumu konkrētam vadītājam/grupai.</p>
                </div>
                <div class="flex gap-2">
                    <button class="tab-btn" :class="tab==='contact' ? 'active' : ''" @click="tab='contact'">Sazināties</button>
                    <button class="tab-btn" :class="tab==='join' ? 'active' : ''" @click="tab='join'">Pieteikties grupai</button>
                </div>
            </div>

            {{-- TAB 1: SAZINĀTIES --}}
            <div x-show="tab==='contact'" x-transition.opacity class="mt-8 grid lg:grid-cols-2 gap-8">
                <div class="reveal">
                    <div class="card p-6 sm:p-8">
                        <h2 class="text-xl font-bold text-white mb-4">Ātrā saziņa</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <a href="tel:29490737" class="btn btn-muted">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.69l1.52 4.55a1 1 0 01-.5 1.2l-2.26 1.13a11.05 11.05 0 005.52 5.52l1.13-2.26a1 1 0 011.2-.5l4.55 1.52a1 1 0 01.69.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/>
                                </svg>
                                29490737
                            </a>
                            <a href="mailto:huntlogs@gmail.com" class="btn btn-muted">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.5 5L18 8m-15 8h18V6H3v10z"/>
                                </svg>
                                huntlogs@gmail.com
                            </a>
                        </div>
                        <p class="mt-3 text-xs text-gray-400">Parasti atbildam 24h laikā.</p>
                    </div>
                </div>

                <div class="reveal ring-animated">
                    <div class="card p-6 sm:p-8">
                        <h2 class="text-2xl font-bold text-emerald-300 text-center">Sūtiet mums ziņu</h2>

                        @if(session('success'))
                            <div class="mt-4 rounded-lg bg-emerald-600/90 text-white px-4 py-3 shadow-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contacts.store') }}" class="mt-6 space-y-5" id="contactForm">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-semibold text-white mb-1">Vārds</label>
                                <input id="name" name="name" type="text" required autocomplete="name" class="field" placeholder="Jūsu vārds" value="{{ old('name') }}">
                                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-white mb-1">E-pasts</label>
                                <input id="email" name="email" type="email" required autocomplete="email" class="field" placeholder="you@example.com" value="{{ old('email') }}">
                                @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-semibold text-white mb-1">Ziņa</label>
                                <textarea id="message" name="message" rows="5" required class="field placeholder-gray-400"
                                          placeholder="Ko mēs varam palīdzēt?"
                                          @input="count=$event.target.value.length; if(count>1000){ $event.target.value=$event.target.value.slice(0,1000); count=1000 }">{{ old('message') }}</textarea>
                                <div class="mt-1 text-xs text-gray-400"><span x-text="count"></span>/1000</div>
                                @error('message') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-1 text-center">
                                <button type="submit" class="btn btn-emerald shadow-lg shadow-emerald-900/30">
                                    <svg class="h-5 w-5 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Sūtīt ziņu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TAB 2: PIETEIKTIES GRUPAI --}}
            <div x-show="tab==='join'" x-transition.opacity class="mt-8">
                <div class="reveal">
                    <div class="card p-6 sm:p-8 max-w-3xl mx-auto">
                        <h2 class="text-xl font-bold mb-4">Pieteikties grupai</h2>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Medību tips</label>
                                <select class="field" x-model.number="selectedTypeId">
                                    <option :value="null">— Izvēlies tipu —</option>
                                    <template x-for="t in types" :key="t.id">
                                        <option :value="t.id" x-text="t.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Vadītājs</label>
                                <select class="field" x-model.number="selectedLeaderId" :disabled="!selectedTypeId">
                                    <option :value="null">— Izvēlies vadītāju —</option>
                                    <template x-for="l in leadersForType" :key="l.id">
                                        <option :value="l.id" x-text="l.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm text-gray-300 mb-1">Konkrēta grupa (izvēles)</label>
                            <select class="field" x-model.number="selectedGroupId" :disabled="!selectedLeaderId">
                                <option :value="null">— Bez konkrētas grupas —</option>
                                <template x-for="g in groupsFiltered" :key="g.id">
                                    <option :value="g.id" x-text="g.name"></option>
                                </template>
                            </select>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('join-group.store') }}" class="mt-4 space-y-4">
                                @csrf
                                <input type="hidden" name="hunting_type_id" :value="selectedTypeId">
                                <input type="hidden" name="group_id" :value="selectedGroupId || ''">

                                @php($hasNote = \Illuminate\Support\Facades\Schema::hasColumn('group_requests','note'))
                                @if($hasNote)
                                    <div>
                                        <label class="block text-sm text-gray-300 mb-1">Ziņa vadītājam (izvēles)</label>
                                        <textarea name="note" rows="4" class="field" placeholder="Kāpēc vēlies pievienoties?"></textarea>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-emerald" :disabled="!selectedTypeId">
                                    Nosūtīt pieprasījumu
                                </button>
                            </form>
                        @else
                            <div class="mt-4 rounded-lg border border-white/10 bg-black/40 p-4">
                                Lai nosūtītu pieprasījumu, lūdzu
                                <a href="{{ route('login') }}" class="text-emerald-300 underline">pieslēdzieties</a>
                                vai <a href="{{ route('register') }}" class="text-emerald-300 underline">izveidojiet kontu</a>.
                            </div>
                        @endauth

                        <div class="mt-6" x-show="selectedTypeId">
                            <h3 class="text-lg font-bold mb-3">Pieejamās grupas</h3>
                            <ul class="space-y-3">
                                <template x-for="l in leadersForType" :key="l.id">
                                    <li class="rounded-lg border border-white/10 bg-black/40 p-3">
                                        <div class="font-semibold" x-text="l.name"></div>
                                        <ul class="ml-4 list-disc text-sm text-gray-200">
                                            <template x-for="g in groupsForLeader(l.id)" :key="g.id">
                                                <li x-text="g.name"></li>
                                            </template>
                                        </ul>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Scripts (slow pop-in like leader dashboard, plus parallax & particles) --}}
    <script>
    function contactsState({ types, groups }) {
        return {
            tab: 'contact',
            types, groups,
            selectedTypeId: null,
            selectedLeaderId: null,
            selectedGroupId: null,
            count: 0,

            init(){
                const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                // Staggered reveal — exactly like the leader dashboard (70ms steps)
                const all = document.querySelectorAll('.reveal');
                all.forEach((el, i) => setTimeout(() => el.classList.add('show'), 70 * i));

                // Also reveal groups as they enter (for long pages)
                const io = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (!entry.isIntersecting) return;
                        entry.target.querySelectorAll('.reveal').forEach((el,i) => {
                            setTimeout(() => el.classList.add('show'), 70 * i);
                        });
                        io.unobserve(entry.target);
                    });
                }, { threshold: .15 });
                document.querySelectorAll('[data-reveal-group]').forEach(g => io.observe(g));

                if (!prefersReduced) {
                    // Parallax
                    const hero = document.getElementById('hero');
                    let rafId = null, tx=0, ty=0, cx=0, cy=0;
                    window.addEventListener('mousemove', (e) => {
                        tx = (e.clientX / innerWidth - .5) * 10;
                        ty = (e.clientY / innerHeight - .5) * 10;
                        if (!rafId) tick();
                    });
                    function tick(){
                        cx += (tx - cx) * .08;
                        cy += (ty - cy) * .08;
                        hero.style.transform = `translate(${cx}px, ${cy}px)`;
                        if (Math.abs(tx-cx) > .1 || Math.abs(ty-cy) > .1) rafId = requestAnimationFrame(tick); else rafId = null;
                    }

                    // Particles
                    const canvas = document.getElementById('particles');
                    const ctx = canvas.getContext('2d');
                    const size = () => { canvas.width = innerWidth; canvas.height = innerHeight; };
                    size(); addEventListener('resize', size);
                    const dots = [];
                    for (let i=0;i<60;i++){
                        dots.push({ x:Math.random()*canvas.width, y:Math.random()*canvas.height, r:Math.random()*1.6+.4, vx:(Math.random()-.5)*.3, vy:(Math.random()-.5)*.3, a:Math.random()*.5+.25 });
                    }
                    (function step(){
                        ctx.clearRect(0,0,canvas.width,canvas.height);
                        ctx.globalCompositeOperation = 'lighter';
                        for (const d of dots){
                            d.x += d.vx; d.y += d.vy;
                            if (d.x<0||d.x>canvas.width) d.vx*=-1;
                            if (d.y<0||d.y>canvas.height) d.vy*=-1;
                            ctx.beginPath(); ctx.arc(d.x,d.y,d.r,0,Math.PI*2);
                            ctx.fillStyle = `rgba(16,185,129,${d.a})`;
                            ctx.fill();
                        }
                        requestAnimationFrame(step);
                    })();
                }
            },

            get leadersForType(){
                if (!this.selectedTypeId) return [];
                const seen = new Set(), out = [];
                for (const g of this.groups) {
                    if (g.hunting_type_id === this.selectedTypeId && g.leader) {
                        if (!seen.has(g.leader.id)) {
                            seen.add(g.leader.id);
                            out.push({ id:g.leader.id, name:g.leader.name });
                        }
                    }
                }
                if (this.selectedLeaderId && !seen.has(this.selectedLeaderId)) {
                    this.selectedLeaderId = null; this.selectedGroupId = null;
                }
                return out.sort((a,b)=> a.name.localeCompare(b.name));
            },
            get groupsFiltered(){
                if (!this.selectedTypeId || !this.selectedLeaderId) return [];
                return this.groups
                    .filter(g => g.hunting_type_id === this.selectedTypeId && g.leader_id === this.selectedLeaderId)
                    .sort((a,b)=> a.name.localeCompare(b.name));
            },
            groupsForLeader(leaderId){
                if (!this.selectedTypeId) return [];
                return this.groups
                    .filter(g => g.hunting_type_id === this.selectedTypeId && g.leader_id === leaderId)
                    .sort((a,b)=> a.name.localeCompare(b.name));
            },
        }
    }
    </script>
</x-app-layout>
