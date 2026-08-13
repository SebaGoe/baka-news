// Bakabake: idle float, random peeks, click-counter easter egg, speech bubbles.
(function () {
  const m = document.getElementById('mascot');
  if (!m) return;
  const speech = document.getElementById('mascot-speech');
  const lines = [
    'boo.', 'read something silly today!',
    'psst - try the Konami code.', "I'm 87% sheet, 13% vibes.",
    'you look nice. yes, you.', 'the news is fake but my care is real.',
    'click me a few more times...'
  ];
  let clicks = 0;

  function say(text, ms = 2600) {
    if (!speech) return;
    speech.textContent = text; speech.hidden = false;
    clearTimeout(say._t); say._t = setTimeout(() => (speech.hidden = true), ms);
  }

  m.addEventListener('click', () => {
    clicks++;
    if (clicks === 7) {
      window.BakaEggs && window.BakaEggs.unlock('mascot-clicks', 'The ghost giggles and hands you a coupon.');
      say('you found my secret!');
      clicks = 0;
    } else {
      say(lines[Math.floor(Math.random() * lines.length)]);
    }
  });
  m.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); m.click(); } });

  setInterval(() => {
    if (Math.random() < 0.35) {
      m.classList.add('is-peeking');
      setTimeout(() => m.classList.remove('is-peeking'), 1400);
    }
  }, 9000);

  setTimeout(() => say('welcome to Baka News!'), 1200);
})();
