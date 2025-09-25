<x-guest-layout>
    <style>
        /* Dark animated gradient */
        @keyframes floatGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        html::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -9999;
            background: linear-gradient(135deg, #0f172a, #1e293b, #111827); /* darker slate/gray tones */
            background-size: 200% 200%;
            animation: floatGradient 20s ease infinite;
        }

        /* Kill all white wrappers Breeze adds */
        body, .bg-white, .bg-gray-100 {
            background: transparent !important;
        }

        /* Fade in */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp .8s ease both; }
    </style>

    {{-- Shooting game behind everything --}}
    <canvas id="shooting-game" class="fixed inset-0 -z-10 pointer-events-none"></canvas>

    {{-- Centered form --}}
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 text-gray-100">
        <div class="w-full max-w-md animate-fadeInUp">
            <div class="bg-gray-900/95 backdrop-blur-xl border border-gray-800 rounded-2xl shadow-2xl p-6 sm:p-8">
                <h1 class="mb-6 text-4xl font-extrabold text-center text-emerald-300 drop-shadow">
                    HuntLog
                </h1>
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Vārds')" class="text-white font-medium" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                                    class="mt-1 block w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-lg
                                            focus:border-emerald-500 focus:ring-emerald-500 transition hover:border-emerald-400" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('E-pasts')" class="text-white font-medium" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                                    class="mt-1 block w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-lg
                                            focus:border-emerald-500 focus:ring-emerald-500 transition hover:border-emerald-400" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Parole')" class="text-white font-medium" />
                        <x-text-input id="password" type="password" name="password" required autocomplete="new-password"
                                    class="mt-1 block w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-lg
                                            focus:border-emerald-500 focus:ring-emerald-500 transition hover:border-emerald-400" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Apstiprini paroli')" class="text-white font-medium" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                    class="mt-1 block w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-lg
                                            focus:border-emerald-500 focus:ring-emerald-500 transition hover:border-emerald-400" />
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <a class="text-sm underline text-gray-400 hover:text-emerald-400 transition" href="{{ route('login') }}">
                            {{ __('Jau esi reģistrējies?') }}
                        </a>
                        <x-primary-button
                            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-semibold shadow-lg shadow-emerald-900/30 focus:ring-emerald-500 transition">
                            {{ __('Reģistrēties') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Shooting game (scoped) --}}
    <script>
    (() => {
        const canvas = document.getElementById("shooting-game");
        const ctx = canvas.getContext("2d");
        const setSize = () => { canvas.width = innerWidth; canvas.height = innerHeight; };
        setSize(); addEventListener("resize", setSize);

        const targets = [], bullets = [];
        const crosshair = { x: canvas.width/2, y: canvas.height/2 };

        function drawTarget(x, y, r) {
            const colors = ["red","white","black","white","red"];
            const w = r / colors.length;
            for (let i = colors.length - 1; i >= 0; i--) {
                ctx.beginPath(); ctx.arc(x, y, w * (i + 1), 0, Math.PI * 2);
                ctx.fillStyle = colors[i]; ctx.fill();
            }
        }
        function drawCrosshair(x, y) {
            ctx.strokeStyle = "white"; ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(x - 15, y); ctx.lineTo(x + 15, y);
            ctx.moveTo(x, y - 15); ctx.lineTo(x, y + 15);
            ctx.stroke();
        }

        class Target {
            constructor(x, y, r, s){ this.x=x; this.y=y; this.r=r; this.s=s; }
            update(){ this.y += this.s; if (this.y > canvas.height + this.r){ this.y = -this.r; this.x = Math.random()*canvas.width; } this.draw(); }
            draw(){ drawTarget(this.x, this.y, this.r); }
        }
        class Bullet {
            constructor(x, y){ this.x=x; this.y=y; this.r=5; this.s=7; }
            update(){ this.y -= this.s; this.draw(); }
            draw(){ ctx.beginPath(); ctx.arc(this.x,this.y,this.r,0,Math.PI*2); ctx.fillStyle="yellow"; ctx.fill(); }
        }

        for (let i=0;i<6;i++){
            targets.push(new Target(Math.random()*canvas.width, Math.random()*canvas.height, 20, 1+Math.random()*2));
        }

        addEventListener("click", e => bullets.push(new Bullet(e.clientX,e.clientY)));
        addEventListener("mousemove", e => { crosshair.x = e.clientX; crosshair.y = e.clientY; });

        const animate = () => {
            ctx.clearRect(0,0,canvas.width,canvas.height);

            // Update targets & bullets
            for (let i=targets.length-1;i>=0;i--) targets[i].update();
            for (let j=bullets.length-1;j>=0;j--){ bullets[j].update(); if (bullets[j].y + bullets[j].r < 0) bullets.splice(j,1); }

            // Collisions
            for (let i=targets.length-1;i>=0;i--){
                const t = targets[i];
                for (let j=bullets.length-1;j>=0;j--){
                    const b = bullets[j];
                    const dx=b.x-t.x, dy=b.y-t.y;
                    if (dx*dx + dy*dy < t.r*t.r){
                        targets.splice(i,1);
                        bullets.splice(j,1);
                        targets.push(new Target(Math.random()*canvas.width, -20, 20, 1+Math.random()*2));
                        break;
                    }
                }
            }

            drawCrosshair(crosshair.x, crosshair.y);
            requestAnimationFrame(animate);
        };
        animate();
    })();
    </script>
</x-guest-layout>
