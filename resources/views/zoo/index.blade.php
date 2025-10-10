{{-- resources/views/zoo/index.blade.php --}}
<x-app-layout>
  <div x-data="zooPage()" x-init="init()" class="bg-black text-gray-100">

    {{-- HERO (low z so cards can scroll above video/fade) --}}
    <section
      id="heroSection"
      class="relative z-10 isolate w-full h-[72vh] sm:h-[80vh] lg:h-[90vh] overflow-hidden"
      :class="{
        'animate-fs-out': fsPhase==='leave',
        'fs-entering': fsEntering
      }"
      @mousemove.window="handleActivity"
      @touchstart.window.passive="handleActivity"
    >
      {{-- Video + overlays (don’t catch clicks) --}}
      <div class="absolute inset-0 z-0 pointer-events-none"
           style="-webkit-mask-image:linear-gradient(to bottom,black 88%,transparent 100%);
                  mask-image:linear-gradient(to bottom,black 88%,transparent 100%);">
        <video
          x-ref="heroVideo"
          :src="currentAnimal.video"
          playsinline muted loop autoplay preload="metadata"
          aria-hidden="true" tabindex="-1"
          class="pointer-events-none select-none absolute inset-0 w-full h-full object-cover"
        ></video>

        {{-- Dark cinematic look --}}
        <div x-show="!(isFullscreen && !showUI)" x-transition.opacity.duration.200ms
             class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
        <div x-show="!(isFullscreen && !showUI)" x-transition.opacity.duration.200ms
             class="pointer-events-none absolute inset-0 bg-[radial-gradient(85%_60%_at_50%_20%,rgba(255,255,255,0.06),transparent)]"></div>
      </div>

      {{-- Loading shimmer --}}
      <div x-show="isLoading" class="pointer-events-none absolute inset-0 z-0">
        <div class="w-full h-full animate-pulse bg-gradient-to-b from-black/50 via-black/30 to-black/70"></div>
      </div>

      {{-- Animal selector (mobile: full-width row; desktop: top-right chips) --}}
      <div
        x-show="mounted"
        x-transition:enter="transform transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="
          absolute z-40
          top-16 left-0 right-0 px-4       /* mobile */
          sm:top-8 sm:left-auto sm:right-6 /* desktop */
        "
      >
        <div
          class="flex items-center gap-2 sm:gap-3 overflow-x-auto no-scrollbar
                 bg-black/35 backdrop-blur-md border border-white/10 rounded-full
                 px-2 py-2 w-full sm:w-auto"
        >
          <template x-for="(a, idx) in animals" :key="a.slug">
            <button
              type="button"
              @click="selectAnimal(idx)"
              class="shrink-0 rounded-full px-3 py-1.5 text-xs sm:text-sm font-semibold transition border"
              :class="idx===currentIndex
                      ? 'bg-white text-black border-white/80'
                      : 'bg-white/10 text-white hover:bg-white/15 border-white/10'">
              <span x-text="a.short"></span>
            </button>
          </template>
        </div>
      </div>

      {{-- Heading / intro text --}}
      <div
        class="relative z-30 h-full flex items-start sm:items-end"
        :class="isFullscreen && !showUI ? 'opacity-0 cursor-none' : 'opacity-100'"
      >
        <div class="w-full">
          <div
            x-show="mounted"
            x-transition:enter="transform transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            class="max-w-7xl mx-auto px-4
                   pt-24 sm:pt-6 lg:pt-8    {{-- extra top space for chips on mobile --}}
                   pb-40 sm:pb-48 lg:pb-56
                   transform-gpu
                   -translate-y-6 sm:-translate-y-10 lg:-translate-y-14">
            <div class="max-w-3xl">
              <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur px-3 py-1 text-xs border border-white/10">
                <span>HuntLog</span><span class="text-white/60">•</span><span>Interaktīvs profils</span>
              </div>
              <h1 class="mt-3 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight drop-shadow-xl"
                  x-text="currentAnimal.title"></h1>
              <p class="mt-3 text-sm sm:text-base text-gray-200/90 max-w-xl" x-text="currentAnimal.lead"></p>
            </div>
          </div>
        </div>
      </div>

      {{-- ABSOLUTE CONTROL BAR OVERLAY --}}
      <div
        x-show="mounted"
        x-transition:enter="transform transition ease-out duration-500 delay-100"
        x-transition:enter-start="opacity-0 translate-y-4 scale-[0.98]"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="absolute inset-x-0 z-[9999] pointer-events-auto bottom-24 sm:bottom-36 lg:bottom-40"
        :class="isFullscreen && !showUI ? 'opacity-0' : 'opacity-100'"
      >
        <div class="max-w-7xl mx-auto px-4">
          <div class="flex flex-wrap items-center gap-3 rounded-full bg-white/10 backdrop-blur-xl border border-white/10 px-2 py-2 shadow-[0_20px_60px_-20px_rgba(0,0,0,0.8)]">
            <button type="button" @click.stop.prevent="restartVideo()"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold
                           bg-white/10 hover:bg-white/15 border border-white/10 transition focus:outline-none focus:ring-2 focus:ring-white/40">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5v2a5 5 0 1 1-4.9 6.1h-2A7 7 0 1 0 12 5zM13 1 9 5l4 4V6a6 6 0 1 1-6 6H5a8 8 0 1 0 8-8V1z"/></svg>
              Atkārtot
            </button>

            <button type="button" x-show="!isPlaying" @click.stop.prevent="startVideo()"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold
                           bg-white text-black hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-white/60">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7L8 5z"/></svg>
              Atskaņot
            </button>

            <button type="button" @click.stop.prevent="toggleFullscreen()"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold
                           bg-white/10 hover:bg-white/15 border border-white/10 transition focus:outline-none focus:ring-2 focus:ring-white/40">
              <svg x-show="!isFullscreen" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 14H5v5h5v-2H7v-3zm12 0h-2v3h-3v2h5v-5zM7 5h3V3H5v5h2V5zm10 0h-3V3h5v5h-2V5z"/></svg>
              <svg x-show="isFullscreen" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M9 7H7v2H5V5h4v2zm10 0h-2V5h-4v2h2v2h2V5zm-2 10h-2v2h-2v4h4v-2h2v-4zM9 17H7v2H5v4h4v-2h2v-4H9z"/></svg>
              Pilnekrāns
            </button>
          </div>

          {{-- Progress --}}
          <div class="mt-3 h-1.5 w-full max-w-xl rounded-full bg-white/10 overflow-hidden">
            <div class="h-full bg-white/80 transition-[width] duration-150" :style="`width: ${progress}%`"></div>
          </div>
        </div>
      </div>

      {{-- FS cue (soft pulse) --}}
      <div class="pointer-events-none absolute inset-0 z-0 transition-opacity duration-300"
           :class="isFsAnimating ? 'opacity-100' : 'opacity-0'"
           style="background:radial-gradient(60% 60% at 50% 50%, rgba(255,255,255,0.04), transparent 70%);"></div>
    </section>

    {{-- CARDS --}}
    <section class="relative -mt-24 md:-mt-32 lg:-mt-40 z-20">
      <div class="max-w-7xl mx-auto px-4 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            x-show="mounted"
            x-transition:enter="transform transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="rounded-3xl border border-white/10 bg-white/[0.06] backdrop-blur-xl p-6 shadow-[0_20px_60px_-20px_rgba(0,0,0,0.8)]">
            <h3 class="text-lg font-semibold">Medību mēneši</h3>
            <p class="mt-3 text-gray-100 leading-relaxed" x-text="currentAnimal.huntMonths.join(', ')"></p>
          </div>

          <div
            x-show="mounted"
            x-transition:enter="transform transition ease-out duration-500 delay-75"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="rounded-3xl border border-white/10 bg-white/[0.06] backdrop-blur-xl p-6 shadow-[0_20px_60px_-20px_rgba(0,0,0,0.8)]">
            <h3 class="text-lg font-semibold">Raksturs</h3>
            <p class="mt-3 text-gray-100 leading-relaxed" x-text="currentAnimal.traits"></p>
          </div>

          <div
            x-show="mounted"
            x-transition:enter="transform transition ease-out duration-500 delay-150"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="rounded-3xl border border-white/10 bg-white/[0.06] backdrop-blur-xl p-6 shadow-[0_20px_60px_-20px_rgba(0,0,0,0.8)]">
            <h3 class="text-lg font-semibold">Interesanti fakti</h3>
            <ul class="mt-3 space-y-2 text-gray-100 text-sm leading-relaxed list-disc list-inside">
              <template x-for="fact in currentAnimal.facts" :key="fact">
                <li x-text="fact"></li>
              </template>
            </ul>
          </div>
        </div>
      </div>
    </section>

    {{-- AUDIO (WaveSurfer waveform) --}}
    <section class="relative z-20">
      <div class="max-w-7xl mx-auto px-4 pb-16">
        <div class="rounded-3xl border border-white/10 bg-white/[0.06] backdrop-blur-xl p-6 shadow-[0_20px_60px_-20px_rgba(0,0,0,0.8)]">
          <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-white/10 border border-white/10">🎧</span>
              <h3 class="text-lg font-semibold">Skaņas</h3>
              <span class="ml-2 text-xs px-2 py-1 rounded-full bg-white/10 border border-white/10" x-text="currentAnimal.short"></span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
              <button type="button" @click.stop.prevent="audioPlayPause()"
                class="inline-flex justify-center items-center gap-2 rounded-full px-5 py-3 font-semibold
                       bg-white/10 hover:bg-white/15 border border-white/10 transition
                       focus:outline-none focus:ring-2 focus:ring-white/40 w-full sm:w-auto"
                :disabled="!currentAnimal.audio"
                :class="!currentAnimal.audio ? 'opacity-50 cursor-not-allowed' : ''">
                <svg x-show="!audioIsPlaying" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7L8 5z"/></svg>
                <svg x-show="audioIsPlaying" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
                <span x-text="audioIsPlaying ? 'Pauze' : 'Atskaņot'"></span>
              </button>
              <button type="button" @click.stop.prevent="audioStop()"
                class="inline-flex justify-center items-center gap-2 rounded-full px-5 py-3 font-semibold
                       bg-white/5 hover:bg-white/10 border border-white/10 transition
                       w-full sm:w-auto"
                :disabled="!currentAnimal.audio"
                :class="!currentAnimal.audio ? 'opacity-50 cursor-not-allowed' : ''">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h12v12H6z"/></svg>
                Stop
              </button>
            </div>
          </div>

          <div class="mt-4">
            {{-- IMPORTANT: full width so canvas gets a real size --}}
            <div x-ref="waveContainer" class="w-full h-20 sm:h-24 rounded-xl overflow-hidden bg-black/30 border border-white/10"></div>

            <div class="mt-2 h-1.5 w-full rounded-full bg-white/10 overflow-hidden">
              <div class="h-full bg-white/80 transition-[width] duration-150" :style="`width: ${audioProgress}%`"></div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-gray-300">
              <div>
                <span class="px-2 py-1 rounded bg-white/5 border border-white/10">Formāts: MP3</span>
                <span class="ml-2 px-2 py-1 rounded bg-white/5 border border-white/10">Paraugs: 44.1 kHz</span>
              </div>
              <div><span x-text="audioTimeLabel"></span></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  {{-- FS transition, pre-enter + exit keyframes & reduced motion support --}}
  <style>
    /* mobile scrollbar hide for chip row */
    .no-scrollbar{scrollbar-width:none;}
    .no-scrollbar::-webkit-scrollbar{display:none;}

    #heroSection.fs-entering { transform: scale(0.98); opacity: 0.85; }
    .animate-fs-out  { animation: fsOut 340ms ease both; }
    @keyframes fsOut { from { transform: scale(1.02); opacity: 1; } to { transform: scale(1.00); opacity: 1; } }
    #heroSection{transition:transform 320ms ease,opacity 220ms ease;}
    :fullscreen #heroSection{transform:scale(1.02);}
    @media (prefers-reduced-motion:reduce){
      [x-cloak],[x-show],[x-transition]{transition:none!important;animation:none!important}
      #heroSection,:fullscreen #heroSection{transition:none!important;transform:none!important}
      .animate-fs-out{animation:none!important}
    }
  </style>

  <script>
    function zooPage(){
      let hideTimer=null, fsAnimTimer=null;
      const fmt = t => !isFinite(t) ? '00:00' :
        `${String(Math.floor(t/60)).padStart(2,'0')}:${String(Math.floor(t%60)).padStart(2,'0')}`;

      return{
        // flags
        mounted:false,
        fsPhase:'idle',
        fsEntering:false,
        _initialized:false,   // guard for double init (HMR/Alpine)
        _wfBuildId:0,         // token to ignore late events
        ro:null,              // ResizeObserver

        // indices
        currentIndex: 0,

        // animals
        animals: [
          {
            slug:'briedis', short:'Briedis', title:'Briedis (Red Deer)',
            lead:'Lielākais briežu dzimtas pārstāvis Latvijā; riestā tēviņi rēk un cīnās ar ragiem.',
            video:"{{ asset('storage/videos/briedis.mp4') }}",
            audio:"{{ asset('storage/audio/briedis.mp3') }}",
            huntMonths:['Septembris','Oktobris','Novembris'],
            traits:'Modrs, piesardzīgs, aktīvs krēslā; turas mežmalās un pļavās.',
            facts:['Ragi krīt katru gadu un ataug pavasarī.','Riesta rēciens dzirdams pat kilometru attālumā.','Barība: zāles, dzinumi, mizas, lapas.']
          },
          {
            slug:'lacis', short:'Lācis', title:'Lācis (Brown Bear)',
            lead:'Spēcīgs, inteliģents un pārsvarā vientuļš dzīvnieks; meklē barību mežos un purvos.',
            video:"{{ asset('storage/videos/lacis.mp4') }}",
            audio:"{{ asset('storage/audio/lacis.mp3') }}",
            huntMonths:['Stingri regulēta/ierobežota sezona — skatīt aktuālos noteikumus'],
            traits:'Vientuļš, uzmanīgs; aktīvāks krēslā un naktī. Ziemā dodas ziemas miegā.',
            facts:['Uzturs ir jaukēdāja — ogas, kukaiņi, saknes, kritalas un mazāks medījums.','Lieliska oža (daudz labāka par cilvēka); dzirde arī ļoti attīstīta.','Spēj skriet pārsteidzoši ātri un peldēt lielus attālumus.']
          },
          {
            slug:'lapsa', short:'Lapsa', title:'Lapsa (Red Fox)',
            lead:'Gudrs plēsējs ar lielisku dzirdi; pielāgojas dažādām biotopiem, tai skaitā pie cilvēka.',
            video:"{{ asset('storage/videos/lapsa.mp4') }}",
            audio:"{{ asset('storage/audio/lapsa.mp3') }}",
            huntMonths:['Visu gadu'],
            traits:'Viltīga, ziņkārīga, aktīva krēslā un naktī.',
            facts:['Medī sīkus grauzējus, putnus un ēd arī ogas.','Komunikācijai izmanto vairākus balsu tipus (kliedzieni, rējieni).','Aste (“slota”) palīdz līdzsvarot skrējienu.']
          },
          {
            slug:'lusis', short:'Lūsis', title:'Lūsis (Eurasian Lynx)',
            lead:'Slepenīgs meža plēsējs; retāk sastopams, medīšanas noteikumi stingri regulēti.',
            video:"{{ asset('storage/videos/lusis.mp4') }}",
            audio:"{{ asset('storage/audio/lusis.mp3') }}",
            huntMonths:['— (aizsargājama vai stingri regulēta)'],
            traits:'Klusa, uzmanīga; medī no slēpņa vai izsekojot.',
            facts:['Raksturīgas “sānu bārdas” un melni kušķi uz ausīm.','Galvenais medījums — stirnas un zaķi.','Lieliska redze krēslā un naktī.']
          },
          {
            slug:'vilks', short:'Vilks', title:'Vilks (Grey Wolf)',
            lead:'Inteliģents un ļoti sociāls plēsējs; pārvietojas baros un medī stratēģiski.',
            video:"{{ asset('storage/videos/vilks.mp4') }}",
            audio:"{{ asset('storage/audio/vilks.mp3') }}",
            huntMonths:['Sezona regulēta; skatīt aktuālos noteikumus'],
            traits:'Bara plēsējs, modrs un piesardzīgs; aktīvāks krēslā un naktī.',
            facts:['Sazinās ar gaudošanu, ķermeņa valodu un smaržatzīmēm.','Spēj veikt desmitiem kilometru dienā pārtikas meklējumos.','Uzturs: stirnas, brieži, mežacūkas, kā arī sīkie zīdītāji.']
          }
        ],

        get currentAnimal(){ return this.animals[this.currentIndex] },

        // video state
        isPlaying:false,isLoading:true,isFullscreen:false,showUI:true,isFsAnimating:false,progress:0,

        // audio state
        wavesurfer:null,audioIsPlaying:false,audioProgress:0,audioTimeLabel:'00:00 / 00:00',

        // ---------- Video controls ----------
        async startVideo(){
          const v=this.$refs.heroVideo;if(!v)return;
          try{ v.muted=true; await v.play(); this.isPlaying=true; }catch{ this.isPlaying=false; }
        },
        async restartVideo(){
          const v=this.$refs.heroVideo;if(!v)return;
          this.isLoading=true;
          try{ v.pause(); v.currentTime=0; v.load(); await v.play(); this.isPlaying=true; }catch{ this.isPlaying=false; }
        },
        async toggleFullscreen(){
          const section=document.getElementById('heroSection');
          const video=this.$refs.heroVideo;
          this.triggerFsOverlay();
          try{
            if(document.fullscreenElement || document.webkitFullscreenElement){
              await (document.exitFullscreen?.()||document.webkitExitFullscreen?.()||Promise.resolve());
              return;
            }
            this.fsEntering = true;
            await this.$nextTick(); void section.offsetWidth;
            if(document.fullscreenEnabled && section.requestFullscreen){ await section.requestFullscreen(); return; }
            if(section.webkitRequestFullscreen){ section.webkitRequestFullscreen(); return; }
            if(video?.webkitEnterFullscreen){ video.webkitEnterFullscreen(); return; }
          }catch(e){
            console.warn('FS error',e); this.fsEntering=false;
          }
        },
        triggerFsOverlay(){
          clearTimeout(fsAnimTimer);
          this.isFsAnimating=true;
          fsAnimTimer=setTimeout(()=>this.isFsAnimating=false,320);
        },
        handleActivity(){ if(!this.isFullscreen) return; this.showUI=true; this.scheduleHide(); },
        scheduleHide(){ this.clearHideTimer(); hideTimer=setTimeout(()=>{ if(this.isFullscreen) this.showUI=false; },2000); },
        clearHideTimer(){ if(hideTimer){ clearTimeout(hideTimer); hideTimer=null; } },

        // ---------- Waveform helpers ----------
        async safeDestroyWaveform(){
          if (!this.wavesurfer) return;
          try { await this.wavesurfer.destroy(); }
          catch(e){ if (e?.name !== 'AbortError') console.warn('WaveSurfer destroy error:', e); }
          finally { this.wavesurfer = null; }
        },

        async buildWaveform(){
          const el = this.$refs.waveContainer;
          const token = ++this._wfBuildId;

          await this.safeDestroyWaveform();
          if (el) el.innerHTML = '';

          if (!this.currentAnimal.audio) {
            this.audioIsPlaying=false; this.audioProgress=0; this.audioTimeLabel='Nav audio';
            return;
          }
          if (!window.WaveSurfer || !el) {
            this.audioTimeLabel = 'Audio bibliotēka nav ielādēta';
            return;
          }

          // wait until container has real size
          await this.$nextTick();
          await new Promise(r => requestAnimationFrame(r));
          if (el.clientWidth < 8) {
            setTimeout(() => { if (token === this._wfBuildId) this.buildWaveform(); }, 120);
            return;
          }

          this.wavesurfer = WaveSurfer.create({
            container: el,
            waveColor: 'rgba(255,255,255,0.35)',
            progressColor: 'rgba(255,255,255,0.9)',
            cursorColor: 'transparent',
            barWidth: 2, barGap: 2, barRadius: 2,
            height: 96, responsive: true, interact: true, normalize: true,
          });

          const isFresh = () => token === this._wfBuildId;

          this.wavesurfer.on('ready', () => {
            if (!isFresh()) return;
            this.audioIsPlaying = false;
            this.audioProgress = 0;
            this.audioTimeLabel = `00:00 / ${fmt(this.wavesurfer.getDuration())}`;
            this.wavesurfer.setOptions({}); // ensure draw after decode
          });
          this.wavesurfer.on('play', () => { if (isFresh()) this.audioIsPlaying = true; });
          this.wavesurfer.on('pause', () => { if (isFresh()) this.audioIsPlaying = false; });
          this.wavesurfer.on('timeupdate', (t) => {
            if (!isFresh()) return;
            const d = this.wavesurfer.getDuration() || 0;
            this.audioProgress = d ? (t / d) * 100 : 0;
            this.audioTimeLabel = `${fmt(t)} / ${fmt(d)}`;
          });
          this.wavesurfer.on('error', (e) => {
            if (e?.name !== 'AbortError') console.warn('WaveSurfer error:', e);
            if (!isFresh()) return;
            this.audioIsPlaying = false;
            this.audioProgress = 0;
            this.audioTimeLabel = 'Audio nevar ielādēt';
          });

          this.wavesurfer.load(this.currentAnimal.audio);
        },

        audioPlayPause(){ this.wavesurfer?.playPause(); },
        audioStop(){
          if(!this.wavesurfer) return;
          this.wavesurfer.stop(); this.audioIsPlaying=false; this.audioProgress=0;
          const d=this.wavesurfer.getDuration()||0; this.audioTimeLabel=`00:00 / ${fmt(d)}`;
        },

        // ---------- Switch animal ----------
        async selectAnimal(idx){
          if(idx===this.currentIndex) return;
          this.currentIndex = idx;

          // Video swap
          const v=this.$refs.heroVideo;
          if(v){
            this.isLoading=true; this.isPlaying=false;
            v.pause(); v.src=this.currentAnimal.video; v.load();
            try{ v.muted=true; await v.play(); this.isPlaying=true; }catch{ this.isPlaying=false; }
          }

          // Waveform rebuild
          this.audioIsPlaying=false; this.audioProgress=0; this.audioTimeLabel='00:00 / 00:00';
          await this.buildWaveform();

          // re-trigger entrance anim
          this.mounted=false; requestAnimationFrame(()=>{ this.mounted = true; });
        },

        // ---------- lifecycle ----------
        async init(){
          if (this._initialized) return;
          this._initialized = true;

          const v=this.$refs.heroVideo;
          if(v){
            v.addEventListener('loadeddata',()=>this.isLoading=false);
            v.addEventListener('waiting',()=>this.isLoading=true);
            v.addEventListener('playing',()=>{this.isLoading=false; this.isPlaying=true;});
            v.addEventListener('timeupdate',()=>{ if(v.duration) this.progress=(v.currentTime/v.duration)*100; });
          }
          this.startVideo();

          // fullscreen enter/leave
          const onFsChange = () => {
            const entering = !!(document.fullscreenElement || document.webkitFullscreenElement);
            this.isFullscreen = entering;
            this.fsEntering = false;
            this.fsPhase = entering ? 'enter' : 'leave';
            if (entering) { this.showUI = true; this.scheduleHide(); }
            else { this.showUI = true; this.clearHideTimer(); }
            this.triggerFsOverlay();
            setTimeout(()=>{ this.fsPhase='idle'; }, 360);
          };
          document.addEventListener('fullscreenchange', onFsChange);
          document.addEventListener('webkitfullscreenchange', onFsChange);

          // initial waveform
          await this.buildWaveform();

          // redraw on container resize
          const el = this.$refs.waveContainer;
          if ('ResizeObserver' in window && el) {
            this.ro = new ResizeObserver(() => {
              if (this.wavesurfer) this.wavesurfer.setOptions({});
            });
            this.ro.observe(el);
          }

          // entrance animations
          requestAnimationFrame(()=>{ this.mounted = true; });
        }
      }
    }
  </script>
</x-app-layout>
