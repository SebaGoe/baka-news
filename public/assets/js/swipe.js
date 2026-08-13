// Swipe left/right to move between categories (mobile-first).
(function () {
  const nav = document.getElementById('catnav');
  if (!nav) return;
  const items = [...nav.querySelectorAll('.catnav__item')];
  const idx = items.findIndex(a => a.classList.contains('is-active'));
  const root = document.querySelector('[data-swipe-root]') || document.body;

  let x0 = null, y0 = null;
  const THRESH = 60;
  root.addEventListener('touchstart', (e) => {
    x0 = e.touches[0].clientX; y0 = e.touches[0].clientY;
  }, { passive: true });
  root.addEventListener('touchend', (e) => {
    if (x0 === null) return;
    const dx = e.changedTouches[0].clientX - x0;
    const dy = e.changedTouches[0].clientY - y0;
    if (Math.abs(dx) > THRESH && Math.abs(dx) > Math.abs(dy) * 1.5) {
      const dir = dx < 0 ? 1 : -1;               // left swipe = next
      const next = items[(idx + dir + items.length) % items.length];
      if (next) { document.body.style.opacity = '.4'; location.href = next.href; }
    }
    x0 = y0 = null;
  }, { passive: true });
})();
