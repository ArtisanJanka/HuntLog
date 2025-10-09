<x-app-layout>
    @php
        $monthNames = ['Janvāris','Februāris','Marts','Aprīlis','Maijs','Jūnijs','Jūlijs','Augusts','Septembris','Oktobris','Novembris','Decembris'];
        $currentDay   = (int)date('j');
        $currentMonth = (int)date('n');
        $currentYear  = (int)date('Y');

        // Prepare a safe, minimal events array for JS (include as many useful fields as exist)
        $safeEvents = collect($events ?? [])->map(function ($e) {
            return [
                'id'           => $e->id,
                'group_id'     => $e->group_id ?? null,
                'group'        => optional($e->group)->name ?? null,
                'title'        => $e->title ?? 'Notikums',
                'start_at'     => optional($e->start_at)->format('Y-m-d') ?? null,
                'end_at'       => optional($e->end_at)->format('Y-m-d') ?? null,
                'start_time'   => optional($e->start_at)->format('H:i') ?? null,
                'end_time'     => optional($e->end_at)->format('H:i') ?? null,
                'polygon'      => optional($e->polygon)->name ?? null,
                'polygon_url'  => $e->polygon ? route('polygons.show', $e->polygon) : null,
                'meetup_place' => $e->meetup_place ?? null,
                'details'      => $e->details ?? ($e->description ?? null),
                // If you have creator relation on the model:
                'creator'      => optional($e->creator ?? null)->name ?? null,
                // If group has hunting type:
                'type'         => optional(optional($e->group)->huntingType)->name ?? null,
            ];
        })->values();
    @endphp

    <style>
        html, body, .min-h-screen, main { background: transparent !important; }
        .hunt-bg { position: relative; z-index: 0; }
        .hunt-bg > .hunt-content { position: relative; z-index: 2; }
        .hunt-bg::before {
            content:""; position: fixed; inset: 0; z-index: 1;
            background:
              linear-gradient(180deg, rgba(0,0,0,.35), rgba(0,0,0,.78)),
              url('https://images.squarespace-cdn.com/content/v1/5ddd707034a3a5066151f221/d90be072-e7ed-47e7-8828-91c87f49e9df/laura-college-K_Na5gCmh38-unsplash.jpg') center/cover no-repeat;
            pointer-events: none;
        }
        .hunt-bg::after {
            content:""; position: fixed; inset: 0; z-index: 1;
            background:
              radial-gradient(1400px 620px at 50% 110%, rgba(16,185,129,.12), transparent 70%),
              radial-gradient(1200px 600px at 20% 10%, rgba(255,255,255,.05), transparent 60%);
            pointer-events: none;
        }
        .wild-art { position: fixed; right: -4%; bottom: -2%; width: 40vw; max-width: 640px; min-width: 260px; opacity: .12; filter: grayscale(1) contrast(1.05); z-index: 1; pointer-events: none; user-select: none; }

        @keyframes fadeUp { from {opacity:0; transform:translateY(12px)} to {opacity:1; transform:none} }
        .reveal { opacity:0; transform:translateY(12px); }
        .reveal.show { animation: fadeUp .6s ease forwards; }

        .month-chip { transition: all .2s ease; }
        .month-chip.active { background: rgba(16,185,129,.15); border-color: rgba(16,185,129,.5); color:#d1fae5; }

        .day-cell { transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease, border-color .15s ease; }
        .day-cell:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,.35); }
        .today-ring { box-shadow: 0 0 0 2px rgba(16,185,129,.8) inset, 0 0 0 1px rgba(16,185,129,.35); }
        .weekend { background-image: linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01)); }

        .no-scrollbar { scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Icon button reused */
        .btn-icon{
          display:inline-flex; align-items:center; justify-content:center;
          width:34px; height:34px; border-radius:.6rem;
          background: rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12);
          color:#e5e7eb; transition: all .2s ease;
        }
        .btn-icon:hover{ color:#34d399; border-color: rgba(16,185,129,.5); background: rgba(255,255,255,.12); }

        .chip { display:inline-flex; align-items:center; gap:.4rem; padding:.2rem .5rem; border-radius:.5rem; font-size:.75rem; border:1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.08); color:#e5e7eb; }
    </style>

    <div
        class="hunt-bg relative w-full min-h-screen text-gray-100 overflow-hidden"
        x-data="huntCalendar({
            initialYear: {{ $currentYear }},
            initialMonth: {{ $currentMonth }},
            initialDay: {{ $currentDay }},
            monthNames: @js($monthNames),
            events: @js($safeEvents)
        })"
        x-init="initComponent()"
    >
        <img class="wild-art" alt="Sarkanais briedis" src="https://upload.wikimedia.org/wikipedia/commons/3/3f/Red_deer_stag_2019.jpg" />

        <div class="hunt-content max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10" data-reveal-group>
            <!-- Header -->
            <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight">Medību kalendārs</h1>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="today()" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15 transition">Šodien</button>
                    <div class="flex gap-2">
                        <button @click="prev()" class="h-10 w-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center hover:bg-white/15 transition" aria-label="Iepriekšējais">‹</button>
                        <button @click="next()" class="h-10 w-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center hover:bg-white/15 transition" aria-label="Nākamais">›</button>
                    </div>
                </div>
            </div>

            <!-- Month chips -->
            <div class="reveal mt-5">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg font-semibold" x-text="viewYear"></div>
                </div>
                <div class="flex gap-2 overflow-x-auto no-scrollbar py-2 -mx-1 px-1">
                    <template x-for="(m, idx) in monthNames" :key="idx">
                        <button
                            class="month-chip whitespace-nowrap px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-gray-200 hover:bg-white/10"
                            :class="idx+1 === viewMonth ? 'active' : ''"
                            @click="setMonth(idx+1)"
                            x-text="m"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Calendar -->
            <div class="mt-6 sm:mt-8">
                <section class="reveal w-full">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-2xl sm:text-3xl font-bold" x-text="monthNames[viewMonth-1] + ' ' + viewYear"></h2>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm shadow-2xl p-3 sm:p-4">
                        <!-- Week headers (Mon..Sun) -->
                        <div class="grid grid-cols-7 gap-1 sm:gap-2 text-center font-semibold text-gray-300 mb-2">
                            <div>P</div><div>O</div><div>T</div><div>C</div><div>P</div><div>S</div><div>S</div>
                        </div>
                        <!-- Days grid -->
                        <div class="grid grid-cols-7 gap-1 sm:gap-2">
                            <template x-for="cell in daysGrid()" :key="cell.key">
                                <div
                                    class="day-cell h-12 sm:h-16 md:h-20 rounded border border-white/10 flex items-center justify-center font-semibold text-sm sm:text-base cursor-pointer"
                                    :class="{
                                        'bg-white/[.03]': cell.inMonth && !cell.isWeekend,
                                        'weekend bg-white/[.04]': cell.inMonth && cell.isWeekend,
                                        'bg-transparent border-transparent': !cell.inMonth,
                                        'today-ring bg-emerald-600/10': cell.isToday
                                    }"
                                    :title="cell.title"
                                    x-text="cell.label"
                                    @click="cell.inMonth && selectDate(cell.dateISO)"
                                ></div>
                            </template>
                        </div>
                    </div>

                    <!-- Selected day summary (full details) -->
                    <div class="mt-5 rounded-xl border border-white/10 bg-black/40 p-4">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold" x-text="formatLong(selectedDate)"></div>
                            <div class="text-sm text-gray-300" x-text="dayEvents(selectedDate).length + ' pasākumi'"></div>
                        </div>

                        <div class="mt-3 space-y-3">
                            <template x-if="dayEvents(selectedDate).length === 0">
                                <div class="text-gray-300">Šajā dienā nav notikumu.</div>
                            </template>

                            <template x-for="ev in dayEvents(selectedDate)" :key="ev.id">
                                <div class="rounded-xl border border-white/10 bg-black/30 p-3">
                                    <!-- Header: title + quick chips + map btn -->
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="font-semibold truncate" x-text="ev.title"></div>
                                            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs">
                                                <template x-if="ev.group">
                                                    <span class="chip">Grupa: <strong x-text="ev.group"></strong></span>
                                                </template>
                                                <template x-if="ev.type">
                                                    <span class="chip">Tips: <span x-text="ev.type"></span></span>
                                                </template>
                                                <template x-if="ev.creator">
                                                    <span class="chip">Izveidoja: <span x-text="ev.creator"></span></span>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Show on map -->
                                        <a class="btn-icon" x-show="ev.polygon_url" :href="ev.polygon_url" title="Skatīt kartē">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M9 20l-5-2V6l5 2 6-2 5 2v12l-5-2-6 2z"/>
                                            </svg>
                                        </a>
                                    </div>

                                    <!-- When -->
                                    <div class="mt-2 text-sm text-gray-300">
                                        <span x-text="ev.start_at"></span>
                                        <template x-if="ev.start_time">
                                            <span> <span x-text="ev.start_time"></span></span>
                                        </template>
                                        <template x-if="ev.end_at || ev.end_time">
                                            <span> — </span>
                                        </template>
                                        <template x-if="ev.end_at">
                                            <span x-text="ev.end_at"></span>
                                        </template>
                                        <template x-if="ev.end_time">
                                            <span> <span x-text="ev.end_time"></span></span>
                                        </template>
                                    </div>

                                    <!-- Polygon name -->
                                    <template x-if="ev.polygon">
                                        <div class="mt-1 text-sm text-gray-300">
                                            Poligons: <span class="font-medium" x-text="ev.polygon"></span>
                                        </div>
                                    </template>

                                    <!-- Meetup -->
                                    <template x-if="ev.meetup_place">
                                        <div class="mt-1 text-sm text-gray-300">
                                            Tikšanās vieta: <span class="font-medium" x-text="ev.meetup_place"></span>
                                        </div>
                                    </template>

                                    <!-- Details / Notes -->
                                    <template x-if="ev.details">
                                        <div class="mt-2 text-sm text-gray-200 whitespace-pre-line leading-relaxed">
                                            <span x-text="ev.details"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
      window.huntCalendar = function ({ initialYear, initialMonth, initialDay, monthNames, events }) {
        return {
          // State
          viewYear: initialYear,
          viewMonth: initialMonth,
          todayY: initialYear,
          todayM: initialMonth,
          todayD: initialDay,
          monthNames: monthNames || ['Janvāris','Februāris','Marts','Aprīlis','Maijs','Jūnijs','Jūlijs','Augusts','Septembris','Oktobris','Novembris','Decembris'],
          events: Array.isArray(events) ? events : [],

          // Initial selected date without Date/UTC conversions
          selectedDate: `${initialYear}-${String(initialMonth).padStart(2,'0')}-${String(initialDay).padStart(2,'0')}`,

          initComponent() {
            document.querySelectorAll('[data-reveal-group]').forEach(group => {
              const els = group.querySelectorAll('.reveal');
              els.forEach((el, i) => setTimeout(() => el.classList.add('show'), 80 * i));
            });
          },

          setMonth(m) { this.viewMonth = m; },
          today()  {
            this.viewYear = this.todayY;
            this.viewMonth = this.todayM;
            this.selectedDate = this.isoLocal(this.todayY, this.todayM, this.todayD);
          },
          prev()   { if (this.viewMonth === 1) { this.viewMonth = 12; this.viewYear -= 1; } else { this.viewMonth -= 1; } },
          next()   { if (this.viewMonth === 12) { this.viewMonth = 1;  this.viewYear += 1; } else { this.viewMonth += 1; } },
          selectDate(iso) { this.selectedDate = iso; },

          // Local helpers (no timezone drifting)
          isoLocal(y,m,d){ return `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`; },
          daysInMonth(y, m){ return new Date(y, m, 0).getDate(); }, // m: 1..12
          monFirstIndex(jsDay){ return ((jsDay + 6) % 7) + 1; },
          isToday(y,m,d){ return y === this.todayY && m === this.todayM && d === this.todayD; },
          isWeekend(y,m,d){ const js = new Date(y, m-1, d).getDay(); return js === 6 || js === 0; },

          formatLong(iso){
            if(!iso) return '';
            const [yy,mm,dd] = iso.split('-').map(n => parseInt(n,10));
            return `${dd}. ${this.monthNames[mm-1]} ${yy}`;
          },

          daysGrid(){
            const y = this.viewYear, m = this.viewMonth;
            const first = new Date(y, m-1, 1);
            const leading = this.monFirstIndex(first.getDay()) - 1;
            const dim = this.daysInMonth(y, m);

            const cells = []; let key = 0;
            for (let i=0;i<leading;i++) cells.push({ key: key++, inMonth:false, label:'', title:'', dateISO:null });
            for (let d=1; d<=dim; d++) {
              const weekend = this.isWeekend(y,m,d);
              const iso = this.isoLocal(y,m,d);
              cells.push({
                key: key++, inMonth:true, label: String(d), dateISO: iso,
                isWeekend: weekend, isToday: this.isToday(y,m,d),
                title: `${d}. ${this.monthNames[m-1]} ${y}`
              });
            }
            const remainder = cells.length % 7;
            if (remainder !== 0) {
              for (let i=0;i<7-remainder;i++) cells.push({ key: key++, inMonth:false, label:'', title:'', dateISO:null });
            }
            return cells;
          },

          dayEvents(iso){
            if(!iso) return [];
            return this.events.filter(e => e.start_at === iso);
          },
        }
      }
    </script>
</x-app-layout>
