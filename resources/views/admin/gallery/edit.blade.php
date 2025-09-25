<x-app-layout>
    {{-- Fons + dūmaka --}}
    <section class="relative min-h-screen pt-20 sm:pt-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-gray-900 to-black"></div>
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="fog fog-1"></div>
            <div class="fog fog-2"></div>
            <div class="fog fog-3"></div>
        </div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" data-reveal-group>
            {{-- Galvene --}}
            <header class="reveal mb-6">
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Rediģēt galerijas attēlu</h1>
                <p class="mt-2 text-gray-300">Atjauno medību tipu, nosaukumu un (ja vēlies) nomaini pašu attēlu.</p>
            </header>

            {{-- Forma --}}
            <section class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.gallery.update', $item) }}" enctype="multipart/form-data" class="space-y-7" id="galleryEditForm">
                    @csrf
                    @method('PUT')

                    {{-- Medību tips --}}
                    <div>
                        <label for="hunting_type_id" class="block text-sm font-semibold text-white mb-1.5">Medību tips</label>
                        <select id="hunting_type_id" name="hunting_type_id"
                                class="w-full bg-gray-800/80 text-white rounded-lg p-3 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500">
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected(old('hunting_type_id', $item->hunting_type_id)==$type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('hunting_type_id') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nosaukums --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-white mb-1.5">Nosaukums <span class="text-gray-400 font-normal">(nav obligāts)</span></label>
                        <input id="title" name="title" value="{{ old('title', $item->title) }}"
                               class="w-full bg-gray-800/80 text-white rounded-lg p-3 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                               placeholder="Piem., “Rīta medības 05:30”">
                        @error('title') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Attēla nomaiņa (drag & drop + priekšskatījums) --}}
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1.5">Aizstāt attēlu <span class="text-gray-400 font-normal">(nav obligāts)</span></label>

                        <div id="dropzone"
                             class="relative rounded-xl border border-dashed border-gray-600 bg-gray-900/60 p-5 flex flex-col items-center justify-center text-gray-300 hover:border-emerald-500/70 transition">
                            <input id="image" type="file" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" />
                            <svg class="h-8 w-8 mb-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h10a4 4 0 000-8 6 6 0 10-11.472 2.132M12 12v9m0-9l-3 3m3-3l3 3"/>
                            </svg>
                            <p class="text-sm text-center">
                                Ievelc un nomet vai <span class="text-emerald-300 font-semibold">izvēlies failu</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400">Atbalsts: JPG, PNG, WEBP. Ieteicams &ge; 1600px platums.</p>
                        </div>

                        {{-- Priekšskatījums jaunajam failam (ja izvēlēts) --}}
                        <div id="previewWrap" class="hidden mt-4 rounded-xl border border-white/10 bg-white/5 p-3">
                            <div class="flex items-start gap-3">
                                <img id="previewImg" src="" alt="Priekšskatījums" class="h-28 w-28 object-cover rounded-lg shadow" />
                                <div class="flex-1">
                                    <p class="text-sm text-white font-medium">Jaunais attēls</p>
                                    <p id="previewMeta" class="text-xs text-gray-400 mt-1">—</p>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <button type="button" id="clearSelection"
                                                class="inline-flex px-3 py-1.5 rounded-lg bg-white/10 text-white border border-white/10 hover:bg-white/15 transition">
                                            Noņemt izvēli
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Esošais attēls --}}
                        @php
                            $currentUrl = method_exists($item,'url') ? $item->url() : (isset($item->image_path) ? asset('storage/'.$item->image_path) : '');
                        @endphp
                        @if($currentUrl)
                            <div class="mt-5">
                                <p class="text-sm text-gray-300 mb-2 font-semibold">Pašreizējais attēls:</p>
                                <div class="relative">
                                    <img src="{{ $currentUrl }}" alt="Pašreizējais attēls"
                                         class="w-full max-h-72 object-cover rounded-xl shadow-lg">
                                    <button type="button" id="openCurrent"
                                            class="btn-icon absolute top-3 right-3"
                                            title="Skatīt pilnā izmērā">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-4.553a1 1 0 00-1.414-1.414L13.586 8.586M21 21H3V3h7l11 11v7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @error('image') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pogas --}}
                    <div class="pt-2 flex flex-col-reverse sm:flex-row justify-end gap-2">
                        <a href="{{ route('admin.gallery.index') }}"
                           class="inline-flex justify-center px-4 py-2 rounded-lg bg-white/10 text-white border border-white/10 hover:border-gray-400/50 hover:bg-white/15 transition">
                            Atcelt
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center px-4 py-2 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 shadow-lg shadow-emerald-900/30 focus:ring-2 focus:ring-emerald-500 transition">
                            Atjaunināt
                        </button>
                    </div>
                </form>
            </section>
        </div>

        {{-- Lightbox pašreizējam attēlam --}}
        <div id="lightbox" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/90 p-4">
            <button id="lb-close" class="absolute top-4 right-4 btn-icon" title="Aizvērt">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            @if($currentUrl)
                <img id="lb-img" src="{{ $currentUrl }}" alt="" class="max-h-[90vh] max-w-[90vw] rounded-xl shadow-2xl">
            @endif
        </div>
    </section>

    {{-- Scripts --}}
    <script>
    (function(){
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Reveal stagger
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

        // Drag & drop + priekšskatījums
        const dz = document.getElementById('dropzone');
        const input = document.getElementById('image');
        const wrap = document.getElementById('previewWrap');
        const img  = document.getElementById('previewImg');
        const meta = document.getElementById('previewMeta');
        const clearBtn = document.getElementById('clearSelection');

        function humanFileSize(bytes){
            if (!bytes && bytes !== 0) return '—';
            const thresh = 1024;
            if (Math.abs(bytes) < thresh) return bytes + ' B';
            const units = ['KB','MB','GB','TB'];
            let u = -1;
            do { bytes /= thresh; ++u; } while (Math.abs(bytes) >= thresh && u < units.length - 1);
            return bytes.toFixed(1)+' '+units[u];
        }

        function setPreview(file){
            if (!file) { wrap?.classList.add('hidden'); return; }
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; };
            reader.readAsDataURL(file);

            // ielasa izmērus
            const tmp = new Image();
            tmp.onload = () => {
                meta.textContent = `${file.name} • ${humanFileSize(file.size)} • ${tmp.width}×${tmp.height}px`;
            };
            tmp.src = URL.createObjectURL(file);

            wrap?.classList.remove('hidden');
        }

        input?.addEventListener('change', (e)=> {
            const file = e.target.files?.[0];
            setPreview(file);
        });

        clearBtn?.addEventListener('click', ()=>{
            input.value = '';
            wrap?.classList.add('hidden');
            img.src = '';
            meta.textContent = '—';
        });

        ['dragenter','dragover'].forEach(evt => {
            dz?.addEventListener(evt, e => {
                e.preventDefault(); e.stopPropagation();
                dz.classList.add('ring-2','ring-emerald-500','bg-gray-900/80');
            });
        });
        ['dragleave','drop'].forEach(evt => {
            dz?.addEventListener(evt, e => {
                e.preventDefault(); e.stopPropagation();
                dz.classList.remove('ring-2','ring-emerald-500','bg-gray-900/80');
            });
        });
        dz?.addEventListener('drop', e => {
            const file = e.dataTransfer.files?.[0];
            if (!file) return;
            input.files = e.dataTransfer.files; // nodod inputam
            setPreview(file);
        });

        // Lightbox
        const lb = document.getElementById('lightbox');
        const lbClose = document.getElementById('lb-close');
        const openCurrent = document.getElementById('openCurrent');
        const closeLB = ()=>{ lb?.classList.add('hidden'); lb?.classList.remove('flex'); };

        openCurrent?.addEventListener('click', ()=>{ lb?.classList.remove('hidden'); lb?.classList.add('flex'); });
        lbClose?.addEventListener('click', closeLB);
        lb?.addEventListener('click', (e)=> { if (e.target === lb) closeLB(); });
        document.addEventListener('keydown', (e)=> { if (e.key === 'Escape') closeLB(); });

    })();
    </script>

    {{-- Stili --}}
    <style>
    /* Dūmaka / Fog */
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

    /* Reveal */
    [data-reveal-group] .reveal { opacity:0; transform: translateY(14px) scale(.98); transition: opacity .6s ease, transform .6s ease; }
    [data-reveal-group] .reveal.show { opacity:1; transform: none; }

    /* Ikonas poga */
    .btn-icon{
        display:inline-flex; align-items:center; justify-content:center;
        width:36px; height:36px; border-radius:.6rem;
        background: rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.12);
        color:#e5e7eb; transition: all .18s ease;
    }
    .btn-icon:hover{ color:#34d399; border-color: rgba(16,185,129,.5); background: rgba(255,255,255,.16); }
    </style>
</x-app-layout>
