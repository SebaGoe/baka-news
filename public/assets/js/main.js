// Coupon redemption + Easter-egg engine (Konami, hidden spots, secret URL).
(function () {
  function confetti() {
    const colors = ['#9e2b25', '#3e6b3a', '#8a531b', '#1b4b8f', '#7a3468'];
    for (let i = 0; i < 60; i++) {
      const p = document.createElement('div');
      p.className = 'confetti-piece';
      p.style.background = colors[i % colors.length];
      p.style.left = Math.random() * 100 + 'vw';
      p.style.top = '-20px';
      document.body.appendChild(p);
      const dur = 1600 + Math.random() * 1400;
      p.animate([
        { transform: 'translateY(0) rotate(0)', opacity: 1 },
        { transform: `translateY(105vh) rotate(${720 * (Math.random() > .5 ? 1 : -1)}deg)`, opacity: .9 }
      ], { duration: dur, easing: 'ease-in' }).onfinish = () => p.remove();
    }
  }

  // A CSS barcode: random-width bars, no pictographic glyphs.
  function barcode() {
    let bars = '';
    for (let i = 0; i < 34; i++) {
      const w = 1 + Math.floor(Math.random() * 4);
      bars += `<i style="width:${w}px"></i>`;
    }
    return `<div class="barcode" aria-hidden="true">${bars}</div>`;
  }

  function redeem(data) {
    fetch('/coupons/redeem', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id: data.id })
    }).then(r => r.json()).then(res => {
      const html = `
        <div class="modal__title">${data.title}</div>
        <div class="modal__desc">${data.desc}</div>
        ${barcode()}
        <div class="modal__serial">Serial ${res.serial || '\u2014'}</div>
        <div class="modal__fine">${data.fine || ''}</div>`;
      window.BakaModal.open({ bar: 'Coupon redeemed', html });
      confetti();
      window.BakaSound && window.BakaSound.coin();
      const btn = document.querySelector(`[data-coupon*='"${data.id}"']`);
      if (btn) btn.classList.add('is-redeemed');
    });
  }

  document.querySelectorAll('.coupon').forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.dataset.unlock !== 'open') {
        window.BakaModal.open({ bar: 'Locked', html:
          '<div class="modal__title">Still locked</div><div class="modal__desc">This one needs a secret trick to reveal. Keep exploring the site.</div>' });
        return;
      }
      redeem(JSON.parse(btn.dataset.coupon));
    });
  });

  function reveal(kind) {
    const btn = document.querySelector(`.coupon[data-unlock="${kind}"]`);
    if (!btn) return false;
    btn.classList.remove('coupon--locked');
    const d = JSON.parse(btn.dataset.coupon);
    const mono = btn.querySelector('.coupon__mono');
    if (mono) mono.textContent = (d.title || '?').trim().charAt(0).toUpperCase();
    btn.querySelector('.coupon__title').textContent = d.title;
    btn.querySelector('.coupon__hint').textContent = 'Unlocked - tap to redeem';
    btn.dataset.unlock = 'open'; d.unlock = 'open';
    btn.dataset.coupon = JSON.stringify(d);
    return true;
  }

  window.BakaFX = { confetti };
  window.BakaEggs = {
    reveal,
    unlock(kind, message) {
      reveal(kind);
      window.BakaModal.open({ bar: 'Secret unlocked', html:
        `<div class="modal__title">Easter egg</div><div class="modal__desc">${message}</div>` });
      confetti();
      document.dispatchEvent(new CustomEvent('baka:egg', { detail: { kind } }));
    }
  };

  const seq = [38,38,40,40,37,39,37,39,66,65]; let pos = 0;
  document.addEventListener('keydown', (e) => {
    pos = (e.keyCode === seq[pos]) ? pos + 1 : 0;
    if (pos === seq.length) {
      pos = 0;
      reveal('konami'); // unlock the legendary coupon if we're on the Vault
      confetti();
      window.BakaSound && window.BakaSound.coin();
      if (location.pathname.replace(/\/+$/, '') === '/arcade') {
        window.BakaModal.open({ bar: 'Konami', html:
          '<div class="modal__title">Cheat activated</div><div class="modal__desc">Up up down down left right left right B A. You are already in the Arcade. Legendary.</div>' });
      } else {
        window.BakaModal.open({ bar: 'Konami', html:
          '<div class="modal__title">Warping to the Arcade...</div><div class="modal__desc">Up up down down left right left right B A. Hold on to your sheet.</div>' });
        setTimeout(() => { location.href = '/arcade'; }, 900);
      }
    }
  });

  if (new URLSearchParams(location.search).get('ghost') === '1') {
    setTimeout(() => window.BakaEggs.unlock('secret-url', 'You found the secret URL. The modem screeches in your honour.'), 800);
  }

  const counter = document.getElementById('visitor-counter');
  if (counter) counter.addEventListener('dblclick', () => window.BakaEggs.unlock('hidden-spot', 'You double-clicked the counter? Here, take the WiFi password.'));
})();
