<x-app-layout>
    <section class="relative min-h-[92vh] flex items-center overflow-hidden">
        <div id="hero-bg" class="absolute inset-0 will-change-transform">
            <video
                class="absolute inset-0 w-full h-full object-cover"
                src="{{ asset('storage/videos/hunting.mp4') }}"
                playsinline
                muted
                loop
                autoplay
                preload="metadata"
                poster="https://images.pexels.com/photos/167699/pexels-photo-167699.jpeg"
                aria-hidden="true"
                tabindex="-1"
            ></video>
        </div>

        {{-- Cinematic darken + grain --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80"></div>
        <div class="absolute inset-0 pointer-events-none hero-grain"></div>

        {{-- Subtle fog (smoke) --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="fog fog-1"></div>
            <div class="fog fog-2"></div>
            <div class="fog fog-3"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-20">
            <div class="max-w-3xl" data-reveal-group>
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-4 py-1.5 text-emerald-300 text-xs tracking-widest uppercase reveal">
                    Jauna paaudze medību žurnālam
                </span>

                <h1 class="mt-6 text-5xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight text-white reveal">
                    Laipni lūdzam <span class="text-emerald-400">HuntLog</span>
                </h1>

                <p class="mt-6 text-lg sm:text-xl text-gray-300 max-w-2xl reveal">
                    Reāllaika ieskati, precīzi pieraksti un kopiena, kas domā tāpat kā Tu.
                    Plāno, seko progresam un dalies droši — viss vienā vietā.
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-4 reveal">
                    <a href="{{ route('register') }}" class="cta-primary group">
                        <span>Reģistrēties</span>
                        <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('gallery') }}" class="cta-secondary">Apskatīt galeriju</a>
                </div>

                <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm text-gray-300 reveal">
                    <div class="stat"><span class="stat-value">12k+</span><span class="stat-label">Ieraksti</span></div>
                    <div class="stat"><span class="stat-value">85+</span><span class="stat-label">Mednieku grupas</span></div>
                    <div class="stat"><span class="stat-value">99.9%</span><span class="stat-label">Uptime</span></div>
                    <div class="stat"><span class="stat-value">EU</span><span class="stat-label">Datu drošība</span></div>
                </div>
            </div>
        </div>

        {{-- Soft glows --}}
        <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-emerald-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-[-6rem] left-[-6rem] h-80 w-80 rounded-full bg-sky-500/10 blur-3xl"></div>
    </section>

    {{-- =========================
         MAP GLIMPSE
    ========================== --}}
<section class="relative py-20 sm:py-24">
  <div class="mx-auto max-w-7xl px-6">
    <div class="grid lg:grid-cols-2 gap-10 items-center" data-reveal-group>
      <div class="reveal">
        <h2 class="text-4xl sm:text-5xl font-extrabold text-white">Medību karte</h2>
        <p class="mt-4 text-lg text-gray-300">
          Skati maršrutus, notikumus un novērojumus uz interaktīvas kartes. Plāno izbraucienus un dalies ar komandu.
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="{{ route('map.index') }}" class="cta-primary">Apskatīt karti</a>
          <a href="{{ route('calendar') }}" class="cta-secondary">Plānot kalendārā</a>
        </div>
      </div>

      <div class="relative rounded-2xl overflow-hidden border border-white/10 bg-white/5 backdrop-blur reveal">
        <img
          src="{{ asset('storage/images/phone.jpg') }}"
          alt="Karte priekšskatījums"
          class="w-full h-[380px] object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/20"></div>

        {{-- Animated pins --}}
        <div class="absolute left-[18%] top-[55%]"><span class="pin"></span></div>
        <div class="absolute left-[62%] top-[38%]"><span class="pin delay-200"></span></div>
        <div class="absolute left-[78%] top-[70%]"><span class="pin delay-400"></span></div>
      </div>
    </div>
  </div>
</section>


    {{-- =========================
         FEATURE PILLARS (SPOTLIGHT + STAGGER)
    ========================== --}}
    <section class="relative py-24 sm:py-28">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="fog fog-4"></div>
            <div class="fog fog-5"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-3xl text-center" data-reveal-group>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-white reveal">Ko Tu vari darīt ar HuntLog?</h2>
                <p class="mt-4 text-gray-300 text-lg reveal">
                    Mēs apvienojam precizitāti, ātrumu un vienkāršību — lai Tu vari koncentrēties uz galveno.
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group id="feature-grid">
                <article class="feature-card reveal spotlight">
                    <div class="feature-card__icon">📊</div>
                    <h3 class="feature-card__title">Sekot aktivitātēm</h3>
                    <p class="feature-card__body">Izseko maršrutus, laika apstākļus un rezultātus — automatizēti un pārskatāmi.</p>
                    <a href="{{ route('calendar') }}" class="feature-card__link">Skatīt kalendāru</a>
                </article>

                <article class="feature-card reveal spotlight">
                    <div class="feature-card__icon">🤝</div>
                    <h3 class="feature-card__title">Pievienoties grupām</h3>
                    <p class="feature-card__body">Veido un pārvaldi komandas, piešķir lomas un sadarbojies droši.</p>
                    <a href="{{ route('contacts') }}" class="feature-card__link">Sazināties ar vadītāju</a>
                </article>

                <article class="feature-card reveal spotlight">
                    <div class="feature-card__icon">📌</div>
                    <h3 class="feature-card__title">Dalīties pieredzē</h3>
                    <p class="feature-card__body">Kopīgo pieredzi un padomus ar medniekiem visā Latvijā.</p>
                    <a href="{{ route('gallery') }}" class="feature-card__link">Apskatīt galeriju</a>
                </article>
            </div>
        </div>
    </section>

    {{-- =========================
         TESTIMONIALS
    ========================== --}}
    <section class="relative py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-6" data-reveal-group>
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-white reveal">Ko saka mūsu lietotāji</h2>
                <p class="mt-3 text-gray-300 reveal">Reālas pieredzes no medību kopienas.</p>
            </div>

            <div class="mt-10 reveal">
                <div class="slider" x-data>
                    <div class="slider-track" id="testi-track" role="region" aria-label="Atsauksmju karuselis">
                        <figure class="slide">
                            <blockquote class="slide-quote">“HuntLog beidzot sakārtoja mūsu komandas plānošanu. Viss vienuviet un ātri.”</blockquote>
                            <figcaption class="slide-meta">
                                <img class="slide-avatar" src="https://i.pravatar.cc/100?img=3" alt="Lietotāja foto">
                                <div><div class="slide-name">Jānis B.</div><div class="slide-role">Kluba vadītājs, Valmiera</div></div>
                            </figcaption>
                        </figure>

                        <figure class="slide">
                            <blockquote class="slide-quote">“Kalendārs un maršruti strādā lieliski. Redzu, kas notiek, arī esot mežā.”</blockquote>
                            <figcaption class="slide-meta">
                                <img class="slide-avatar" src="https://i.pravatar.cc/100?img=5" alt="Lietotāja foto">
                                <div><div class="slide-name">Agnese R.</div><div class="slide-role">Medniece, Kurzeme</div></div>
                            </figcaption>
                        </figure>

                        <figure class="slide">
                            <blockquote class="slide-quote">“Vienkārši lietojams un drošs. Iesaku visiem mūsu jaunpienācējiem.”</blockquote>
                            <figcaption class="slide-meta">
                                <img class="slide-avatar" src="https://i.pravatar.cc/100?img=12" alt="Lietotāja foto">
                                <div><div class="slide-name">Mārtiņš K.</div><div class="slide-role">Komandas koordinators</div></div>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="slider-dots" id="testi-dots" role="tablist" aria-label="Atsauksmju slēdzis"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================
         CTA
    ========================== --}}
    <section class="relative overflow-hidden rounded-none">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1558022103-603c34ab10ce?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0"
                 alt="Forest background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/70"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-6 py-24 sm:py-28" data-reveal-group>
            <div class="max-w-3xl reveal">
                <h3 class="text-4xl sm:text-5xl font-extrabold text-emerald-400">Sāc savu medību pieredzi ar HuntLog</h3>
                <p class="mt-4 text-lg text-gray-200">
                    Reģistrējies, pievieno savu komandu un atklāj, cik vienkārši ir plānot un analizēt katru gājienu.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="cta-primary">Reģistrēties</a>
                    <a href="{{ route('contacts') }}" class="cta-secondary">Sazināties</a>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================
         FOOTER
    ========================== --}}
    <footer class="relative border-t border-white/10 bg-black/60 backdrop-blur">
        <div class="mx-auto max-w-7xl px-6 py-10 flex flex-col md:flex-row items-center justify-between gap-6 text-gray-400">
            <p class="text-sm">&copy; {{ date('Y') }} HuntLog. Visas tiesības aizsargātas.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('contacts') }}" class="hover:text-emerald-400">Kontakti</a>
                <a href="{{ route('gallery') }}" class="hover:text-emerald-400">Galerija</a>
            </div>
        </div>
    </footer>
</x-app-layout>

{{-- ================
     INTERACTIVITY
================ --}}
<script>
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Parallax on the wrapper (#hero-bg). Works with video just like the image.
  const hero = document.getElementById('hero-bg');
  if (hero && !prefersReduced) {
    document.addEventListener('mousemove', (e) => {
      const x = (e.clientX / window.innerWidth - 0.5) * 16;
      const y = (e.clientY / window.innerHeight - 0.5) * 16;
      hero.style.transform = `translate(${x}px, ${y}px) scale(1.05)`;
    });
  }

  function setupReveals() {
    const groups = document.querySelectorAll('[data-reveal-group]');
    const singleObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('show'); });
    }, { threshold: 0.18 });

    const groupObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const items = entry.target.querySelectorAll('.reveal');
        items.forEach((el, i) => {
          el.style.transitionDelay = `${i * 120}ms`;
          singleObserver.observe(el);
        });
        groupObserver.unobserve(entry.target);
      });
    }, { threshold: 0.15 });

    document.querySelectorAll('.reveal:not([data-reveal-group] .reveal)').forEach(el => singleObserver.observe(el));
    groups.forEach(g => groupObserver.observe(g));
  }
  if (!prefersReduced) setupReveals(); else document.querySelectorAll('.reveal').forEach(el => el.classList.add('show'));

  function setupSpotlight() {
    document.querySelectorAll('.spotlight').forEach(card => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        card.style.setProperty('--spot-x', `${x}%`);
        card.style.setProperty('--spot-y', `${y}%`);
      });
      card.addEventListener('mouseleave', () => {
        card.style.setProperty('--spot-x', '50%');
        card.style.setProperty('--spot-y', '50%');
      });
    });
  }
  setupSpotlight();

  // Testimonials slider (unchanged)
  (function(){
    const track = document.getElementById('testi-track');
    if (!track) return;
    const slides = Array.from(track.children);
    const dotsWrap = document.getElementById('testi-dots');
    let index = 0, timer;

    slides.forEach((_, i) => {
      const btn = document.createElement('button');
      btn.type = 'button'; btn.className = 'dot';
      btn.setAttribute('aria-label', `Rādīt atsauksmi ${i+1}`);
      btn.addEventListener('click', () => go(i, true));
      dotsWrap.appendChild(btn);
    });
    const dots = Array.from(dotsWrap.children);

    function go(i, user=false) {
      index = (i + slides.length) % slides.length;
      track.style.transform = `translateX(-${index * 100}%)`;
      dots.forEach((d, di) => d.classList.toggle('active', di === index));
      if (user) resetAutoplay();
    }
    function autoplay(){ timer = setInterval(() => go(index+1), 4500); }
    function resetAutoplay(){ if (timer) clearInterval(timer); if (!prefersReduced) autoplay(); }

    let startX=0, currentX=0, dragging=false;
    const start = x => { dragging=true; startX=x; track.classList.add('grabbing'); };
    const move  = x => { if(!dragging) return; currentX = x-startX; track.style.transform = `translateX(calc(-${index*100}% + ${currentX}px))`; };
    const end   = () => {
      if(!dragging) return; dragging=false; track.classList.remove('grabbing');
      if (Math.abs(currentX) > window.innerWidth*0.12) go(index + (currentX<0?1:-1), true); else go(index);
      currentX=0;
    };
    track.addEventListener('mousedown', e=>start(e.clientX));
    window.addEventListener('mousemove', e=>move(e.clientX));
    window.addEventListener('mouseup', end);
    track.addEventListener('touchstart', e=>start(e.touches[0].clientX), {passive:true});
    window.addEventListener('touchmove', e=>move(e.touches[0].clientX), {passive:true});
    window.addEventListener('touchend', end);
    window.addEventListener('keydown', e=>{ if(e.key==='ArrowRight') go(index+1,true); if(e.key==='ArrowLeft') go(index-1,true); });

    go(0); if (!prefersReduced) autoplay();
  })();
</script>

{{-- ================
     PREMIUM STYLES (unchanged, just works with video)
================ --}}
<style>
.hero-grain {
  background-image:
    radial-gradient(ellipse at top left, rgba(255,255,255,0.06), transparent 40%),
    radial-gradient(ellipse at bottom right, rgba(0,0,0,0.3), transparent 40%);
  mix-blend-mode: overlay;
}

.fog {
  position:absolute; width:40vw; height:40vw; min-width:360px; min-height:360px;
  background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 60%);
  filter: blur(60px); opacity:.35; transform: translateZ(0);
  animation: fogDrift 30s ease-in-out infinite;
}
.fog-1{ top:60%; left:-10%; animation-delay:0s; }
.fog-2{ top:10%; right:-15%; animation-duration:36s; animation-delay:6s; opacity:.30; }
.fog-3{ bottom:-10%; left:20%; animation-duration:40s; animation-delay:12s; opacity:.28; }
.fog-4{ top:30%; left:5%; animation-duration:34s; opacity:.25; }
.fog-5{ bottom:20%; right:8%; animation-duration:38s; opacity:.22; }
@keyframes fogDrift { 0%{transform:translate(0,0) scale(1)} 50%{transform:translate(60px,-40px) scale(1.15)} 100%{transform:translate(0,0) scale(1)} }

.cta-primary {
  display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.5rem;
  border-radius:.5rem; font-weight:600; background:#059669; color:#fff;
  box-shadow:0 10px 25px rgba(6,95,70,.35); transition:transform .2s, background .2s, box-shadow .2s;
}
.cta-primary:hover { background:#047857; transform:translateY(-2px); box-shadow:0 14px 32px rgba(6,95,70,.45); }
.cta-primary:focus { outline:2px solid #10b981; outline-offset:2px; }

.cta-secondary {
  display:inline-flex; align-items:center; padding:.75rem 1.5rem; border-radius:.5rem;
  font-weight:600; background:rgba(255,255,255,.1); color:#fff; border:1px solid rgba(255,255,255,.1);
  transition:color .2s, background .2s, border-color .2s;
}
.cta-secondary:hover { color:#6ee7b7; background:rgba(255,255,255,.15); border-color:rgba(16,185,129,.5); }

.stat { border-radius:.75rem; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); padding:.75rem 1rem; backdrop-filter: blur(6px); }
.stat-value { display:block; font-size:1.375rem; font-weight:800; color:#fff; }
.stat-label { font-size:.7rem; text-transform:uppercase; letter-spacing:.2em; color:#9ca3af; }

.feature-card {
  position: relative;
  border-radius:1rem;
  background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
  border:1px solid rgba(255,255,255,.1);
  padding:2rem;
  backdrop-filter: blur(6px);
  box-shadow:0 12px 36px rgba(0,0,0,.35);
  transform: translateY(16px) scale(.98);
  opacity:0; transition: transform .7s ease, opacity .7s ease, box-shadow .3s ease;
  --spot-x: 50%; --spot-y: 50%;
}
.feature-card:hover { box-shadow:0 18px 56px rgba(0,0,0,.5), inset 0 0 0 1px rgba(16,185,129,.25); }
.feature-card::before {
  content:""; position:absolute; inset:-2px; border-radius:inherit; padding:2px;
  background:
    radial-gradient(120px 120px at var(--spot-x) var(--spot-y),
      rgba(16,185,129,.85), rgba(16,185,129,.35) 35%, rgba(16,185,129,0) 60%) border-box;
  -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
  -webkit-mask-composite: xor; mask-composite: exclude;
  opacity:0; transition: opacity .25s ease; pointer-events:none;
}
.feature-card:hover::before { opacity:1; }

.feature-card__icon { font-size:1.75rem; margin-bottom:1rem; }
.feature-card__title { font-size:1.25rem; font-weight:800; color:#fff; transition: color .2s ease; }
.feature-card__body  { margin-top:.5rem; color:#d1d5db; line-height:1.7; }
.feature-card__link  { margin-top:1rem; display:inline-flex; align-items:center; color:#34d399; transition:color .2s ease; }
.feature-card:hover .feature-card__title { color:#34d399; }
.feature-card:hover .feature-card__link  { color:#10b981; }

.pin { position:relative; display:block; width:12px; height:12px; border-radius:9999px; background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,.5); }
.pin::after { content:""; position:absolute; inset:-8px; border-radius:9999px; background: radial-gradient(circle, rgba(16,185,129,.35) 0%, rgba(16,185,129,0) 60%); animation: ping 1.8s ease-out infinite; }
.delay-200::after { animation-delay:.2s; } .delay-400::after { animation-delay:.4s; }
@keyframes ping { 0%{transform:scale(.6);opacity:.8} 100%{transform:scale(2.4);opacity:0} }

.slider { position:relative; overflow:hidden; }
.slider-track { display:flex; width:300%; transition: transform .6s cubic-bezier(.22,.61,.36,1); cursor:grab; }
.slider-track.grabbing { cursor:grabbing; }
.slide { width:100%; flex:0 0 100%; padding:2rem; display:grid; gap:1.25rem;
  background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
  border:1px solid rgba(255,255,255,.08); border-radius:1.25rem; backdrop-filter: blur(6px); color:#e5e7eb;
}
.slide-quote { font-size:1.125rem; line-height:1.7; }
.slide-meta { display:flex; align-items:center; gap:.75rem; }
.slide-avatar { width:44px; height:44px; border-radius:9999px; object-fit:cover; }
.slide-name { font-weight:700; color:#fff; }
.slide-role { font-size:.875rem; color:#9ca3af; }
.slider-dots { display:flex; justify-content:center; gap:.5rem; margin-top:1rem; }
.dot { width:8px; height:8px; border-radius:9999px; background:rgba(255,255,255,.3); transition: transform .2s, background .2s; }
.dot.active { transform:scale(1.3); background:#10b981; }

.reveal { opacity:0; transform: translateY(16px) scale(.98); transition: opacity .6s ease, transform .6s ease; }
.reveal.show { opacity:1; transform: none; }

@media (prefers-reduced-motion: reduce) {
  #hero-bg { transform:none !important; }
  .reveal { transition: none; opacity: 1 !important; transform: none !important; }
  .slider-track { transition: none; }
  .fog { animation:none; opacity:.25; }
}
@media (min-width: 640px){ .slide { padding: 2.5rem; } }
</style>
