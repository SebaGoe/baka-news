// Generic retro modal used by coupons and mascot gifts.
window.BakaModal = (function () {
  let backdrop;
  function ensure() {
    if (backdrop) return backdrop;
    backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop';
    backdrop.innerHTML = `
      <div class="modal" role="dialog" aria-modal="true">
        <div class="modal__bar"><span class="modal__bartitle">Baka.exe</span>
          <button class="modal__close" aria-label="Close">×</button></div>
        <div class="modal__body"></div>
      </div>`;
    document.body.appendChild(backdrop);
    const close = () => backdrop.classList.remove('is-open');
    backdrop.querySelector('.modal__close').addEventListener('click', close);
    backdrop.addEventListener('click', (e) => { if (e.target === backdrop) close(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    return backdrop;
  }
  function open({ bar = 'Baka.exe', html = '' }) {
    ensure();
    backdrop.querySelector('.modal__bartitle').textContent = bar;
    backdrop.querySelector('.modal__body').innerHTML = html;
    backdrop.classList.add('is-open');
    backdrop.querySelector('.modal__close').focus();
    return backdrop;
  }
  return { open };
})();
