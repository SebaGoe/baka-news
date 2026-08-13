// Netscape-style chrome behaviour: toolbar, address bar, status bar, throbber, menus.
(function () {
  const $ = (s) => document.querySelector(s);
  const status = $('#statusbar');
  const throbber = $('#throbber');
  const progress = $('#progress');

  function setStatus(t) { if (status) status.textContent = t; }
  function loading(on, label) {
    if (throbber) throbber.classList.toggle('is-loading', on);
    if (progress) { progress.classList.remove('is-loading'); if (on) { void progress.offsetWidth; progress.classList.add('is-loading'); } }
    if (on && label) setStatus(label);
  }

  // Simulate a page "load" finishing.
  loading(true, 'Loading http://www.bakanews.web ...');
  window.addEventListener('load', () => setTimeout(() => { loading(false); setStatus('Document: Done'); }, 700));

  // Status bar shows link targets, like a real browser.
  document.addEventListener('mouseover', (e) => {
    const a = e.target.closest && e.target.closest('a[href]');
    if (a) setStatus(a.getAttribute('href'));
  });
  document.addEventListener('mouseout', (e) => {
    if (e.target.closest && e.target.closest('a[href]')) setStatus('Document: Done');
  });
  // Kick the throbber when navigating away.
  document.addEventListener('click', (e) => {
    const a = e.target.closest && e.target.closest('a[href]');
    if (a && a.getAttribute('href') && !a.getAttribute('href').startsWith('#')) loading(true, 'Connecting ...');
  });

  // ---- Window controls (min / max / close) ----
  const browser = $('.browser');
  const titlebar = $('.browser__titlebar');
  const btnMin = $('#win-min'), btnMax = $('#win-max'), btnClose = $('#win-close');

  function setMax(on) {
    browser.classList.toggle('is-maxed', on);
    if (on) browser.classList.remove('is-mini');
    if (btnMax) { btnMax.setAttribute('aria-pressed', on ? 'true' : 'false'); btnMax.setAttribute('aria-label', on ? 'Restore window' : 'Maximize window'); }
  }
  function setMin(on) {
    browser.classList.toggle('is-mini', on);
    document.body.classList.toggle('is-min', on);
    if (on) buildVirusAd();
    if (btnMin) { btnMin.setAttribute('aria-pressed', on ? 'true' : 'false'); btnMin.setAttribute('aria-label', on ? 'Restore window' : 'Minimize window'); }
    setStatus(on ? 'Window minimized. That ad behind us is not real. Click the title bar to restore.' : 'Document: Done');
  }

  // ---- Minimizing reveals a fake "DANGER STUPID VIRUS" ad; click it to play ----
  let virusAd = null;
  function buildVirusAd() {
    if (virusAd) return virusAd;
    virusAd = document.createElement('div');
    virusAd.className = 'virus-ad';
    virusAd.setAttribute('role', 'button');
    virusAd.setAttribute('tabindex', '0');
    virusAd.setAttribute('aria-label', 'Fake advertisement: danger, stupid virus. Activate to play a mini-game.');
    virusAd.innerHTML =
      '<div class="virus-ad__box">' +
        '<p class="virus-ad__warn blink">!!  DANGER  !!</p>' +
        '<p class="virus-ad__big">STUPID VIRUS<br>DETECTED</p>' +
        '<p class="virus-ad__sub">Your computer has 0 (zero) real problems. This is not a real warning &mdash; nothing here is real. But there is a small ghost inside.</p>' +
        '<p class="virus-ad__cta">&#9656; CLICK ANYWHERE TO SQUASH THE VIRUS &#9662;</p>' +
      '</div>';
    const launch = () => startVirusGame();
    virusAd.addEventListener('click', launch);
    virusAd.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); launch(); } });
    document.body.appendChild(virusAd);
    return virusAd;
  }

  function startVirusGame() {
    if (!window.BakaModal) return;
    const bd = window.BakaModal.open({ bar: 'AntiBaka Scanner 98', html:
      '<div class="modal__title">Squash the Stupid Virus</div>' +
      '<div class="modal__desc" id="vg-desc">Click the wobbling blob before it teleports away. You have 12 seconds.</div>' +
      '<div class="virusgame">' +
        '<div class="virusgame__hud">Score <b id="vg-score">0</b> &nbsp;&middot;&nbsp; Time <b id="vg-time">12</b></div>' +
        '<div class="virusgame__stage" id="vg-stage"><button class="virusgame__bug" id="vg-bug" type="button" aria-label="virus">x_x</button></div>' +
      '</div>' });
    if (!bd) return;
    const stage = bd.querySelector('#vg-stage'), bug = bd.querySelector('#vg-bug');
    const elS = bd.querySelector('#vg-score'), elT = bd.querySelector('#vg-time'), elD = bd.querySelector('#vg-desc');
    let score = 0, time = 12, alive = true;
    function moveBug() {
      const w = Math.max(20, stage.clientWidth - 52), h = Math.max(20, stage.clientHeight - 52);
      bug.style.left = (Math.random() * w) + 'px';
      bug.style.top = (Math.random() * h) + 'px';
    }
    function stop() { alive = false; clearInterval(hop); clearInterval(tick); }
    bug.addEventListener('click', (e) => {
      e.stopPropagation();
      if (!alive) return;
      score++; if (elS) elS.textContent = score;
      window.BakaSound && window.BakaSound.coin();
      moveBug();
    });
    const hop = setInterval(() => { if (alive) moveBug(); }, 700);
    const tick = setInterval(() => {
      if (!bd.classList.contains('is-open')) return stop(); // closed early
      time--; if (elT) elT.textContent = time;
      if (time <= 0) {
        stop(); if (elT) elT.textContent = '0'; if (bug) bug.style.display = 'none';
        if (elD) elD.textContent = 'You squashed ' + score + ' virus' + (score === 1 ? '' : 'es') + '. None of them were real. Heroic, regardless.';
        window.BakaFX && window.BakaFX.confetti();
      }
    }, 1000);
    setTimeout(moveBug, 30);
  }

  if (btnMax) btnMax.addEventListener('click', () => setMax(!browser.classList.contains('is-maxed')));
  if (btnMin) btnMin.addEventListener('click', () => setMin(!browser.classList.contains('is-mini')));
  if (titlebar) titlebar.addEventListener('click', (e) => {
    if (browser.classList.contains('is-mini') && !e.target.closest('.winbtn')) setMin(false);
  });
  if (btnClose) btnClose.addEventListener('click', () =>
    modal('Close', "You can't close Baka News. Baka News closes <em>you</em>. (Just kidding &mdash; try the Back button.)"));

  // ---- Toolbar ----
  const on = (id, fn) => { const el = document.getElementById(id); if (el) el.addEventListener('click', fn); };
  on('nav-back', () => history.back());
  on('nav-fwd', () => history.forward());
  on('nav-reload', () => location.reload());
  on('nav-print', () => window.print());
  on('nav-stop', () => { loading(false); setStatus('Stopped. (Nothing was actually happening.)'); });
  function focusSearch() {
    const s = document.getElementById('site-search');
    if (s) { s.scrollIntoView({ block: 'center' }); s.focus(); s.select && s.select(); setStatus('Search all stories — type and press Enter.'); }
    else { location.href = '/search'; }
  }
  on('nav-search', focusSearch);

  // ---- Address bar ----
  const bar = $('#addressbar');
  const addr = $('#address');
  if (bar && addr) {
    addr.addEventListener('focus', () => addr.select());
    bar.addEventListener('submit', (e) => {
      e.preventDefault();
      let v = addr.value.trim();
      v = v.replace(/^https?:\/\/(www\.)?bakanews\.web\/?/i, '').replace(/^https?:\/\//i, '');
      const bare = v.replace(/[^0-9a-z]/gi, '').toLowerCase();
      if (window.BakaMemes && window.BakaMemes.has(bare)) { window.BakaMemes.trigger(bare); return; }
      if (window.BakaMemes && /^[0-9]+$/.test(bare) && window.BakaMemes.trigger(bare)) return;
      // Otherwise treat it as a path and "navigate".
      let path = '/' + v.replace(/^bakanews\.web/i, '').replace(/^\/+/, '');
      loading(true, 'Connecting to ' + (v || 'home') + ' ...');
      setTimeout(() => { location.href = path === '/' ? '/' : path; }, 250);
    });
  }

  // ---- Menu bar dropdowns ----
  const MENUS = {
    file: [
      { label: 'New Window', disabled: true },
      { label: 'Open Location...', action: () => { addr && addr.focus(); } },
      { label: 'Print...', action: () => window.print() },
      { sep: true },
      { label: 'Exit', action: () => modal('Exit', 'There is no exit. There is only more Baka News.') }
    ],
    edit: [
      { label: 'Undo', action: () => modal('Undo', 'You cannot undo the things you have read here.') },
      { label: 'Cut', disabled: true }, { label: 'Copy', disabled: true }, { label: 'Paste', disabled: true },
      { sep: true },
      { label: 'Find in Stories...', action: () => focusSearch() },
      { label: 'Select All Nonsense', action: () => modal('Select All', 'Everything is selected. Everything is nonsense. Working as intended.') }
    ],
    view: [
      { label: 'Reload', action: () => location.reload() },
      { label: 'Page Source', action: () => modal('Page Source', 'You peek behind the curtain and find a small ghost holding it up. It waves.') },
      { label: 'Text Size: Enormous', action: () => modal('Text Size', 'We admire the confidence, but no.') }
    ],
    go: [
      { label: 'Front Page', href: '/' }, { label: 'Baka-scopes', href: '/horoscope' },
      { label: 'Classifieds', href: '/classifieds' }, { label: 'Coupon Vault', href: '/coupons' },
      { label: 'The Arcade', href: '/arcade' }, { label: 'Guest Book', href: '/guestbook' },
      { label: 'Webring', href: '/webring' }, { label: 'Under Construction', href: '/construction' },
      { label: 'About', href: '/about' }
    ],
    bookmarks: [
      { label: 'Add Bookmark', action: () => modal('Add Bookmark', 'Bookmarked! (In your heart. We do not have a database for feelings.)') },
      { sep: true },
      { label: 'The Arcade', href: '/arcade' }, { label: 'Coupon Vault', href: '/coupons' },
      { label: 'A Random Story', href: '/random' },
      { label: 'A Random Baka Page', href: '/random-page' }
    ],
    options: [
      { label: 'Toggle Night Edition', action: () => { const b = document.getElementById('night-toggle'); b && b.click(); } },
      { label: 'Auto Load Images', action: () => modal('Auto Load Images', 'Images are always on. It is the 90s. Live a little.') },
      { label: 'Show Java Console', action: () => modal('Java Console', 'A single coffee bean rolls across the screen. That was the whole applet.') }
    ],
    help: [
      { label: 'About Baka News', href: '/about' },
      { label: 'Secret Codes...', action: () => modal('Secret Codes', 'Try typing a number in the Location bar &mdash; say <b>42</b>, <b>67</b>, or <b>1337</b>. Or just type it anywhere on the page.') },
      { label: 'About Netscape', action: () => modal('About', 'Baka Navigator 0.87 (Gold). Best viewed by you, right now. Thank you.') }
    ]
  };

  function modal(title, body) {
    window.BakaModal && window.BakaModal.open({ bar: title, html: `<div class="modal__title">${title}</div><div class="modal__desc">${body}</div>` });
  }

  let openPanel = null, openBtn = null;
  function closeMenu() {
    if (openPanel) openPanel.remove();
    if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
    openPanel = openBtn = null;
  }
  function openMenu(btn) {
    closeMenu();
    const items = MENUS[btn.dataset.menu] || [];
    const panel = document.createElement('div');
    panel.className = 'browser__menu-panel';
    items.forEach((it) => {
      if (it.sep) { panel.appendChild(document.createElement('hr')); return; }
      const node = it.href ? document.createElement('a') : document.createElement('button');
      if (it.href) node.href = it.href; else node.type = 'button';
      node.textContent = it.label;
      if (it.disabled) { node.className = 'is-disabled'; node.setAttribute('aria-disabled', 'true'); if (!it.href) node.disabled = true; }
      else if (it.action) node.addEventListener('click', () => { closeMenu(); it.action(); });
      panel.appendChild(node);
    });
    const bar = btn.closest('.browser__menubar');
    panel.style.left = (btn.offsetLeft) + 'px';
    bar.appendChild(panel);
    btn.setAttribute('aria-expanded', 'true');
    openPanel = panel; openBtn = btn;
    const first = panel.querySelector('a,button:not([disabled])');
    if (first) first.focus();
  }
  document.querySelectorAll('.menu').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      if (openBtn === btn) closeMenu(); else openMenu(btn);
    });
    btn.addEventListener('mouseenter', () => { if (openPanel && openBtn !== btn) openMenu(btn); });
  });
  document.addEventListener('click', (e) => { if (!e.target.closest('.browser__menubar')) closeMenu(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && openBtn) { const b = openBtn; closeMenu(); b.focus(); } });
})();
