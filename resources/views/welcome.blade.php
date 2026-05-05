<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HuntLog</title>

    {{-- For production, it is better to use compiled Tailwind with @vite instead of CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            background:
                radial-gradient(circle at top, rgba(16,185,129,0.10), transparent 24%),
                radial-gradient(circle at 85% 20%, rgba(56,189,248,0.07), transparent 20%),
                linear-gradient(135deg, #04070a 0%, #071018 45%, #09131d 100%);
        }

        .bg-grid {
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 38px 38px;
        }

        .animate-fadeUp {
            animation: fadeUp .55s ease both;
        }

        .focus-outline:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(16,185,129,.35);
        }

        .header-shell {
            border-radius: 1.1rem;
            background: rgba(255,255,255,0.055);
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 12px 32px rgba(0,0,0,.18);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border-radius: 14px;
            padding: .95rem 1.35rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 10px 24px rgba(5,150,105,.25);
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(5,150,105,.32);
            background: linear-gradient(135deg, #34d399, #059669);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            padding: .95rem 1.35rem;
            font-weight: 700;
            color: white;
            background: rgba(255,255,255,.065);
            border: 1px solid rgba(255,255,255,.12);
            transition: transform .18s ease, color .18s ease, background .18s ease, border-color .18s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
            color: #6ee7b7;
            background: rgba(255,255,255,.10);
            border-color: rgba(16,185,129,.42);
        }

        .tiny-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            padding: .72rem 1rem;
            font-size: .92rem;
            font-weight: 700;
            transition: transform .16s ease, background .16s ease, color .16s ease, border-color .16s ease;
            white-space: nowrap;
        }

        .tiny-btn-primary {
            color: white;
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 8px 20px rgba(5,150,105,.18);
        }

        .tiny-btn-primary:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #34d399, #059669);
        }

        .tiny-btn-secondary {
            color: white;
            background: rgba(255,255,255,.065);
            border: 1px solid rgba(255,255,255,.12);
        }

        .tiny-btn-secondary:hover {
            color: #6ee7b7;
            background: rgba(255,255,255,.10);
            border-color: rgba(16,185,129,.42);
        }

        .hero-kicker {
            display: inline-flex;
            border-radius: 9999px;
            border: 1px solid rgba(16,185,129,.22);
            background: rgba(16,185,129,.10);
            padding: .55rem .95rem;
            font-size: 11px;
            letter-spacing: .28em;
            text-transform: uppercase;
            color: #86efac;
        }

        .hero-title {
            text-wrap: balance;
        }

        .hero-title .accent {
            color: #34d399;
        }

        .preview-shell {
            position: relative;
            overflow: hidden;
            border-radius: 2rem;
            background: linear-gradient(180deg, rgba(255,255,255,.055), rgba(255,255,255,.025));
            border: 1px solid rgba(255,255,255,.10);
            box-shadow: 0 22px 70px rgba(0,0,0,.36);
        }

        .preview-shell::before {
            content: "";
            position: absolute;
            top: -70px;
            right: -60px;
            width: 190px;
            height: 190px;
            border-radius: 9999px;
            background: radial-gradient(circle, rgba(16,185,129,.18), transparent 68%);
            pointer-events: none;
        }

        .preview-topbar {
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .hero-preview-image {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
            background: #071018;
        }

        .floating-chip {
            position: absolute;
            border-radius: 1rem;
            background: rgba(8,12,18,.88);
            border: 1px solid rgba(255,255,255,.10);
            box-shadow: 0 12px 28px rgba(0,0,0,.24);
        }

        .light-panel {
            position: relative;
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.98));
            border-top-left-radius: 2rem;
            border-top-right-radius: 2rem;
            box-shadow: 0 -16px 54px rgba(0,0,0,.14);
            content-visibility: auto;
            contain-intrinsic-size: 1200px;
        }

        .light-card {
            border-radius: 1.5rem;
            background: white;
            border: 1px solid rgba(15,23,42,.06);
            box-shadow: 0 12px 30px rgba(15,23,42,.06);
        }

        .feature-card {
            border-radius: 1.5rem;
            background: white;
            border: 1px solid rgba(15,23,42,.06);
            box-shadow: 0 10px 26px rgba(15,23,42,.055);
            padding: 1.5rem;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        @media (hover: hover) and (pointer: fine) {
            .feature-card:hover {
                transform: translateY(-3px);
                border-color: rgba(16,185,129,.22);
                box-shadow: 0 16px 36px rgba(15,23,42,.08);
            }
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            display: grid;
            place-items: center;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(16,185,129,.12), rgba(56,189,248,.10));
            color: #047857;
            font-size: 1.25rem;
            border: 1px solid rgba(16,185,129,.12);
        }

        .section-kicker-light {
            display: inline-flex;
            border-radius: 9999px;
            border: 1px solid rgba(16,185,129,.16);
            background: rgba(16,185,129,.08);
            padding: .5rem .9rem;
            font-size: 11px;
            letter-spacing: .24em;
            text-transform: uppercase;
            color: #059669;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001ms !important;
            }
        }

        @media (max-width: 640px) {
            .header-shell {
                border-radius: 1rem;
            }

            .hero-kicker,
            .section-kicker-light {
                font-size: 10px;
                letter-spacing: .18em;
                padding: .5rem .75rem;
            }

            .preview-shell {
                border-radius: 1.25rem;
                box-shadow: 0 16px 42px rgba(0,0,0,.30);
            }

            .light-panel {
                border-top-left-radius: 1.5rem;
                border-top-right-radius: 1.5rem;
            }

            .light-card,
            .feature-card {
                border-radius: 1.1rem;
            }

            .feature-card {
                padding: 1.2rem;
            }

            .floating-chip {
                display: none !important;
            }
        }
    </style>
</head>

<body class="min-h-full text-white antialiased overflow-x-hidden">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="bg-grid absolute inset-0 opacity-35"></div>
        <div class="absolute -top-20 right-[-6rem] h-80 w-80 rounded-full bg-emerald-500/10 blur-2xl"></div>
        <div class="absolute bottom-[-6rem] left-[-6rem] h-96 w-96 rounded-full bg-sky-500/10 blur-2xl"></div>
    </div>

    {{-- HEADER --}}
    <header class="relative z-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 pt-4 sm:pt-6">
            <div class="header-shell flex items-center justify-between gap-3 px-3 py-3 sm:px-5">
                <a href="{{ url('/') }}"
                   class="font-extrabold tracking-wider transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded text-emerald-400 hover:text-emerald-300 text-lg sm:text-xl shrink-0">
                    HuntLog
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="focus-outline tiny-btn tiny-btn-primary text-xs sm:text-sm px-3 sm:px-4">
                                Doties uz paneli
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="focus-outline tiny-btn tiny-btn-secondary text-xs sm:text-sm px-3 sm:px-4">
                                Pieteikties
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="focus-outline tiny-btn tiny-btn-primary text-xs sm:text-sm px-3 sm:px-4">
                                    Reģistrēties
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- HERO --}}
        <section class="relative">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 pb-16 pt-10 sm:pt-16 lg:pb-28">
                <div class="mx-auto max-w-4xl text-center animate-fadeUp">
                    <span class="hero-kicker">Jauna paaudze medību žurnālam</span>

                    <h1 class="hero-title mt-6 text-4xl font-black leading-[0.98] tracking-tight sm:mt-7 sm:text-6xl lg:text-7xl">
                        Pārvaldi medību pieredzi
                        <span class="accent">vienā skaidrā platformā</span>
                    </h1>

                    <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-gray-300 sm:mt-6 sm:text-xl sm:leading-8">
                        HuntLog apvieno karti, galeriju, ierakstus un komandas sadarbību vienotā vidē,
                        lai viss svarīgais būtu pārskatāms no pirmā acu uzmetiena.
                    </p>

                    <div class="mt-8 flex flex-col items-stretch justify-center gap-3 sm:mt-10 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4">
                        <a href="{{ route('register') }}" class="focus-outline btn-primary w-full sm:w-auto">
                            Sākt lietot
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>

                        <a href="{{ route('gallery') }}" class="focus-outline btn-secondary w-full sm:w-auto">
                            Apskatīt galeriju
                        </a>
                    </div>
                </div>

                <div class="relative mx-auto mt-10 max-w-5xl animate-fadeUp sm:mt-16" style="animation-delay:.08s;">
                    <div class="preview-shell">
                        <div class="preview-topbar flex items-center justify-between px-4 py-3 sm:px-5 sm:py-4">
                            <div class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full bg-red-400/80"></span>
                                <span class="h-3 w-3 rounded-full bg-yellow-400/80"></span>
                                <span class="h-3 w-3 rounded-full bg-emerald-400/80"></span>
                            </div>
                            <div class="rounded-full bg-white/10 px-3 py-1 text-[10px] sm:text-xs text-white/60">
                                huntlog.app
                            </div>
                        </div>

                        <img
                            src="https://cdn.outsideonline.com/wp-content/uploads/2020/10/29/hunting-for-beginners-lead_h.jpg?auto=webp&width=1600&quality=70&fit=cover"
                            alt="HuntLog preview"
                            class="hero-preview-image h-[220px] sm:h-[420px] lg:h-[560px]"
                            width="1600"
                            height="900"
                            fetchpriority="high"
                            decoding="async"
                        >
                    </div>

                    <div class="floating-chip hidden lg:block left-[-2rem] top-[4.5rem] px-4 py-3">
                        <div class="text-[10px] uppercase tracking-[0.24em] text-emerald-300/80">Live stats</div>
                        <div class="mt-1 text-lg font-black text-white">12k+ ieraksti</div>
                    </div>

                    <div class="floating-chip hidden lg:block right-[-2rem] bottom-[3.5rem] px-4 py-3">
                        <div class="text-[10px] uppercase tracking-[0.24em] text-sky-300/80">Komandas</div>
                        <div class="mt-1 text-lg font-black text-white">85+ grupas</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- LIGHT CONTENT SECTION --}}
        <section class="light-panel text-slate-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 pb-16 pt-12 sm:pb-20 sm:pt-20">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="section-kicker-light">Par platformu</span>
                    <h2 class="mt-5 text-3xl font-black tracking-tight sm:mt-6 sm:text-5xl">
                        No pierakstiem līdz skaidram pārskatam
                    </h2>
                    <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                        Šis landing page stils ir tuvāks klasiskai produktu prezentācijai:
                        tumšs hero augšā, liels preview un tīrs, gaišs saturs zem tā.
                    </p>
                </div>

                <div class="mt-12 grid items-center gap-8 lg:mt-14 lg:grid-cols-[1.05fr_.95fr] lg:gap-10">
                    <div class="light-card overflow-hidden p-3">
                        <img
                            src="https://smt-strapi-cms.s3.us-east-1.amazonaws.com/FGBLOG_hand_holding_phone_showing_private_land_boundaries_in_Hunt_Wise_1a6cf85aff.jpeg"
                            alt="HuntLog content preview"
                            class="h-[220px] w-full rounded-2xl object-cover sm:h-[360px]"
                            width="1200"
                            height="760"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>

                    <div>
                        <h3 class="text-2xl font-black tracking-tight sm:text-4xl">
                            Ātrāks veids, kā organizēt visu vienuviet
                        </h3>

                        <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                            Produkta vērtība ir daudz vieglāk uztverama, ja sākumā parādi vienu lielu,
                            skaidru galveno iespaidu, nevis pārāk daudz atsevišķu bloku.
                        </p>

                        <div class="mt-6 grid gap-4 sm:mt-8 sm:grid-cols-2">
                            <div class="feature-card">
                                <div class="feature-icon">🗺️</div>
                                <h4 class="mt-4 text-lg font-black">Interaktīva karte</h4>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
                                    Pārvaldi maršrutus, punktus un novērojumus pārskatāmā vidē.
                                </p>
                            </div>

                            <div class="feature-card">
                                <div class="feature-icon">🤝</div>
                                <h4 class="mt-4 text-lg font-black">Komandas darbs</h4>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
                                    Sadarbojies ar grupu un turi visu svarīgo vienā plūsmā.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 grid gap-4 sm:mt-16 md:grid-cols-3 md:gap-6">
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h4 class="mt-4 text-lg font-black">Aktivitāšu pārskats</h4>
                        <p class="mt-2 text-sm leading-7 text-slate-600">
                            Sekojiet līdzi ierakstiem, plāniem un galvenajiem rādītājiem vienā vietā.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📸</div>
                        <h4 class="mt-4 text-lg font-black">Galerija</h4>
                        <p class="mt-2 text-sm leading-7 text-slate-600">
                            Saglabā momentus elegantā, vizuāli tīrā un viegli uztveramā skatā.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h4 class="mt-4 text-lg font-black">Droša vide</h4>
                        <p class="mt-2 text-sm leading-7 text-slate-600">
                            Profesionāls izskats un uzticama vide rada daudz pārliecinošāku sajūtu.
                        </p>
                    </div>
                </div>

                <div class="mt-12 overflow-hidden rounded-[1.5rem] border border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-sky-50 px-5 py-10 shadow-[0_18px_48px_rgba(15,23,42,0.07)] sm:mt-16 sm:rounded-[2rem] sm:px-8 sm:py-14">
                    <div class="max-w-3xl">
                        <span class="section-kicker-light">Gatavs sākt?</span>
                        <h3 class="mt-5 text-3xl font-black tracking-tight sm:mt-6 sm:text-5xl">
                            Izmanto HuntLog kā modernu bāzi savai medību pieredzei
                        </h3>
                        <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                            Reģistrējies, pievieno savu komandu un sāc izmantot vienotu, pārdomātu vidi ikdienas darbam.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:gap-4">
                            <a href="{{ route('register') }}" class="focus-outline btn-primary w-full sm:w-auto">
                                Reģistrēties
                            </a>
                            <a href="{{ route('login') }}" class="focus-outline btn-secondary w-full sm:w-auto !text-slate-800 !border-slate-200 !bg-white hover:!text-emerald-700 hover:!border-emerald-200">
                                Pieteikties
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-white/10 bg-black/30">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 py-8 text-center text-sm text-gray-400 sm:px-6 md:flex-row md:text-left">
            <span>&copy; {{ date('Y') }} HuntLog. Visas tiesības aizsargātas.</span>
            <div class="flex items-center gap-5">
                <a href="{{ route('contacts') }}" class="transition hover:text-emerald-400">Kontakti</a>
                <a href="{{ route('gallery') }}" class="transition hover:text-emerald-400">Galerija</a>
            </div>
        </div>
    </footer>
</body>
</html>