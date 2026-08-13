// Visitor counter: hits the server once, then rolls the LCD digits up.
(function () {
  const el = document.getElementById('visitor-counter');
  const marquee = document.getElementById('marquee-visits');
  fetch('/counter.json').then(r => r.json()).then(({ visits }) => {
    if (marquee) marquee.textContent = visits.toLocaleString();
    if (!el) return;
    const target = visits;
    const pad = (n) => String(n).padStart(7, '0');
    let cur = Math.max(0, target - 40);
    const tick = () => {
      cur += Math.ceil((target - cur) / 6) || 1;
      if (cur >= target) cur = target;
      el.textContent = pad(cur);
      if (cur < target) requestAnimationFrame(tick);
    };
    tick();
  }).catch(() => { if (el) el.textContent = '0001337'; });
})();
