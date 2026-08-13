// Baka Arcade — three tiny games for /arcade: Whack-a-Ghost, Ghost Snake, Baka-sweeper.
(function () {
  const root = document.querySelector('[data-arcade]');
  if (!root) return;
  const $ = (id) => document.getElementById(id);
  const reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  const coin = () => window.BakaSound && window.BakaSound.coin();
  const getHi = (k) => { try { return parseInt(localStorage.getItem(k) || '0', 10) || 0; } catch (e) { return 0; } };
  const setHi = (k, v) => { try { localStorage.setItem(k, String(v)); } catch (e) {} };

  // ---------------- Tab switching ----------------
  const tabs = Array.from(root.querySelectorAll('.arcade__tab'));
  const panels = Array.from(root.querySelectorAll('[data-game-panel]'));
  function show(game) {
    tabs.forEach(t => {
      const on = t.dataset.game === game;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panels.forEach(p => {
      const on = p.dataset.gamePanel === game;
      p.classList.toggle('is-active', on);
      p.hidden = !on;
    });
    if (game === 'snake') Snake.draw();
    if (game === 'mines') Mines.ensure();
  }
  tabs.forEach(t => t.addEventListener('click', () => show(t.dataset.game)));

  // ---------------- Whack-a-Ghost ----------------
  const Whack = (function () {
    const grid = $('whack-grid');
    if (!grid) return {};
    const holes = Array.from(grid.querySelectorAll('.hole'));
    const elScore = $('whack-score'), elTime = $('whack-time'), elHi = $('whack-hi'),
          elMsg = $('whack-msg'), startBtn = $('whack-start');
    let score = 0, time = 30, running = false, popTimer = null, tick = null, upIndex = -1;
    let hi = getHi('baka_hiscore'); if (elHi) elHi.textContent = hi;

    function pop() {
      holes.forEach(h => h.classList.remove('is-up', 'is-bonk'));
      let i = Math.floor(Math.random() * holes.length);
      if (i === upIndex) i = (i + 1) % holes.length;
      upIndex = i;
      holes[i].classList.add('is-up');
      const life = Math.max(500, 1100 - (30 - time) * 20);
      popTimer = setTimeout(() => { holes[i] && holes[i].classList.remove('is-up'); }, life);
    }
    function whack(h) {
      if (!running || !h.classList.contains('is-up')) return;
      h.classList.remove('is-up'); h.classList.add('is-bonk');
      score++; if (elScore) elScore.textContent = score; coin();
    }
    holes.forEach(h => h.addEventListener('click', () => whack(h)));
    document.addEventListener('keydown', (e) => {
      if (!running) return;
      const n = parseInt(e.key, 10);
      if (n >= 1 && n <= holes.length) whack(holes[n - 1]);
    });
    function end() {
      running = false; clearInterval(tick); clearTimeout(popTimer);
      holes.forEach(h => h.classList.remove('is-up', 'is-bonk'));
      startBtn.disabled = false; startBtn.textContent = 'Play again';
      let best = '';
      if (score > hi) { hi = score; setHi('baka_hiscore', hi); if (elHi) elHi.textContent = hi; best = ' A new high score!'; }
      if (elMsg) elMsg.textContent = `Time! You bonked ${score} ghost${score === 1 ? '' : 's'}.${best}`;
      window.BakaModal && window.BakaModal.open({ bar: 'Game Over', html:
        `<div class="modal__title">Final score: ${score}</div><div class="modal__desc">${score >= 15 ? 'The ghost is genuinely impressed.' : 'The ghost claps politely.'}${best}</div>` });
      if (score >= 15) { window.BakaEggs && window.BakaEggs.reveal('meme-67'); window.BakaFX && window.BakaFX.confetti(); }
    }
    function start() {
      score = 0; time = 30; running = true; upIndex = -1;
      if (elScore) elScore.textContent = '0';
      if (elTime) elTime.textContent = '30';
      if (elMsg) elMsg.textContent = 'Go! Bonk the ghosts!';
      startBtn.disabled = true; startBtn.textContent = 'Playing...';
      pop();
      tick = setInterval(() => {
        time--; if (elTime) elTime.textContent = time;
        if (time <= 0) return end();
        pop();
      }, 820);
    }
    startBtn && startBtn.addEventListener('click', start);
    return {};
  })();

  // ---------------- Ghost Snake ----------------
  const Snake = (function () {
    const cv = $('snake-canvas');
    if (!cv) return { draw() {} };
    const ctx = cv.getContext('2d');
    const N = 14, CELL = cv.width / N;
    const elScore = $('snake-score'), elHi = $('snake-hi'), elMsg = $('snake-msg'), startBtn = $('snake-start');
    let snake, dir, next, food, loop = null, running = false, score = 0;
    let hi = getHi('baka_snake_hi'); if (elHi) elHi.textContent = hi;

    const ink = '#1c1b17', paper = '#e7dfcd', ghostC = '#3e6b3a', foodC = '#9e2b25';
    function cellXY(c) { return [c.x * CELL, c.y * CELL]; }
    function drawCell(c, color) { const [x, y] = cellXY(c); ctx.fillStyle = color; ctx.fillRect(x + 1, y + 1, CELL - 2, CELL - 2); }
    function draw() {
      ctx.fillStyle = paper; ctx.fillRect(0, 0, cv.width, cv.height);
      ctx.strokeStyle = 'rgba(0,0,0,.06)';
      for (let i = 1; i < N; i++) { ctx.beginPath(); ctx.moveTo(i * CELL, 0); ctx.lineTo(i * CELL, cv.height); ctx.moveTo(0, i * CELL); ctx.lineTo(cv.width, i * CELL); ctx.stroke(); }
      if (food) {
        const [fx, fy] = cellXY(food);
        ctx.fillStyle = foodC; ctx.beginPath();
        ctx.arc(fx + CELL / 2, fy + CELL / 2, CELL / 2 - 3, 0, 7); ctx.fill();
      }
      if (snake) snake.forEach((c, i) => {
        drawCell(c, i === 0 ? ink : ghostC);
        if (i === 0) { // eyes
          const [x, y] = cellXY(c);
          ctx.fillStyle = '#fff';
          ctx.fillRect(x + CELL * 0.28, y + CELL * 0.3, 3, 4);
          ctx.fillRect(x + CELL * 0.6, y + CELL * 0.3, 3, 4);
        }
      });
    }
    function placeFood() {
      let ok = false;
      while (!ok) { food = { x: (Math.random() * N) | 0, y: (Math.random() * N) | 0 }; ok = !snake.some(s => s.x === food.x && s.y === food.y); }
    }
    function step() {
      dir = next;
      const head = { x: snake[0].x + dir.x, y: snake[0].y + dir.y };
      if (head.x < 0 || head.y < 0 || head.x >= N || head.y >= N || snake.some(s => s.x === head.x && s.y === head.y)) return end();
      snake.unshift(head);
      if (food && head.x === food.x && head.y === food.y) { score++; if (elScore) elScore.textContent = score; coin(); placeFood(); }
      else snake.pop();
      draw();
    }
    function end() {
      running = false; clearInterval(loop);
      startBtn.disabled = false; startBtn.textContent = 'Play again';
      let best = '';
      if (score > hi) { hi = score; setHi('baka_snake_hi', hi); if (elHi) elHi.textContent = hi; best = ' New best!'; }
      if (elMsg) elMsg.textContent = `The ghost ate ${score} coupon${score === 1 ? '' : 's'}, then itself.${best}`;
    }
    function setDir(d) {
      const map = { up: { x: 0, y: -1 }, down: { x: 0, y: 1 }, left: { x: -1, y: 0 }, right: { x: 1, y: 0 } };
      const nd = map[d]; if (!nd) return;
      if (nd.x === -dir.x && nd.y === -dir.y) return; // no reversing
      next = nd;
    }
    function start() {
      snake = [{ x: 6, y: 7 }, { x: 5, y: 7 }, { x: 4, y: 7 }];
      dir = { x: 1, y: 0 }; next = dir; score = 0; running = true;
      if (elScore) elScore.textContent = '0';
      if (elMsg) elMsg.textContent = 'Chomp!';
      startBtn.disabled = true; startBtn.textContent = 'Playing...';
      placeFood(); draw();
      clearInterval(loop); loop = setInterval(step, reduce ? 220 : 130);
    }
    startBtn && startBtn.addEventListener('click', start);
    document.addEventListener('keydown', (e) => {
      if (!running) return;
      const k = { ArrowUp: 'up', ArrowDown: 'down', ArrowLeft: 'left', ArrowRight: 'right', w: 'up', s: 'down', a: 'left', d: 'right' }[e.key];
      if (k) { e.preventDefault(); setDir(k); }
    });
    root.querySelectorAll('.dpad__btn').forEach(b => b.addEventListener('click', () => { if (running) setDir(b.dataset.dir); }));
    draw();
    return { draw };
  })();

  // ---------------- Baka-sweeper (Minesweeper) ----------------
  const Mines = (function () {
    const grid = $('mines-grid');
    if (!grid) return { ensure() {} };
    const N = 9, BOMBS = 10;
    const elCount = $('mines-count'), elFlags = $('mines-flags'), elMsg = $('mines-msg'), resetBtn = $('mines-reset');
    let cells = [], started = false, over = false, flags = 0, revealed = 0, built = false;

    function idx(r, c) { return r * N + c; }
    function neighbors(r, c) { const out = []; for (let dr = -1; dr <= 1; dr++) for (let dc = -1; dc <= 1; dc++) { if (!dr && !dc) continue; const nr = r + dr, nc = c + dc; if (nr >= 0 && nc >= 0 && nr < N && nc < N) out.push([nr, nc]); } return out; }

    function build() {
      grid.innerHTML = ''; cells = []; started = false; over = false; flags = 0; revealed = 0;
      if (elFlags) elFlags.textContent = '0';
      if (elCount) elCount.textContent = BOMBS;
      if (elMsg) elMsg.textContent = 'Click to reveal. Right-click (or long-press) to flag a ghost.';
      grid.style.setProperty('--n', N);
      for (let r = 0; r < N; r++) for (let c = 0; c < N; c++) {
        const b = document.createElement('button');
        b.className = 'mine'; b.type = 'button'; b.dataset.r = r; b.dataset.c = c;
        b.setAttribute('aria-label', 'hidden square');
        cells.push({ bomb: false, near: 0, open: false, flag: false, el: b });
        grid.appendChild(b);
      }
      built = true;
    }
    function seed(safeR, safeC) {
      let placed = 0;
      while (placed < BOMBS) {
        const r = (Math.random() * N) | 0, c = (Math.random() * N) | 0;
        if (Math.abs(r - safeR) <= 1 && Math.abs(c - safeC) <= 1) continue; // keep first click safe
        const cell = cells[idx(r, c)];
        if (cell.bomb) continue;
        cell.bomb = true; placed++;
      }
      for (let r = 0; r < N; r++) for (let c = 0; c < N; c++) {
        if (cells[idx(r, c)].bomb) continue;
        cells[idx(r, c)].near = neighbors(r, c).filter(([nr, nc]) => cells[idx(nr, nc)].bomb).length;
      }
      started = true;
    }
    function openCell(r, c) {
      const cell = cells[idx(r, c)];
      if (cell.open || cell.flag || over) return;
      cell.open = true; revealed++;
      cell.el.classList.add('is-open');
      cell.el.setAttribute('aria-label', cell.bomb ? 'ghost' : (cell.near || 'empty'));
      if (cell.bomb) { cell.el.classList.add('is-bomb'); cell.el.textContent = 'oo'; return boom(); }
      if (cell.near) { cell.el.textContent = cell.near; cell.el.dataset.n = cell.near; }
      else neighbors(r, c).forEach(([nr, nc]) => openCell(nr, nc)); // flood fill
      checkWin();
    }
    function boom() {
      over = true; coin();
      cells.forEach(cl => { if (cl.bomb) { cl.el.classList.add('is-open', 'is-bomb'); cl.el.textContent = 'oo'; } });
      if (elMsg) elMsg.textContent = 'You woke a ghost. Boo. Press New board to try again.';
      window.BakaModal && window.BakaModal.open({ bar: 'Boo!', html:
        '<div class="modal__title">A ghost woke up</div><div class="modal__desc">It was having a lovely nap. Try a fresh board.</div>' });
    }
    function checkWin() {
      if (revealed === N * N - BOMBS && !over) {
        over = true;
        if (elMsg) elMsg.textContent = 'Swept clean! Every ghost stayed asleep. Impressive.';
        window.BakaFX && window.BakaFX.confetti();
        window.BakaModal && window.BakaModal.open({ bar: 'You win', html:
          '<div class="modal__title">Board cleared</div><div class="modal__desc">Ten ghosts, undisturbed. They send their quiet thanks.</div>' });
      }
    }
    function toggleFlag(r, c) {
      const cell = cells[idx(r, c)];
      if (cell.open || over) return;
      cell.flag = !cell.flag;
      cell.el.classList.toggle('is-flag', cell.flag);
      cell.el.textContent = cell.flag ? '!' : '';
      cell.el.setAttribute('aria-label', cell.flag ? 'flagged ghost' : 'hidden square');
      flags += cell.flag ? 1 : -1;
      if (elFlags) elFlags.textContent = flags;
    }
    // Delegated events
    grid.addEventListener('click', (e) => {
      const b = e.target.closest('.mine'); if (!b) return;
      const r = +b.dataset.r, c = +b.dataset.c;
      if (!started) seed(r, c);
      openCell(r, c);
    });
    grid.addEventListener('contextmenu', (e) => {
      const b = e.target.closest('.mine'); if (!b) return;
      e.preventDefault();
      toggleFlag(+b.dataset.r, +b.dataset.c);
    });
    // Long-press to flag (touch)
    let lpTimer = null;
    grid.addEventListener('touchstart', (e) => {
      const b = e.target.closest('.mine'); if (!b) return;
      lpTimer = setTimeout(() => { toggleFlag(+b.dataset.r, +b.dataset.c); lpTimer = null; }, 450);
    }, { passive: true });
    grid.addEventListener('touchend', () => { if (lpTimer) clearTimeout(lpTimer); });
    resetBtn && resetBtn.addEventListener('click', build);
    function ensure() { if (!built) build(); }
    return { ensure };
  })();

  // Deep-link ?game=snake / #snake, or leave default whack.
  const wanted = (new URLSearchParams(location.search).get('game') || location.hash.replace('#', '')).toLowerCase();
  if (['snake', 'mines', 'whack'].includes(wanted)) show(wanted);
})();
