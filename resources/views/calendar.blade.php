<x-app-layout>
    @php
        $months = range(1, 12);
        $monthNames = ['Janvāris','Februāris','Marts','Aprīlis','Maijs','Jūnijs','Jūlijs','Augusts','Septembris','Oktobris','Novembris','Decembris'];

        $animals = [
            ['name'=>'Sarkanais briedis','type'=>'brieži','months'=>[8,9,10,11,12,1]],
            ['name'=>'Staltbrieži','type'=>'brieži','months'=>[6,7,8,9,10,11]],
            ['name'=>'Alnis','type'=>'brieži','months'=>[9,10,11,12]],
            ['name'=>'Mežacūka','type'=>'brieži','months'=>range(1,12)],
            ['name'=>'Lūsis','type'=>'plēsēji','months'=>[]],
            ['name'=>'Brūnais lācis','type'=>'plēsēji','months'=>[8,9,10]],
            ['name'=>'Ūdensputni','type'=>'putni','months'=>[8,9,10,11,12,1]],
        ];

        $colors = [
            'brieži'  => 'bg-emerald-600',
            'plēsēji' => 'bg-amber-600',
            'putni'   => 'bg-sky-600',
        ];

        $icons = [
            'default' => '🦌',
            'boar'    => '🐗',
            'bear'    => '🐻',
            'duck'    => '🦆',
        ];

        $currentMonthIndex = (int)date('n') - 1;
        $currentDay   = (int)date('j');
        $currentMonth = (int)date('n');
        $currentYear  = (int)date('Y');
    @endphp

    <style>
        /* 1) Neutralize layout backgrounds on this page only */
        html, body, .min-h-screen, main { background: transparent !important; }

        /* 2) Make the calendar container own the stacking and paint its bg above page bg */
        .hunt-bg { position: relative; z-index: 0; }
        .hunt-bg > .hunt-content { position: relative; z-index: 2; } /* content above the background layers */

        /* Background image + vignette (NO filter) */
        .hunt-bg::before {
            content:"";
            position: fixed; inset: 0;
            z-index: 1; /* above the page bg, below .hunt-content */
            background:
              linear-gradient(180deg, rgba(0,0,0,.35), rgba(0,0,0,.78)),
              url('https://images.squarespace-cdn.com/content/v1/5ddd707034a3a5066151f221/d90be072-e7ed-47e7-8828-91c87f49e9df/laura-college-K_Na5gCmh38-unsplash.jpg')
                center/cover no-repeat;
            pointer-events: none;
        }
        .hunt-bg::after {
            content:"";
            position: fixed; inset: 0;
            z-index: 1;
            background:
              radial-gradient(1400px 620px at 50% 110%, rgba(16,185,129,.12), transparent 70%),
              radial-gradient(1200px 600px at 20% 10%, rgba(255,255,255,.05), transparent 60%);
            pointer-events: none;
        }

        /* Optional decorative silhouette (kept subtle) */
        .wild-art {
            position: fixed; right: -4%; bottom: -2%;
            width: 40vw; max-width: 640px; min-width: 260px;
            opacity: .12; filter: grayscale(1) contrast(1.05);
            z-index: 1; pointer-events: none; user-select: none;
        }

        /* Reveal animation */
        @keyframes fadeUp { from {opacity:0; transform:translateY(12px)} to {opacity:1; transform:none} }
        .reveal { opacity:0; transform:translateY(12px); }
        .reveal.show { animation: fadeUp .6s ease forwards; }

        /* Month chips */
        .month-chip { transition: all .2s ease; }
        .month-chip.active { background: rgba(16,185,129,.15); border-color: rgba(16,185,129,.5); color:#d1fae5; }

        /* Day cell */
        .day-cell { transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease, border-color .15s ease; }
        .day-cell:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,.35); }
        .today-ring { box-shadow: 0 0 0 2px rgba(16,185,129,.8) inset, 0 0 0 1px rgba(16,185,129,.35); }
        .weekend { background-image: linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01)); }

        /* Legend dots */
        .dot { width:.6rem; height:.6rem; border-radius:9999px; display:inline-block; }

        /* Tooltip (pure CSS) */
        .tip { position: relative; }
        .tip .tip-box {
            position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%);
            background: rgba(17,24,39,.92); color: #e5e7eb; border: 1px solid rgba(255,255,255,.08);
            padding: .45rem .6rem; border-radius: .5rem; white-space: nowrap;
            font-size: .75rem; line-height: 1rem; opacity: 0; pointer-events: none;
            transition: opacity .15s ease, transform .15s ease;
        }
        .tip .tip-box::after {
            content: ""; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            border: 6px solid transparent; border-top-color: rgba(17,24,39,.92);
        }
        .tip:hover .tip-box { opacity: 1; transform: translate(-50%, -2px); }

        .no-scrollbar { scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        @media (prefers-reduced-motion: reduce) {
            .reveal.show { animation: none !important; opacity:1 !important; transform:none !important; }
        }
    </style>

    <div 
        x-data="huntCalendar({ initial: {{ $currentMonthIndex }} })"
        x-init="init()"
        @keydown.window.left.prevent="prev()"
        @keydown.window.right.prevent="next()"
        class="hunt-bg relative w-full min-h-screen text-gray-100 overflow-hidden"
    >
        {{-- Optional silhouette --}}
        <img class="wild-art" alt="Sarkanais briedis" src="https://upload.wikimedia.org/wikipedia/commons/3/3f/Red_deer_stag_2019.jpg" />

        <div class="hunt-content max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
            {{-- Header / Controls --}}
            <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight">Medību kalendārs</h1>
                    <p class="text-gray-300 mt-1">Pārslēdz mēnešus, skaties ieteicamos dzīvniekus pēc sezonas.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="today()" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15 transition">
                        Šodien
                    </button>
                    <div class="flex gap-2">
                        <button @click="prev()" class="h-10 w-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center hover:bg-white/15 transition" aria-label="Iepriekšējais">
                            ‹
                        </button>
                        <button @click="next()" class="h-10 w-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center hover:bg-white/15 transition" aria-label="Nākamais">
                            ›
                        </button>
                    </div>
                </div>
            </div>

            {{-- Month chips --}}
            <div class="reveal mt-5">
                <div class="flex gap-2 overflow-x-auto no-scrollbar py-2 -mx-1 px-1">
                    @foreach($months as $index => $month)
                        <button
                            @click="monthIndex={{ $index }}"
                            :class="monthIndex === {{ $index }} ? 'active' : ''"
                            class="month-chip whitespace-nowrap px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-gray-200 hover:bg-white/10"
                        >
                            {{ $monthNames[$index] }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Swipe area --}}
            <div 
                class="mt-6 sm:mt-8"
                x-on:touchstart.passive="touchStart($event)"
                x-on:touchend.passive="touchEnd($event)"
            >
                {{-- Month sections --}}
                @foreach($months as $index => $month)
                    @php
                        $year = $currentYear;
                        $daysInMonth  = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $firstWeekday = (int)date('N', strtotime("$year-$month-01")); // 1..7 (Mon..Sun)
                        $leading = $firstWeekday - 1;
                    @endphp

                    <section
                        x-show="monthIndex === {{ $index }}"
                        x-transition
                        class="reveal w-full"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-2xl sm:text-3xl font-bold">
                                {{ $monthNames[$index] }} {{ $year }}
                            </h2>
                            <div class="hidden sm:flex items-center gap-4 text-sm">
                                <span><span class="dot bg-emerald-600"></span> Brieži</span>
                                <span><span class="dot bg-amber-600"></span> Plēsēji</span>
                                <span><span class="dot bg-sky-600"></span> Putni</span>
                            </div>
                        </div>

                        {{-- Calendar --}}
                        <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm shadow-2xl p-3 sm:p-4">
                            {{-- Week headers --}}
                            <div class="grid grid-cols-7 gap-1 sm:gap-2 text-center font-semibold text-gray-300 mb-2">
                                <div>P</div><div>O</div><div>T</div><div>C</div><div>P</div><div>S</div><div>S</div>
                            </div>

                            {{-- Days grid --}}
                            <div class="grid grid-cols-7 gap-1 sm:gap-2">
                                {{-- Leading blanks --}}
                                @for ($i = 0; $i < $leading; $i++)
                                    <div class="h-12 sm:h-16 md:h-20 rounded bg-transparent"></div>
                                @endfor

                                {{-- Real days --}}
                                @for($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $w = (int)date('N', strtotime("$year-$month-$day"));
                                        $isWeekend = $w >= 6;
                                        $isToday = ($year === $currentYear && $month === $currentMonth && $day === $currentDay);
                                    @endphp
                                    <div
                                        class="day-cell h-12 sm:h-16 md:h-20 rounded border border-white/10 {{ $isWeekend ? 'weekend bg-white/[.04]' : 'bg-white/[.03]' }} {{ $isToday ? 'today-ring bg-emerald-600/10' : '' }} flex items-center justify-center font-semibold text-sm sm:text-base"
                                        title="{{ $day }}. {{ $monthNames[$index] }} {{ $year }}"
                                    >
                                        {{ $day }}
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Animals --}}
                        <div class="mt-5">
                            <h3 class="font-semibold mb-2 text-lg">Medību dzīvnieki:</h3>
                            <div class="flex flex-wrap gap-2 min-w-[250px]">
                                @php $hasAnimals = false; @endphp
                                @foreach($animals as $animal)
                                    @php
                                        $isOpen = in_array($month, $animal['months']);
                                        $hasAnimals = $hasAnimals || $isOpen;

                                        $seasonText = '';
                                        if (!empty($animal['months'])) {
                                            $names = array_map(fn($m) => $monthNames[$m-1], $animal['months']);
                                            $seasonText = implode(', ', $names);
                                        } else {
                                            $seasonText = 'Tikai ar īpašām atļaujām';
                                        }

                                        $icon = $icons['default'];
                                        if (str_contains($animal['name'], 'Mežacūka')) $icon = $icons['boar'];
                                        elseif (str_contains($animal['name'], 'lācis') || str_contains($animal['name'], 'Lācis')) $icon = $icons['bear'];
                                        elseif (str_contains($animal['name'], 'Ūdensputni')) $icon = $icons['duck'];
                                    @endphp

                                    @if($isOpen)
                                        <span class="tip inline-flex items-center gap-2 px-3 py-1 text-sm rounded text-white shadow whitespace-nowrap border border-white/10 {{ $colors[$animal['type']] }} hover:brightness-110 transition">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-black/20">{{ $icon }}</span>
                                            <span>{{ $animal['name'] }}</span>
                                            <span class="tip-box">{{ $seasonText }}</span>
                                        </span>
                                    @endif
                                @endforeach

                                @unless($hasAnimals)
                                    <p class="text-gray-300/90 italic text-sm">Nav medību šajā mēnesī</p>
                                @endunless
                            </div>

                            <p class="mt-3 text-xs text-gray-300/80">
                                <strong>Lūsis</strong> – medības tikai ar atļaujām.
                            </p>
                        </div>
                    </section>
                @endforeach
            </div>

            {{-- Controls (bottom on mobile) --}}
            <div class="reveal flex justify-between w-full mt-6">
                <button
                    @click="prev()"
                    class="bg-white/10 hover:bg-white/15 text-white px-4 py-2 rounded-full shadow border border-white/10"
                >
                    ‹ Iepriekšējais
                </button>
                <button
                    @click="next()"
                    class="bg-white/10 hover:bg-white/15 text-white px-4 py-2 rounded-full shadow border border-white/10"
                >
                    Nākamais ›
                </button>
            </div>
        </div>
    </div>

    <script>
        function huntCalendar({ initial = 0 }) {
            return {
                monthIndex: initial,
                init() {
                    const reveals = document.querySelectorAll('.reveal');
                    reveals.forEach((el, i) => setTimeout(() => el.classList.add('show'), 80 * i));
                },
                prev(){ this.monthIndex = (this.monthIndex + 11) % 12; },
                next(){ this.monthIndex = (this.monthIndex + 1) % 12; },
                today(){ this.monthIndex = {{ $currentMonthIndex }}; },
                _ts: null,
                touchStart(e){ this._ts = e.changedTouches[0].clientX; },
                touchEnd(e){
                    if (this._ts === null) return;
                    const dx = e.changedTouches[0].clientX - this._ts;
                    if (Math.abs(dx) > 40) { dx < 0 ? this.next() : this.prev(); }
                    this._ts = null;
                }
            }
        }
    </script>
</x-app-layout>
