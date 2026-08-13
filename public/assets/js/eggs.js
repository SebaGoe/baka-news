// Meme-number & keyword Easter eggs. Type a code in the address bar, or just
// type the digits anywhere on the page.
(function () {
  const T = {
    '42':      { bar: 'Deep Thought', title: '42',
      desc: 'The Answer to Life, the Universe, and Everything. You still have to work out the question yourself.', unlock: 'meme-42' },
    '67':      { bar: '6-7', title: '6 &ndash; 7',
      desc: 'Six. Seven. That is the whole bit. Doot doot. You are part of it now, forever.', unlock: 'meme-67' },
    '1337':    { bar: 'l33t', title: '1337',
      desc: 'Access granted. You are certified ELITE. Please only hack imaginary mainframes.', unlock: 'meme-1337' },
    '9001':    { bar: 'Scouter', title: '9001',
      desc: "WHAT?! It's OVER NINE THOUSAND! (It is, precisely, nine thousand and one.)" },
    '8675309': { bar: 'Operator', title: '867-5309',
      desc: 'Jenny, Jenny, we have got your number. For a good time, keep browsing.' },
    '404':     { bar: 'Not Found', title: '404',
      desc: 'Easter egg not found. ...Just kidding. This IS the egg. Very meta of you.' },
    '777':     { bar: 'Jackpot', title: '777',
      desc: 'CHA-CHING! Three lucky sevens. Your winnings: one (1) sense of accomplishment.' },
    '007':     { bar: 'Classified', title: '007',
      desc: 'You have a licence to be silly. Use it responsibly, Agent.' },
    '2038':    { bar: 'Y2K38', title: '2038',
      desc: 'The Unix clock is sweating about January 19, 2038. We will deal with it in 2037.' },
    '314':     { bar: 'Delicious', title: '3.14',
      desc: 'Mmm... pi. Circular, irrational, and goes on forever. A lot like this website.' },
    '1234':    { bar: 'Security', title: '1234',
      desc: "That's the kind of code an idiot would have on their luggage. Please change it." },
    '1996':    { bar: 'Archives', title: '1996',
      desc: 'The year Baka News was founded. Allegedly. Our records are as fake as everything else.' },
    '0451':    { bar: 'Door', title: '0451',
      desc: 'The door clicks open. For a moment you feel like the hero of an immersive sim.' }
  };

  function norm(raw) { return String(raw).replace(/[^0-9a-z]/gi, '').toLowerCase(); }

  function trigger(raw) {
    const key = norm(raw);
    if (key === 'arcade' || key === 'game') { location.href = '/arcade'; return true; }
    if (key === 'aboutbaka') { location.href = '/about'; return true; }
    const m = T[key];
    if (!m) return false;
    window.BakaModal.open({ bar: m.bar,
      html: `<div class="modal__title">${m.title}</div><div class="modal__desc">${m.desc}</div>` });
    window.BakaFX && window.BakaFX.confetti();
    window.BakaSound && window.BakaSound.coin();
    if (m.unlock && window.BakaEggs) window.BakaEggs.reveal(m.unlock);
    document.dispatchEvent(new CustomEvent('baka:egg', { detail: { kind: m.unlock || ('meme-' + key) } }));
    return true;
  }

  window.BakaMemes = { trigger, has: (k) => !!T[norm(k)] || ['arcade', 'game', 'aboutbaka'].includes(norm(k)) };

  // Type digits anywhere (outside form fields) to fire a code.
  const numeric = Object.keys(T).filter(k => /^[0-9]+$/.test(k)).sort((a, b) => b.length - a.length);
  let buf = '', timer = null;
  document.addEventListener('keydown', (e) => {
    const el = e.target;
    if (el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT' || el.isContentEditable)) return;
    if (e.key >= '0' && e.key <= '9') {
      buf = (buf + e.key).slice(-8);
      clearTimeout(timer); timer = setTimeout(() => (buf = ''), 1400);
      for (const c of numeric) { if (buf.endsWith(c)) { trigger(c); buf = ''; break; } }
    }
  });
})();
