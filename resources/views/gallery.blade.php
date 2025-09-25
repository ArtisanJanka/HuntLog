<x-app-layout>
    <section class="relative bg-gray-900 min-h-screen py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8"
             x-data="galleryState()"
             x-init="init()">

            @php
                $items = \App\Models\GalleryItem::with('huntingType')->latest()->get();

                // Build unique type list for filters
                $types = $items->map(function ($it) {
                    return [
                        'id'   => optional($it->huntingType)->id,
                        'name' => optional($it->huntingType)->name ?? 'Nezināms',
                    ];
                })->unique('id')->filter(fn($t) => $t['id'] !== null)->values();
            @endphp

            {{-- Header / Controls --}}
            <header class="mb-6 sm:mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Galerija</h1>
                    <p class="text-gray-300 mt-1">Skati jaunākos attēlus un pievienojies interesējošajām grupām.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    {{-- Filter chips --}}
                    <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1 -mx-1 px-1">
                        <button @click="activeType='all'"
                                :class="activeType==='all' ? 'chip-active' : ''"
                                class="chip">Visi</button>
                        @foreach($types as $t)
                            <button @click="activeType='{{ $t['name'] }}'"
                                    :class="activeType==='{{ $t['name'] }}' ? 'chip-active' : ''"
                                    class="chip whitespace-nowrap">
                                {{ $t['name'] }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Search --}}
                    <div class="relative">
                        <input x-model="q"
                               type="text"
                               placeholder="Meklēt pēc nosaukuma vai tipa…"
                               class="w-full sm:w-72 bg-gray-800 text-gray-100 border border-gray-700 rounded-lg pl-10 pr-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Zm11 19-5.2-5.2"/>
                        </svg>
                    </div>
                </div>
            </header>

            @if($items->isEmpty())
                <p class="text-gray-400 text-center">Nav attēlu galerijā.</p>
            @else
                <!-- Masonry -->
                <div id="gallery"
                     class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6"
                     style="column-gap: 1.5rem;">
                    @foreach ($items as $i => $item)
                        @php
                            $title = $item->title ?? optional($item->huntingType)->name ?? '—';
                            $typeName = optional($item->huntingType)->name ?? 'Nezināms';
                            $typeId = optional($item->huntingType)->id;
                            $src = $item->url();
                        @endphp

                        <div class="gi break-inside-avoid mb-6 rounded-xl overflow-hidden relative group shadow-lg opacity-0"
                             x-show="visible({{ $i }}, '{{ addslashes($typeName) }}', '{{ addslashes($title) }}')"
                             x-data="{ loaded:false }"
                             @click="openLightbox({ idx: {{ $i }}, src: '{{ $src }}', title: '{{ addslashes($title) }}', type: '{{ addslashes($typeName) }}', typeId: '{{ $typeId }}' })"
                             style="animation-delay: {{ number_format(($i % 12) * 0.06, 2) }}s;">

                            {{-- Skeleton --}}
                            <div class="skeleton" x-show="!loaded"></div>

                            {{-- Image --}}
                            <img
                                src="{{ $src }}"
                                alt="{{ $title }}"
                                loading="lazy" decoding="async"
                                @load="loaded=true; $el.previousElementSibling?.classList.add('fade-out')"
                                class="w-full object-cover rounded-xl transition-transform duration-500 transform group-hover:scale-[1.03] will-change-transform" />

                            {{-- Corner chip (type) --}}
                            <div class="absolute top-3 left-3 z-10">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-black/60 border border-white/10 text-gray-100 backdrop-blur">
                                    {{ $typeName }}
                                </span>
                            </div>

                            {{-- Hover overlay + CTA --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end justify-between p-3">
                                <div class="text-white font-semibold line-clamp-1 pr-2">{{ $title }}</div>
                                @if($typeId)
                                <form action="{{ route('join-group.store') }}" method="POST" @click.stop>
                                    @csrf
                                    <input type="hidden" name="hunting_type_id" value="{{ $typeId }}">
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-emerald-600 text-white text-sm font-semibold rounded-md hover:bg-emerald-700">
                                        Pievienoties
                                    </button>
                                </form>
                                @endif
                            </div>

                            {{-- Subtle glow ring on hover --}}
                            <span class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-emerald-500/0 group-hover:ring-emerald-500/40 transition"></span>
                        </div>
                    @endforeach
                </div>

                {{-- Load more --}}
                <div class="flex justify-center mt-8" x-show="showCount < {{ $items->count() }}">
                    <button @click="loadMore()"
                            class="px-5 py-2.5 bg-gray-800 text-white rounded-lg border border-gray-700 hover:bg-gray-700 focus:ring-2 focus:ring-emerald-500">
                        Ielādēt vēl
                    </button>
                </div>
            @endif

            {{-- Lightbox --}}
            <div x-show="lightbox.open"
                 x-transition.opacity
                 @keydown.window.escape="closeLightbox()"
                 class="fixed inset-0 z-[60] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="relative max-w-5xl w-full">
                    <button @click="closeLightbox()" aria-label="Aizvērt"
                            class="absolute -top-2 -right-2 bg-white/10 hover:bg-white/20 text-white rounded-full p-2">
                        ✕
                    </button>

                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 rounded-xl overflow-hidden border border-white/10 bg-black/30">
                            <img :src="lightbox.src" :alt="lightbox.title" class="w-full h-full object-contain max-h-[70vh] mx-auto" />
                        </div>

                        <div class="md:w-80 bg-black/40 rounded-xl p-4 border border-white/10">
                            <h3 class="text-white font-bold text-lg" x-text="lightbox.title"></h3>
                            <p class="mt-1 text-sm text-gray-300">
                                Tips: <span class="font-medium text-emerald-300" x-text="lightbox.type"></span>
                            </p>

                            <form action="{{ route('join-group.store') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="hunting_type_id" :value="lightbox.typeId">
                                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white font-semibold">
                                    Pievienoties grupai
                                </button>
                            </form>

                            <div class="mt-4 flex items-center justify-between">
                                <button @click="nav(-1)" class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/15 text-white">‹ Iepriekšējais</button>
                                <button @click="nav(1)" class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/15 text-white">Nākamais ›</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Styles --}}
    <style>
        /* Chips */
        .chip { padding:.5rem .75rem; border-radius:.75rem; background:#111827; color:#e5e7eb; border:1px solid #374151; transition:all .2s ease; }
        .chip:hover { border-color:#10b981; }
        .chip-active { background:linear-gradient(90deg, rgba(16,185,129,.20), rgba(16,185,129,.10)); color:#d1fae5; border-color:rgba(16,185,129,.45); }

        /* No scrollbar helper */
        .no-scrollbar { scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Pop-in reveal for tiles */
        @keyframes popIn { from { opacity:0; transform: scale(.96) translateY(16px); } to { opacity:1; transform: scale(1) translateY(0); } }
        .gi { animation: popIn .55s cubic-bezier(.2,.65,.25,1) forwards; }

        /* Skeleton shimmer */
        .skeleton {
            position:absolute; inset:0;
            background: linear-gradient(90deg, rgba(255,255,255,.06), rgba(255,255,255,.12), rgba(255,255,255,.06));
            background-size: 200% 100%;
            animation: shimmer 1.1s linear infinite;
            border-radius: .75rem;
        }
        .skeleton.fade-out { animation: fadeOut .25s ease forwards; }
        @keyframes shimmer { from { background-position: 200% 0 } to { background-position: -200% 0 } }
        @keyframes fadeOut { to { opacity: 0; visibility: hidden; } }
    </style>

    {{-- Alpine state --}}
    <script>
        function galleryState() {
            return {
                q: '',
                activeType: 'all',
                showCount: 16,
                // internal cache of items for lightbox nav
                _nodes: [],
                lightbox: { open:false, idx:null, src:'', title:'', type:'', typeId:null },

                init() {
                    // cache nodes in DOM order for lightbox navigation
                    this._nodes = Array.from(document.querySelectorAll('#gallery .gi'))
                        .filter(el => window.getComputedStyle(el).display !== 'none')
                        .map((el, idx) => {
                            const img = el.querySelector('img');
                            const chip = el.querySelector('.absolute .inline-flex');
                            // pull data from the click handler inline attributes too if needed
                            return {
                                el,
                                idx,
                                src: img?.getAttribute('src') || '',
                                title: img?.getAttribute('alt') || '',
                                type: chip?.textContent?.trim() || '',
                                typeId: (el.querySelector('input[name="hunting_type_id"]')?.value) || null,
                            };
                        });

                    // re-cache when filters/search change
                    this.$watch('q', () => this._recache());
                    this.$watch('activeType', () => this._recache());
                },

                _recache() {
                    // After filtering, rebuild the order for nav
                    const tiles = Array.from(document.querySelectorAll('#gallery .gi'))
                        .filter(el => el.offsetParent !== null); // visible ones only
                    this._nodes = tiles.map((el, i) => {
                        const img = el.querySelector('img');
                        const chip = el.querySelector('.absolute .inline-flex');
                        return {
                            el,
                            idx: i,
                            src: img?.getAttribute('src') || '',
                            title: img?.getAttribute('alt') || '',
                            type: chip?.textContent?.trim() || '',
                            typeId: (el.querySelector('input[name="hunting_type_id"]')?.value) || null,
                        };
                    });
                },

                visible(i, type, title) {
                    if (i >= this.showCount) return false;
                    const matchesType = (this.activeType === 'all') || (type.toLowerCase() === this.activeType.toLowerCase());
                    const s = this.q.trim().toLowerCase();
                    const matchesSearch = !s || title.toLowerCase().includes(s) || type.toLowerCase().includes(s);
                    return matchesType && matchesSearch;
                },

                loadMore() { this.showCount += 12; this.$nextTick(() => this._recache()); },

                openLightbox({ idx, src, title, type, typeId }) {
                    this.lightbox = { open:true, idx, src, title, type, typeId };
                    // allow arrow keys
                    window.addEventListener('keydown', this._keyHandler);
                },
                closeLightbox() {
                    this.lightbox.open = false;
                    window.removeEventListener('keydown', this._keyHandler);
                },
                nav(delta) {
                    if (!this._nodes.length) return;
                    let i = this.lightbox.idx + delta;
                    if (i < 0) i = this._nodes.length - 1;
                    if (i >= this._nodes.length) i = 0;
                    const n = this._nodes[i];
                    this.lightbox = { open:true, idx:i, src:n.src, title:n.title, type:n.type, typeId:n.typeId };
                },
                _keyHandler: null,
            };
        }
        // attach key handler prototype-safe
        (function attachNav(){
            const proto = galleryState.prototype || Object.getPrototypeOf(galleryState());
            if (!proto._keyHandlerAttached) {
                galleryState()._keyHandler = function(e){
                    if (!this.lightbox.open) return;
                    if (e.key === 'ArrowRight') this.nav(1);
                    if (e.key === 'ArrowLeft') this.nav(-1);
                    if (e.key === 'Escape') this.closeLightbox();
                };
                proto._keyHandlerAttached = true;
            }
        })();
    </script>
</x-app-layout>
