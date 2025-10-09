<x-app-layout>
    {{-- Fons + dūmaka --}}
    <section class="relative min-h-screen pt-20 sm:pt-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-gray-900 to-black"></div>
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="fog fog-1"></div>
            <div class="fog fog-2"></div>
            <div class="fog fog-3"></div>
        </div>

        <div class="relative z-10 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" data-reveal-group>
            {{-- Galvene --}}
            <header class="reveal mb-6">
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Izveidot medību tipu</h1>
                <p class="mt-2 text-gray-300">Nosaki nosaukumu un (ja vēlies) savu <span class="text-emerald-300">slug</span>.</p>
            </header>

            {{-- Karte / forma --}}
            <section class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.hunting-types.store') }}" class="space-y-6" id="typeForm">
                    @csrf

                    {{-- Nosaukums --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white mb-1.5">Nosaukums</label>
                        <input id="name" name="name" value="{{ old('name') }}"
                               class="w-full bg-gray-800/80 text-white rounded-lg p-3 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                               placeholder="Piem., Dzīšana, Vakari, Pēdas" required>
                        @error('name')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slugs (nav obligāts) --}}
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label for="slug" class="block text-sm font-semibold text-white">Slug <span class="text-gray-400 font-normal">(nav obligāts)</span></label>
                            <label class="flex items-center gap-2 text-sm text-gray-300 select-none">
                                <input id="autoSlug" type="checkbox" class="rounded border-gray-600 text-emerald-600 focus:ring-emerald-500" checked>
                                Automātiski ģenerēt
                            </label>
                        </div>

                        <input id="slug" name="slug" value="{{ old('slug') }}"
                               class="mt-1.5 w-full bg-gray-800/80 text-white rounded-lg p-3 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                               placeholder="Piem., dzisana, vakari, pedas">
                        @error('slug')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        {{-- Priekšskatījums --}}
                        <div class="mt-2 text-sm text-gray-300">
                            Priekšskatījums:
                            <span id="slugPreview" class="inline-flex items-center px-2 py-0.5 rounded-md border border-white/10 bg-white/10 text-emerald-300 font-semibold">
                                —
                            </span>
                        </div>
                    </div>

                    {{-- Darbības --}}
                    <div class="pt-2 flex flex-col-reverse sm:flex-row justify-end gap-2">
                        <a href="{{ route('admin.hunting-types.index') }}"
                           class="inline-flex justify-center px-4 py-2 rounded-lg bg-white/10 text-white border border-white/10 hover:border-gray-400/50 hover:bg-white/15 transition">
                            Atcelt
                        </a>
                        <button class="inline-flex justify-center px-4 py-2 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 shadow-lg shadow-emerald-900/30 focus:ring-2 focus:ring-emerald-500 transition">
                            Saglabāt
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </section>

    {{-- Script: slug ģenerēšana + reveal --}}
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

 
        const map = {
            'ā':'a','č':'c','ē':'e','ģ':'g','ī':'i','ķ':'k','ļ':'l','ņ':'n','š':'s','ū':'u','ž':'z',
            'Ā':'a','Č':'c','Ē':'e','Ģ':'g','Ī':'i','Ķ':'k','Ļ':'l','Ņ':'n','Š':'s','Ū':'u','Ž':'z',
            'ö':'o','Ö':'o','ä':'a','Ä':'a','ü':'u','Ü':'u','õ':'o','Õ':'o'
        };
        const translit = s => s.replace(/./g, ch => map[ch] ?? ch);

        function slugify(s){
            s = translit(s).toLowerCase();
            s = s.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            s = s.replace(/[^a-z0-9\s-]/g, '');
            s = s.trim().replace(/\s+/g, '-'); 
            s = s.replace(/-+/g, '-');          
            return s;
        }

        const nameEl = document.getElementById('name');
        const slugEl = document.getElementById('slug');
        const autoEl = document.getElementById('autoSlug');
        const prevEl = document.getElementById('slugPreview');

        function updatePreview(){
            const v = (slugEl.value || '').trim();
            prevEl.textContent = v || '—';
        }


        let userTouchedSlug = false;

        nameEl?.addEventListener('input', () => {
            if (autoEl.checked && !userTouchedSlug) {
                slugEl.value = slugify(nameEl.value);
                updatePreview();
            }
        });

        slugEl?.addEventListener('input', () => {
            userTouchedSlug = true;
            updatePreview();
        });

        autoEl?.addEventListener('change', () => {
            if (autoEl.checked) {
                userTouchedSlug = false;
                slugEl.value = slugify(nameEl.value || '');
                updatePreview();
            }
        });

        updatePreview();
        if (autoEl.checked && !slugEl.value) {
            slugEl.value = slugify(nameEl.value || '');
            updatePreview();
        }
    })();
    </script>

    {{-- Stili: dūmaka + reveal --}}
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
    </style>
</x-app-layout>
