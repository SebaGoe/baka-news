// Night Edition, reactions, poll voting, achievements, and retro sound.
(function () {
  // ---------- Night Edition ----------
  const root = document.documentElement;
  const nightBtn = document.getElementById('night-toggle');
  const isNight = () => root.getAttribute('data-theme') === 'night';
  function setNight(on) {
    if (on) root.setAttribute('data-theme', 'night'); else root.removeAttribute('data-theme');
    try { localStorage.setItem('baka_night', on ? '1' : '0'); } catch (e) {}
    if (nightBtn) {
      nightBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
      nightBtn.textContent = on ? 'Day edition' : 'Night edition';
    }
  }
  if (nightBtn) {
    setNight(isNight());
    nightBtn.addEventListener('click', () => { setNight(!isNight()); Sound.blip(); });
  }

  // ---------- Retro sound (off by default) ----------
  const Sound = (function () {
    let on = false, ctx = null;
    try { on = localStorage.getItem('baka_sound') === '1'; } catch (e) {}
    const reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    function tone(freq, ms, type) {
      if (!on || reduce) return;
      try {
        ctx = ctx || new (window.AudioContext || window.webkitAudioContext)();
        if (ctx.state === 'suspended' && ctx.resume) ctx.resume(); // browsers can auto-suspend
        const o = ctx.createOscillator(), g = ctx.createGain();
        o.type = type || 'square'; o.frequency.value = freq;
        g.gain.value = 0.04; o.connect(g); g.connect(ctx.destination);
        o.start(); o.stop(ctx.currentTime + ms / 1000);
      } catch (e) {}
    }
    const chip = document.createElement('button');
    chip.className = 'sound-toggle'; chip.type = 'button';
    const paint = () => (chip.textContent = on ? 'Sound: on' : 'Sound: off');
    paint();
    chip.addEventListener('click', () => {
      on = !on; try { localStorage.setItem('baka_sound', on ? '1' : '0'); } catch (e) {}
      paint(); if (on) tone(660, 90);
    });
    document.body.appendChild(chip);
    return {
      blip: () => tone(520, 60),
      coin: () => { tone(880, 70); setTimeout(() => tone(1180, 90), 70); },
      dialup: () => { [400, 900, 500, 1200, 300].forEach((f, i) => setTimeout(() => tone(f, 120, 'sawtooth'), i * 130)); }
    };
  })();
  window.BakaSound = Sound;

  // ---------- Reactions ----------
  const rx = document.getElementById('reactions');
  if (rx) {
    const id = rx.dataset.article;
    let mine = {};
    try { mine = JSON.parse(localStorage.getItem('baka_react_' + id) || '{}'); } catch (e) {}
    rx.querySelectorAll('.reactions__btn').forEach(btn => {
      const key = btn.dataset.emoji;
      if (mine[key]) btn.classList.add('is-mine');
      btn.addEventListener('click', () => {
        btn.classList.remove('bump'); void btn.offsetWidth; btn.classList.add('bump');
        Sound.blip();
        fetch('/article/react', {
          method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ id, emoji: key })
        }).then(r => r.json()).then(res => {
          if (!res.ok) return;
          Object.entries(res.counts).forEach(([k, c]) => {
            const el = rx.querySelector(`.reactions__count[data-count="${k}"]`);
            if (el) el.textContent = c;
          });
          mine[key] = true;
          try { localStorage.setItem('baka_react_' + id, JSON.stringify(mine)); } catch (e) {}
          btn.classList.add('is-mine');
        });
      });
    });
  }

  // ---------- Poll ----------
  const poll = document.getElementById('poll');
  if (poll) {
    poll.querySelectorAll('.poll__opt').forEach(opt => {
      opt.addEventListener('click', () => {
        if (poll.dataset.voted === '1') return;
        Sound.blip();
        fetch('/poll/vote', {
          method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ option: opt.dataset.option })
        }).then(r => r.json()).then(res => {
          if (!res.poll) return;
          poll.dataset.voted = '1';
          const total = res.poll.total || 0;
          res.poll.options.forEach(o => {
            const el = poll.querySelector(`.poll__opt[data-option="${o.id}"]`);
            if (!el) return;
            const pct = total ? Math.round(o.votes / total * 100) : 0;
            el.querySelector('.poll__bar').style.setProperty('--pct', pct + '%');
            el.querySelector('.poll__pct').textContent = pct + '%';
            if (String(o.id) === String(opt.dataset.option)) el.classList.add('is-mine');
          });
          const tot = poll.querySelector('.poll__total');
          if (tot) tot.textContent = total + ' votes \u00b7 results are legally meaningless';
        });
      });
    });
  }

  // ---------- Achievements ----------
  const KNOWN = ['konami', 'mascot-clicks', 'hidden-spot', 'secret-url', 'open-extra'];
  function eggs() { try { return JSON.parse(localStorage.getItem('baka_eggs') || '[]'); } catch (e) { return []; } }
  function paintEggs() {
    const el = document.getElementById('egg-count');
    if (el) el.textContent = eggs().filter(k => KNOWN.includes(k)).length;
  }
  paintEggs();
  document.addEventListener('baka:egg', (e) => {
    const found = eggs();
    if (!found.includes(e.detail.kind)) {
      found.push(e.detail.kind);
      try { localStorage.setItem('baka_eggs', JSON.stringify(found)); } catch (err) {}
      Sound.coin();
      paintEggs();
      const known = found.filter(k => KNOWN.includes(k)).length;
      if (known >= 4 && !localStorage.getItem('baka_egg_master')) {
        try { localStorage.setItem('baka_egg_master', '1'); } catch (err) {}
        setTimeout(() => window.BakaModal && window.BakaModal.open({ bar: 'Achievement',
          html: '<div class="modal__title">Secret Master</div><div class="modal__desc">You found nearly every secret. Bakabake salutes you.</div>' }), 600);
      }
    }
    if (e.detail.kind === 'secret-url') Sound.dialup();
  });
})();
