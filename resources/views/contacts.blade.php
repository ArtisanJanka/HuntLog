<x-app-layout>
    {{-- Page-specific styles --}}
    <style>
        /* Ken Burns slow zoom on hero bg */
        @keyframes kenBurns {
            0% { transform: scale(1) translateZ(0); }
            100% { transform: scale(1.08) translateZ(0); }
        }

        /* Floating fog orbs */
        @keyframes fogDrift {
            0% { transform: translate(0,0) scale(1); opacity:.25; }
            50% { transform: translate(60px,-40px) scale(1.12); opacity:.35; }
            100% { transform: translate(0,0) scale(1); opacity:.25; }
        }

        /* Reveal in */
        @keyframes fadeUp {
            from { opacity:0; transform: translateY(14px); }
            to   { opacity:1; transform: none; }
        }
        .reveal { opacity:0; transform: translateY(14px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.show { opacity:1; transform: none; }

        /* Animated gradient border ring around the form (mask trick) */
        .ring-animated {
            position: relative;
            border-radius: 1.25rem; /* 2xl */
            isolation: isolate;
        }
        .ring-animated::before {
            content:"";
            position: absolute; inset:-1px; border-radius: inherit; z-index:-1;
            background: conic-gradient(
                from 0deg,
                #10b981, #22d3ee, #a78bfa, #10b981
            );
            animation: spin 8s linear infinite;
            filter: blur(8px); opacity: .45;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Particles canvas sits behind content */
        #particles {
            position: fixed; inset:0; z-index: 0; pointer-events: none;
        }

        /* Respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .parallax, .fog, .ring-animated::before { animation: none !important; }
        }
    </style>

    <section class="relative min-h-[92vh] sm:min-h-screen overflow-hidden text-gray-100">
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
            <div class="fog absolute top-[8%] -left-[10%] w-[42vw] h-[42vw] min-w-[320px] min-h-[320px] rounded-full"
                 style="background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 60%); filter: blur(60px); animation: fogDrift 36s ease-in-out infinite;"></div>
            <div class="fog absolute bottom-[-12%] right-[-10%] w-[40vw] h-[40vw] min-w-[320px] min-h-[320px] rounded-full"
                 style="background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 60%); filter: blur(60px); animation: fogDrift 42s ease-in-out infinite;"></div>
            <div class="fog absolute top-[40%] right-[20%] w-[34vw] h-[34vw] min-w-[280px] min-h-[280px] rounded-full opacity-70"
                 style="background: radial-gradient(circle, rgba(16,185,129,.12) 0%, transparent 60%); filter: blur(70px); animation: fogDrift 38s ease-in-out infinite;"></div>
        </div>

        {{-- Particles (subtle) --}}
        <canvas id="particles" aria-hidden="true"></canvas>

        <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 py-20 sm:py-28">
            {{-- Headline + quick contacts --}}
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                <div data-reveal-group>
                    <h1 class="reveal text-4xl sm:text-5xl font-black tracking-tight text-white">Sazinies ar <span class="text-emerald-400">HuntLog</span></h1>
                    <p class="reveal mt-4 text-lg text-gray-200/90 max-w-xl">
                        Vai tā ir ideja, jautājums vai atbalsts — raksti mums.
                        Mēs atbildēsim **tiklīdz iespējams**.
                    </p>

                    <div class="reveal mt-8 grid sm:grid-cols-2 gap-4">
                        {{-- Phone --}}
                        <a href="tel:29490737"
                           class="group rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 hover:border-emerald-400/60 hover:bg-white/10 transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.69l1.52 4.55a1 1 0 01-.5 1.2l-2.26 1.13a11.05 11.05 0 005.52 5.52l1.13-2.26a1 1 0 011.2-.5l4.55 1.52a1 1 0 01.69.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-sm text-gray-400">Tālrunis</div>
                                    <div class="text-white font-semibold tracking-wide group-hover:text-emerald-300 transition">29490737</div>
                                </div>
                            </div>
                        </a>

                        {{-- Email --}}
                        <a href="mailto:huntlogs@gmail.com"
                           class="group rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 hover:border-emerald-400/60 hover:bg-white/10 transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.5 5L18 8m-15 8h18V6H3v10z"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-sm text-gray-400">E-pasts</div>
                                    <div class="text-white font-semibold tracking-wide group-hover:text-emerald-300 transition">huntlogs@gmail.com</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Form --}}
                <div class="reveal lg:ml-auto w-full max-w-xl ring-animated">
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-6 sm:p-8 shadow-2xl">
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
                                <input id="name" name="name" type="text" required autocomplete="name"
                                       class="w-full bg-gray-900/70 border border-gray-700 text-white rounded-lg px-3 py-2.5 placeholder-gray-400 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                       placeholder="Jūsu vārds">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-white mb-1">E-pasts</label>
                                <input id="email" name="email" type="email" required autocomplete="email"
                                       class="w-full bg-gray-900/70 border border-gray-700 text-white rounded-lg px-3 py-2.5 placeholder-gray-400 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                       placeholder="you@example.com">
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-semibold text-white mb-1">Ziņa</label>
                                <textarea id="message" name="message" rows="5" required
                                          class="w-full bg-gray-900/70 border border-gray-700 text-white rounded-lg px-3 py-2.5 placeholder-gray-400 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                          placeholder="Ko mēs varam palīdzēt?"></textarea>
                                <div class="mt-1 text-xs text-gray-400"><span id="charCount">0</span>/1000</div>
                            </div>

                            <div class="pt-1 text-center">
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 px-7 py-3 rounded-lg font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 shadow-lg shadow-emerald-900/30 transition">
                                    <svg class="h-5 w-5 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Sūtīt ziņu
                                </button>
                            </div>
                        </form>

                        <p class="mt-4 text-center text-xs text-gray-400">Atbildam parasti 24h laikā.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Scripts --}}
    <script>
    (function(){
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Reveal on scroll
        const groupObserver = new IntersectionObserver((entries)=>{
            entries.forEach(entry=>{
                if (!entry.isIntersecting) return;
                const items = entry.target.querySelectorAll('.reveal');
                items.forEach((el,i)=>{ el.style.transitionDelay = (i*110)+'ms'; el.classList.add('show'); });
                groupObserver.unobserve(entry.target);
            });
        }, { threshold: .15 });
        document.querySelectorAll('[data-reveal-group]').forEach(g=> groupObserver.observe(g));
        // For standalone .reveal outside groups
        const singles = document.querySelectorAll('.reveal:not([data-reveal-group] .reveal)');
        const singleObs = new IntersectionObserver((entries)=>{
            entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('show'); singleObs.unobserve(e.target);} });
        }, {threshold:.18});
        singles.forEach(el=> singleObs.observe(el));

        // Parallax (hero image slight move on mouse)
        if (!prefersReduced) {
            const hero = document.getElementById('hero');
            let rafId = null, targetX = 0, targetY = 0, curX = 0, curY = 0;

            const onMove = (e) => {
                const x = (e.clientX / innerWidth - 0.5) * 10; // max ~5px each side
                const y = (e.clientY / innerHeight - 0.5) * 10;
                targetX = x; targetY = y;
                if (!rafId) tick();
            };
            function tick(){
                curX += (targetX - curX) * 0.08;
                curY += (targetY - curY) * 0.08;
                hero.style.transform = `translate(${curX}px, ${curY}px)`;
                if (Math.abs(targetX-curX) > 0.1 || Math.abs(targetY-curY) > 0.1) {
                    rafId = requestAnimationFrame(tick);
                } else { rafId = null; }
            }
            window.addEventListener('mousemove', onMove);
        }

        // Particles (very light)
        const canvas = document.getElementById('particles');
        const ctx = canvas.getContext('2d');
        function size(){ canvas.width = innerWidth; canvas.height = innerHeight; }
        size(); addEventListener('resize', size);

        const dots = [];
        const DOTS = 60;
        for (let i=0;i<DOTS;i++){
            dots.push({
                x: Math.random()*canvas.width,
                y: Math.random()*canvas.height,
                r: Math.random()*1.6 + .4,
                vx: (Math.random()-.5)*.3,
                vy: (Math.random()-.5)*.3,
                a: Math.random()*.5 + .25
            });
        }
        function step(){
            ctx.clearRect(0,0,canvas.width,canvas.height);
            ctx.globalCompositeOperation = 'lighter';
            for (const d of dots){
                d.x += d.vx; d.y += d.vy;
                if (d.x < 0 || d.x > canvas.width) d.vx*=-1;
                if (d.y < 0 || d.y > canvas.height) d.vy*=-1;
                ctx.beginPath();
                ctx.arc(d.x,d.y,d.r,0,Math.PI*2);
                ctx.fillStyle = `rgba(16,185,129,${d.a})`; // emerald
                ctx.fill();
            }
            requestAnimationFrame(step);
        }
        if (!prefersReduced) step();

        // Character counter (soft cap 1000)
        const message = document.getElementById('message');
        const counter = document.getElementById('charCount');
        if (message && counter){
            const update = ()=> { counter.textContent = Math.min(message.value.length, 1000); };
            message.addEventListener('input', update); update();
        }
    })();
    </script>
</x-app-layout>
