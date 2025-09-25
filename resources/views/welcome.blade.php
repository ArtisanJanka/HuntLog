<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sveicināti</title>

    {{-- Ātrais Tailwind (ja izmanto Vite/Mix, šo var noņemt) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Fona animēts gradients */
        @keyframes floatGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        /* Fade-in augšup */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp .8s ease both; animation-delay: .15s; }

        /* Labāks fokusa outlines */
        .focus-outline:focus { outline: none; box-shadow: 0 0 0 3px rgba(16,185,129,.45); }
    </style>
</head>
<body class="h-full antialiased text-gray-900 dark:text-gray-100
             bg-gradient-to-br from-emerald-600 via-sky-700 to-gray-900
             [background-size:200%_200%] animate-[floatGradient_14s_ease_infinite]
             dark:from-gray-900 dark:via-slate-900 dark:to-emerald-900">

    <main class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-3xl w-full">
            <!-- Kartīte -->
            <section
                class="animate-fadeInUp relative overflow-hidden rounded-3xl
                       bg-white/10 backdrop-blur-xl shadow-2xl ring-1 ring-white/20
                       dark:bg-black/20">
                <!-- Dekoratīva virsējā gaisma -->
                <div class="absolute inset-x-0 -top-20 h-48 blur-3xl opacity-60 pointer-events-none"
                     style="background: radial-gradient(40rem 18rem at top, rgba(16,185,129,.35), transparent 60%);">
                </div>

                <div class="relative px-8 sm:px-12 py-12">
                    <!-- Logo / zīmols -->
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500/90 grid place-items-center
                                    text-white font-bold shadow-lg">H</div>
                        <div class="leading-tight">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Sveicināti</h1>
                            <p class="text-sm text-gray-200/80">Prieks, ka esat šeit.</p>
                        </div>
                    </div>

                    <!-- Virsraksts -->
                    <div class="mb-8">
                        <h2 class="text-3xl sm:text-4xl font-black leading-tight">
                            Pieraksti. Dalies. <span class="text-emerald-300">Izbaudi dabu.</span>
                        </h2>
                        <p class="mt-3 text-gray-100/90 max-w-2xl">
                            Vienkārši rīki, lai atzīmētu savus piedzīvojumus un sazinātos ar draugiem.
                            Ātri, droši un ērti — uz jebkuras ierīces.
                        </p>
                    </div>

                    <!-- Pogas -->
                    <div class="mt-8 flex flex-wrap gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="focus-outline inline-flex items-center justify-center rounded-xl
                                          bg-emerald-500 px-5 py-3 text-white font-semibold shadow-lg shadow-emerald-900/30
                                          hover:bg-emerald-600 active:translate-y-[1px] transition">
                                    Doties uz paneli
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="focus-outline inline-flex items-center justify-center rounded-xl
                                          bg-emerald-500 px-5 py-3 text-white font-semibold shadow-lg shadow-emerald-900/30
                                          hover:bg-emerald-600 active:translate-y-[1px] transition">
                                    Pieteikties
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="focus-outline inline-flex items-center justify-center rounded-xl
                                              bg-white/10 px-5 py-3 text-white font-semibold ring-1 ring-white/20
                                              hover:bg-white/20 active:translate-y-[1px] transition">
                                        Reģistrēties
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Funkciju saraksts -->
                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl bg-black/20 ring-1 ring-white/10 p-4 hover:bg-black/25 transition">
                            <div class="text-emerald-300 font-semibold">Ātrs un viegls</div>
                            <p class="text-sm text-gray-200/80 mt-1">Bez liekiem sarežģījumiem.</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 ring-1 ring-white/10 p-4 hover:bg-black/25 transition">
                            <div class="text-emerald-300 font-semibold">Privātums pirmajā vietā</div>
                            <p class="text-sm text-gray-200/80 mt-1">Jūsu dati paliek pie jums.</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 ring-1 ring-white/10 p-4 hover:bg-black/25 transition">
                            <div class="text-emerald-300 font-semibold">Komandām</div>
                            <p class="text-sm text-gray-200/80 mt-1">Uzaicini un sadarbojies uzreiz.</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 flex items-center justify-between text-xs text-gray-300/80">
                        <span>&copy; {{ date('Y') }} HuntLog</span>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
