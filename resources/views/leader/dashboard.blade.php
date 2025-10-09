{{-- resources/views/leader/dashboard.blade.php --}}
<x-app-layout>
    {{-- Page styles (scoped) --}}
    <style>
        @keyframes fadeUp { from {opacity:0; transform:translateY(14px)} to {opacity:1; transform:none} }
        .reveal { opacity: 0; transform: translateY(14px); }
        .reveal.show { animation: fadeUp .6s ease forwards; }

        .tab-btn { border: 1px solid rgba(255,255,255,.08); background: rgba(0,0,0,.35); transition: all .25s ease; }
        .tab-btn.active { background: linear-gradient(90deg, rgba(16,185,129,.20), rgba(16,185,129,.10)); border-color: rgba(16,185,129,.45); color: #d1fae5; }
        .card { transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease, border-color .25s ease; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 20px 40px -18px rgba(0,0,0,.6); }
        .field { background: #111827; border: 1px solid #374151; color: #e5e7eb; transition: all .25s ease; }
        .field:hover { border-color: #10b981; }
        .field:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.25); }
        .badge { font-size: .7rem; padding: .2rem .45rem; border-radius: .5rem; }
        .chip { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); padding:.25rem .5rem; border-radius: 9999px; font-size:.75rem; }

        .month-chip { transition: all .2s ease; }
        .month-chip.active { background: rgba(16,185,129,.15); border-color: rgba(16,185,129,.5); color:#d1fae5; }
        .day-cell { transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease, border-color .15s ease; }
        .day-cell:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,.35); }
        .today-ring { box-shadow: 0 0 0 2px rgba(16,185,129,.8) inset, 0 0 0 1px rgba(16,185,129,.35); }
        .weekend { background-image: linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01)); }
        .no-scrollbar { scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        @media (prefers-reduced-motion: reduce) {
            .reveal.show { animation: none !important; opacity: 1 !important; transform: none !important; }
        }
    </style>

    {{-- BACKGROUND --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0"
             style="background-image:url('https://pbs.twimg.com/media/GzgU0ojXwAAAb9J.jpg'); background-size:cover; background-position:center 40%; filter:saturate(1) contrast(1.05) brightness(0.95);"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-black/35 to-black/55"></div>
        <div class="absolute inset-0" style="background:
            radial-gradient(1100px 520px at 20% 0%, rgba(16,185,129,.10), transparent 60%),
            radial-gradient(1200px 600px at 80% 100%, rgba(255,255,255,.05), transparent 65%);"></div>
    </div>

    {{-- CONTENT --}}
    <div
        class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-gray-100"
        x-data="leaderPanel({
            groupIds: {{ json_encode($groups->pluck('id')) }},
            monthNames: ['Janvāris','Februāris','Marts','Aprīlis','Maijs','Jūnijs','Jūlijs','Augusts','Septembris','Oktobris','Novembris','Decembris'],
            initialEvents: @js($events ?? []),    // expects: id, title, start_at, end_at, group:{id,name}, polygon:{id,name}, meetup_place
            polygons: @js($polygons ?? [])        // expects: id, name, group_id (optional), geojson/coords (not used here)
        })"
        x-init="onInit()"
    >
        {{-- Header --}}
        <header class="reveal flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Vadītāja panelis</h1>
                <p class="text-gray-200/90 mt-1">Veido grupas, pievieno lietotājus un apstrādā pieprasījumus.</p>
            </div>

            {{-- Quick stats --}}
            <div class="grid grid-cols-3 gap-3 min-w-[380px]">
                <div class="reveal card rounded-xl border border-white/10 bg-black/40 backdrop-blur-sm p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-300">Jūsu grupas</div>
                    <div class="mt-1 text-2xl font-bold">{{ $groups->count() }}</div>
                </div>
                <div class="reveal card rounded-xl border border-white/10 bg-black/40 backdrop-blur-sm p-4" style="animation-delay:.06s">
                    <div class="text-xs uppercase tracking-wide text-gray-300">Gaidošie pieprasījumi</div>
                    <div class="mt-1 text-2xl font-bold">{{ $requests->count() }}</div>
                </div>
                <div class="reveal card rounded-xl border border-white/10 bg-black/40 backdrop-blur-sm p-4" style="animation-delay:.12s">
                    <div class="text-xs uppercase tracking-wide text-gray-300">Notikumi</div>
                    <div class="mt-1 text-2xl font-bold" x-text="events.length"></div>
                </div>
            </div>
        </header>

        {{-- Tabs --}}
        <div class="reveal mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
            <button @click="tab='groups'"   :class="tab==='groups'   ? 'active' : ''" class="tab-btn px-4 py-2 rounded-lg font-semibold">Grupas</button>
            <button @click="tab='users'"    :class="tab==='users'    ? 'active' : ''" class="tab-btn px-4 py-2 rounded-lg font-semibold">Pievienot lietotāju</button>
            <button @click="tab='requests'" :class="tab==='requests' ? 'active' : ''" class="tab-btn px-4 py-2 rounded-lg font-semibold">
                Gaidošie pieprasījumi <span class="badge bg-emerald-600/30 border border-emerald-500/40 text-emerald-200 ml-2">{{ $requests->count() }}</span>
            </button>
            <button @click="tab='calendar'" :class="tab==='calendar' ? 'active' : ''" class="tab-btn px-4 py-2 rounded-lg font-semibold">Kalendārs</button>
        </div>

        {{-- Tab: Groups (Create, list) --}}
        <section x-show="tab==='groups'" x-transition.opacity class="mt-6 space-y-6">
            {{-- Create Group --}}
            <div class="reveal card rounded-2xl border border-white/10 bg-black/45 backdrop-blur-xl p-6 sm:p-8">
                <div class="flex items-start gap-4 mb-6">
                    <div class="h-12 w-12 rounded-xl bg-emerald-500/15 text-emerald-300 flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Izveidot jaunu grupu</h2>
                        <p class="text-gray-300 text-sm">Izvēlies medību tipu un piešķir grupai nosaukumu.</p>
                    </div>
                </div>

                <form action="{{ route('groups.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Medību tips</label>
                            <select name="hunting_type_id" class="field w-full rounded-lg p-3" required>
                                @foreach(\App\Models\HuntingType::orderBy('name')->get() as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('hunting_type_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Grupas nosaukums</label>
                            <input type="text" name="name" class="field w-full rounded-lg p-3" placeholder="Piem., Mežacūku vienība A" required>
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Apraksts (izvēles)</label>
                        <textarea name="description" class="field w-full rounded-lg p-3" rows="3" placeholder="Grupas apraksts…"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold">Izveidot grupu</button>
                    </div>
                </form>
            </div>

            {{-- Your groups list (compact) --}}
            <div class="reveal card rounded-2xl border border-white/10 bg-black/45 backdrop-blur-xl p-6 sm:p-8">
                <h3 class="text-lg font-bold mb-4">Jūsu grupas</h3>
                @if($groups->isEmpty())
                    <p class="text-gray-300">Jums vēl nav nevienas grupas.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($groups as $g)
                            <li class="flex items-center justify-between rounded-lg border border-white/10 bg-black/40 p-3">
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold">{{ $g->name }}</span>
                                    <span class="text-xs text-gray-300">• {{ optional($g->huntingType)->name }}</span>
                                </div>
                                <div class="text-sm text-gray-300">
                                    Dalībnieki: {{ $g->members_count ?? $g->members()->count() }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        {{-- Tab: Add user --}}
        <section x-show="tab==='users'" x-transition.opacity class="mt-6">
            <div class="reveal card rounded-2xl border border-white/10 bg-black/45 backdrop-blur-xl p-6 sm:p-8"
                 x-data="{ showPass:false }">
                <div class="flex items-start gap-4 mb-6">
                    <div class="h-12 w-12 rounded-xl bg-emerald-500/15 text-emerald-300 flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Pievienot lietotāju grupai</h2>
                        <p class="text-gray-300 text-sm">Norādi grupu — lietotājs tiks izveidots un automātiski pievienots tai.</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Grupa</label>
                        <select class="field w-full rounded-lg p-3" x-model="selectedGroupId" required>
                            @forelse($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }} — {{ optional($g->huntingType)->name }}</option>
                            @empty
                                <option value="">Nav grupu — izveido vispirms.</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Loma</label>
                        <select class="field w-full rounded-lg p-3" x-model="selectedRole">
                            <option value="member">Dalībnieks</option>
                            <option value="co_leader">Palīg-vadītājs</option>
                        </select>
                    </div>
                </div>

                <form :action="selectedGroupId ? `/groups/${selectedGroupId}/members` : ''" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" :value="selectedRole">

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Vārds</label>
                            <input type="text" name="name" class="field w-full rounded-lg p-3" placeholder="Jānis Jansons" required>
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">E-pasts</label>
                            <input type="email" name="email" class="field w-full rounded-lg p-3" placeholder="user@example.com" required>
                            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-sm text-gray-300 mb-1">Parole</label>
                            <input :type="showPass ? 'text':'password'" name="password" id="leader-pass" class="field w-full rounded-lg p-3 pr-12" placeholder="********" required>
                            <button type="button" @click="showPass=!showPass" class="absolute right-2.5 top-[34px] text-gray-300 hover:text-emerald-400">
                                <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 17 4.5 12 4.5Zm0 12a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9Z"/></svg>
                                <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3.53 2.47 2.47 3.53 5.3 6.36A11.77 11.77 0 0 0 1 12c1.73 4.39 6 7.5 11 7.5 2.1 0 4.08-.54 5.82-1.49l2.35 2.35 1.06-1.06L3.53 2.47ZM12 6.5c1.18 0 2.26.4 3.12 1.06l-1.11 1.11A3 3 0 0 0 9.7 12l-1.1 1.1A4.5 4.5 0 0 1 12 6.5Zm0 11a9.98 9.98 0 0 1-8.61-5.5 9.98 9.98 0 0 1 3.22-3.6l2.04 2.04c-.41.62-.65 1.36-.65 2.16a4.5 4.5 0 0 0 4.5 4.5c.8 0 1.54-.24 2.16-.65l1.51 1.51c-1.01.35-2.09.54-3.17.54Z"/></svg>
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Apstiprināt paroli</label>
                            <input :type="showPass ? 'text':'password'" name="password_confirmation" id="leader-pass2" class="field w-full rounded-lg p-3" placeholder="********" required>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-between pt-2">
                        <div class="flex items-center gap-3">
                            <button type="button" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15" @click="generate()">Ģenerēt spēcīgu paroli</button>
                            <span class="text-xs text-gray-300">Min. 12 simboli, jaukti rakstzīmju tipi.</span>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold" :disabled="!selectedGroupId">
                                Pievienot lietotāju grupai
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- Tab: Requests --}}
        <section x-show="tab==='requests'" x-transition.opacity class="mt-6">
            <div class="reveal card rounded-2xl border border-white/10 bg-black/45 backdrop-blur-xl p-6 sm:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold">Gaidošie pieprasījumi</h2>
                        <p class="text-gray-300 text-sm">Apstiprini vai noraidi jaunus dalībniekus.</p>
                    </div>
                    <div class="relative w-full md:w-80">
                        <input x-model="q" type="text" class="field w-full rounded-lg py-2.5 pl-10 pr-3" placeholder="Meklēt pēc vārda vai e-pasta…">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-300" viewBox="0 0 24 24" fill="currentColor"><path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Zm11 19-5.2-5.2"/></svg>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse($requests as $req)
                        @php
                            $u = $req->user;
                            $requestedAt = $req->created_at;
                            $note = $req->note ?? null;
                            $eligibleGroups = $groups->where('hunting_type_id', $req->hunting_type_id);
                            $initials = collect(explode(' ', $u->name ?? ''))->filter()->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->join('');
                        @endphp

                        <div class="rounded-xl border border-white/10 bg-black/40 p-4 sm:p-5"
                             x-show="matches('{{ addslashes($u->name ?? '') }}','{{ addslashes($u->email ?? '') }}')">

                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="h-12 w-12 rounded-full bg-emerald-500/15 text-emerald-300 flex items-center justify-center font-bold">
                                        {{ $initials ?: '👤' }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white leading-tight">{{ $u->name ?? '—' }}</div>
                                        <div class="text-gray-300 text-sm">{{ $u->email ?? '—' }}</div>

                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-300">
                                            <span class="badge bg-white/10 border border-white/15">Tips: {{ optional($req->huntingType)->name ?? '—' }}</span>
                                            @if($requestedAt)
                                                <span class="badge bg-white/10 border border-white/15">Pieteicies: {{ \Carbon\Carbon::parse($requestedAt)->format('d.m.Y H:i') }}</span>
                                            @endif
                                        </div>

                                        {{-- Always show the user's message (note) to leader --}}
                                        @if($note)
                                            <div class="mt-3 text-gray-100 text-sm border border-white/10 bg-black/40 rounded-lg p-3">
                                                <div class="text-xs uppercase tracking-wide text-gray-300 mb-1">Ziņa</div>
                                                <p class="whitespace-pre-line leading-relaxed">{{ $note }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:items-start">
                                    {{-- Approve --}}
                                    <form action="{{ route('leader.requests.approve', $req) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @if(is_null($req->group_id))
                                            <select name="target_group_id" class="field rounded-lg p-2" required>
                                                <option value="">— Izvēlies grupu —</option>
                                                @foreach($eligibleGroups as $g)
                                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                        <button class="w-full sm:w-auto px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold">Apstiprināt</button>
                                    </form>

                                    {{-- Reject --}}
                                    <form action="{{ route('leader.requests.reject', $req) }}" method="POST" onsubmit="return confirm('Vai tiešām noraidīt šo pieprasījumu?')">
                                        @csrf
                                        <button class="w-full sm:w-auto px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 font-semibold">Noraidīt</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-white/10 bg-black/40 p-6 text-gray-200 text-center">Nav gaidošo pieprasījumu</div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Tab: Calendar (inline) --}}
        <section x-show="tab==='calendar'" x-transition.opacity class="mt-6">
            <div class="reveal card rounded-2xl border border-white/10 bg-black/45 backdrop-blur-xl p-6 sm:p-8">
                <div class="flex flex-col lg:flex-row lg:items-start gap-8">
                    {{-- Calendar column --}}
                    <div class="flex-1 min-w-[320px]">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                            <div>
                                <h2 class="text-xl font-bold">Kalendārs</h2>
                                <p class="text-gray-300 text-sm">Redzami tikai jūsu grupu notikumi.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="today()" class="px-3 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15 transition">Šodien</button>
                                <div class="flex gap-2">
                                    <button @click="prev()" class="h-10 w-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center hover:bg-white/15 transition" aria-label="Iepriekšējais">‹</button>
                                    <button @click="next()" class="h-10 w-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center hover:bg-white/15 transition" aria-label="Nākamais">›</button>
                                </div>
                            </div>
                        </div>

                        {{-- Month chips --}}
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-lg font-semibold" x-text="viewYear"></div>
                                <div class="hidden sm:flex items-center gap-2 text-xs">
                                    <span class="chip"><span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 mr-1"></span>Jūsu grupu notikumi</span>
                                </div>
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

                        {{-- Current month title --}}
                        <div class="mt-4">
                            <h3 class="text-2xl sm:text-3xl font-bold" x-text="monthNames[viewMonth-1] + ' ' + viewYear"></h3>
                        </div>

                        {{-- Grid --}}
                        <div class="mt-3 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm shadow-2xl p-3 sm:p-4"
                             x-on:touchstart.passive="touchStart($event)" x-on:touchend.passive="touchEnd($event)">
                            <div class="grid grid-cols-7 gap-1 sm:gap-2 text-center font-semibold text-gray-300 mb-2">
                                <div>P</div><div>O</div><div>T</div><div>C</div><div>P</div><div>S</div><div>S</div>
                            </div>
                            <div class="grid grid-cols-7 gap-1 sm:gap-2">
                                <template x-for="cell in daysGrid()" :key="cell.key">
                                    <div
                                        class="day-cell h-16 sm:h-20 md:h-24 rounded border border-white/10 p-1 sm:p-1.5 flex flex-col items-start justify-start text-left"
                                        :class="{
                                            'bg-white/[.03]': cell.inMonth && !cell.isWeekend,
                                            'weekend bg-white/[.04]': cell.inMonth && cell.isWeekend,
                                            'bg-transparent border-transparent': !cell.inMonth,
                                            'today-ring bg-emerald-600/10': cell.isToday
                                        }"
                                    >
                                        <div class="text-xs sm:text-sm font-semibold" x-text="cell.label"></div>

                                        {{-- dots for events --}}
                                        <template x-for="ev in eventsForDay(cell.date)" :key="ev.id">
                                            <a href="#" @click.prevent="openShow(ev)"
                                               class="block mt-1 text-[10px] sm:text-xs truncate px-1 rounded bg-emerald-600/30 border border-emerald-500/30">
                                                <span x-text="ev.title || 'Notikums'"></span>
                                            </a>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button @click="openCreate()" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold">
                                Pievienot notikumu
                            </button>
                        </div>
                    </div>

                    {{-- Side column: Upcoming list --}}
                    <aside class="w-full lg:w-[360px]">
                        <div class="rounded-2xl border border-white/10 bg-black/40 p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-lg">Tuvākie notikumi</h3>
                                <span class="text-xs text-gray-300" x-text="upcoming.length + ' ieraksti'"></span>
                            </div>
                            <div class="mt-3 space-y-2 max-h-[520px] overflow-y-auto no-scrollbar pr-1">
                                <template x-if="upcoming.length === 0">
                                    <p class="text-gray-300 text-sm">Nav gaidāmo notikumu.</p>
                                </template>
                                <template x-for="ev in upcoming" :key="ev.id">
                                    <div class="rounded-lg border border-white/10 bg-black/30 p-3">
                                        <div class="font-semibold" x-text="ev.title || 'Notikums'"></div>
                                        <div class="text-xs text-gray-300 mt-0.5">
                                            <span x-text="formatDateTime(ev.start_at)"></span>
                                            <template x-if="ev.end_at"> – <span x-text="formatDateTime(ev.end_at)"></span></template>
                                        </div>
                                        <div class="text-xs text-gray-300 mt-0.5">
                                            Grupa: <span class="font-medium" x-text="ev.group?.name || '—'"></span>
                                        </div>
                                        <template x-if="ev.meetup_place">
                                            <div class="text-xs text-gray-300 mt-0.5">Tikšanās: <span x-text="ev.meetup_place"></span></div>
                                        </template>
                                        <div class="flex gap-2 mt-2">
                                            <button class="px-2 py-1 rounded bg-white/10 border border-white/10 hover:bg-white/15 text-xs" @click="openShow(ev)">Skatīt</button>
                                            <form :action="`/leader/events/${ev.id}`" method="POST" onsubmit="return confirm('Dzēst notikumu?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-2 py-1 rounded bg-red-600 hover:bg-red-700 text-xs">Dzēst</button>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

            {{-- CREATE MODAL --}}
            <div x-show="showCreate" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/60" @click="showCreate=false"></div>
                <div class="relative w-full max-w-2xl rounded-2xl border border-white/10 bg-black/90 p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold">Jauns notikums</h3>
                        <button class="px-2 py-1 rounded bg-white/10 border border-white/10 hover:bg-white/15" @click="showCreate=false">Aizvērt</button>
                    </div>

                    <form method="POST" action="{{ route('leader.events.store') }}" class="mt-4 space-y-4">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Grupa</label>
                                <select class="field rounded-lg p-2" name="group_id" required>
                                    <option value="">— Izvēlies grupu —</option>
                                    @foreach($groups as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Saglabātais poligons</label>
                                <select class="field rounded-lg p-2" name="polygon_id">
                                    <option value="">— Nav —</option>
                                    @foreach(($polygons ?? []) as $poly)
                                        <option value="{{ $poly->id }}">{{ $poly->name }}@if($poly->group_id) — ({{ optional($groups->firstWhere('id',$poly->group_id))->name }})@endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Sākums</label>
                                <input type="datetime-local" class="field rounded-lg p-2" name="start_at" :value="prefillStart" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Beigas (izvēles)</label>
                                <input type="datetime-local" class="field rounded-lg p-2" name="end_at">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Virsraksts</label>
                                <input type="text" class="field rounded-lg p-2" name="title" placeholder="Piem., Rīta medības" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Tikšanās vieta</label>
                                <input type="text" class="field rounded-lg p-2" name="meetup_place" placeholder="Stāvlaukums pie torņa #3">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Papildu info (izvēles)</label>
                            <textarea class="field w-full rounded-lg p-2" name="notes" rows="4" placeholder="Instrukcijas, ekipējums, drošība…"></textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15" @click="showCreate=false">Atcelt</button>
                            <button class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold" type="submit">Saglabāt</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SHOW MODAL --}}
            <div x-show="showOne" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/60" @click="showOne=false"></div>
                <div class="relative w-full max-w-xl rounded-2xl border border-white/10 bg-black/90 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-bold" x-text="one?.title || 'Notikums'"></h3>
                        <button class="px-2 py-1 rounded bg-white/10 border border-white/10 hover:bg-white/15" @click="showOne=false">Aizvērt</button>
                    </div>
                    <div class="text-sm text-gray-300">
                        <div><strong>Laiks:</strong> <span x-text="formatDateTime(one?.start_at)"></span><template x-if="one?.end_at"> – <span x-text="formatDateTime(one?.end_at)"></span></template></div>
                        <div><strong>Grupa:</strong> <span x-text="one?.group?.name || '—'"></span></div>
                        <template x-if="one?.polygon"><div><strong>Poligons:</strong> <span x-text="one?.polygon?.name"></span></div></template>
                        <template x-if="one?.meetup_place"><div><strong>Tikšanās vieta:</strong> <span x-text="one?.meetup_place"></span></div></template>
                        <template x-if="one?.notes"><div class="mt-2"><strong>Piezīmes:</strong><br><span x-text="one?.notes"></span></div></template>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Alpine helpers --}}
    <script>
        function leaderPanel({ groupIds = [], monthNames = [], initialEvents = [], polygons = [] }) {
            const now = new Date();
            return {
                tab: 'groups',
                q: '',
                selectedGroupId: groupIds.length ? groupIds[0] : null,
                selectedRole: 'member',

                // calendar state
                monthNames,
                viewYear: now.getFullYear(),
                viewMonth: now.getMonth() + 1,
                todayY: now.getFullYear(),
                todayM: now.getMonth() + 1,
                todayD: now.getDate(),

                // events
                events: initialEvents,  // array of {id,title,start_at,end_at,group:{id,name},polygon:{id,name},meetup_place,notes}
                polygons,

                // modals
                showCreate: false,
                showOne: false,
                one: null,
                prefillStart: '',

                onInit(){
                    const items = document.querySelectorAll('.reveal');
                    items.forEach((el, i) => setTimeout(() => el.classList.add('show'), 70 * i));
                },

                // users tab helpers
                generate(){
                    const upp="ABCDEFGHJKLMNPQRSTUVWXYZ", low="abcdefghijkmnpqrstuvwxyz", num="23456789", sym="!@#$%^&*()-_=+[]{}";
                    const all=upp+low+num+sym, pick=(str,n)=>Array.from({length:n},()=>str[Math.floor(Math.random()*str.length)]).join('');
                    let pwd = pick(upp,2)+pick(low,6)+pick(num,2)+pick(sym,2)+pick(all,4);
                    pwd = pwd.split('').sort(()=>Math.random()-0.5).join('');
                    const p1=document.getElementById('leader-pass'), p2=document.getElementById('leader-pass2'); if(p1&&p2){p1.value=pwd;p2.value=pwd;}
                },
                matches(name, email){
                    const s = this.q.trim().toLowerCase();
                    if(!s) return true;
                    return (name || '').toLowerCase().includes(s) || (email || '').toLowerCase().includes(s);
                },

                // calendar nav
                setMonth(m){ this.viewMonth = m; },
                today(){ this.viewYear = this.todayY; this.viewMonth = this.todayM; },
                prev(){ if (this.viewMonth === 1) { this.viewMonth = 12; this.viewYear -= 1; } else { this.viewMonth -= 1; } },
                next(){ if (this.viewMonth === 12) { this.viewMonth = 1; this.viewYear += 1; } else { this.viewMonth += 1; } },

                // calendar grid
                daysInMonth(y, m){ return new Date(y, m, 0).getDate(); },
                monFirstIndex(jsDay){ return ((jsDay + 6) % 7) + 1; },
                isToday(y,m,d){ return y === this.todayY && m === this.todayM && d === this.todayD; },
                isWeekend(y,m,d){ const js = new Date(y, m-1, d).getDay(); return js === 6 || js === 0; },
                daysGrid(){
                    const y = this.viewYear, m = this.viewMonth;
                    const first = new Date(y, m-1, 1);
                    const leading = this.monFirstIndex(first.getDay()) - 1;
                    const dim = this.daysInMonth(y, m);
                    const cells = []; let key = 0;

                    for (let i=0;i<leading;i++) cells.push({ key: key++, inMonth:false, label:'', date:null, isWeekend:false, isToday:false });
                    for (let d=1; d<=dim; d++) {
                        cells.push({
                            key: key++,
                            inMonth:true,
                            label:String(d),
                            date: new Date(y, m-1, d),
                            isWeekend: this.isWeekend(y,m,d),
                            isToday: this.isToday(y,m,d),
                        });
                    }
                    const remainder = cells.length % 7;
                    if (remainder) for (let i=0;i<7-remainder;i++) cells.push({ key: key++, inMonth:false, label:'', date:null });
                    return cells;
                },

                // events helpers
                startOfDay(d){ const x = new Date(d); x.setHours(0,0,0,0); return x; },
                endOfDay(d){ const x = new Date(d); x.setHours(23,59,59,999); return x; },
                sameDay(a,b){ return a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate(); },
                parse(dt){ return dt ? new Date(dt.replace(' ', 'T')) : null; },
                eventsForDay(date){
                    if (!date) return [];
                    const start = this.startOfDay(date), end = this.endOfDay(date);
                    return this.events.filter(ev=>{
                        const s = this.parse(ev.start_at), e = this.parse(ev.end_at) || this.parse(ev.start_at);
                        return s && e && s <= end && e >= start; // any overlap
                    }).sort((a,b)=> this.parse(a.start_at) - this.parse(b.start_at)).slice(0,3);
                },
                get upcoming(){
                    const now = new Date();
                    return this.events
                        .filter(ev => {
                            const e = this.parse(ev.end_at) || this.parse(ev.start_at);
                            return e && e >= now;
                        })
                        .sort((a,b)=> this.parse(a.start_at) - this.parse(b.start_at))
                        .slice(0,50);
                },
                formatDateTime(dt){
                    if(!dt) return '';
                    const d = this.parse(dt);
                    const pad = n => String(n).padStart(2,'0');
                    return `${pad(d.getDate())}.${pad(d.getMonth()+1)}.${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
                },

                // modals
                openCreate(){
                    this.prefillStart = (()=> {
                        const d = new Date(this.viewYear, this.viewMonth-1, Math.min(this.todayD, this.daysInMonth(this.viewYear, this.viewMonth)), 7, 0, 0);
                        const pad = n => String(n).padStart(2,'0');
                        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
                    })();
                    this.showCreate = true;
                },
                openShow(ev){ this.one = ev; this.showOne = true; },

                // touch
                _ts: null,
                touchStart(e){ this._ts = e.changedTouches[0].clientX; },
                touchEnd(e){ if (this._ts===null) return; const dx=e.changedTouches[0].clientX - this._ts; if (Math.abs(dx)>40) { dx<0 ? this.next() : this.prev(); } this._ts=null; },
            }
        }
    </script>
</x-app-layout>
        