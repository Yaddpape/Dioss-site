(function () {
  var b = document.getElementById('burger'), d = document.getElementById('drawer');
  if (b && d) {
    b.addEventListener('click', function () { var o = d.classList.toggle('open'); b.setAttribute('aria-expanded', o); });
    d.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { d.classList.remove('open'); b.setAttribute('aria-expanded', 'false'); }); });
  }
  var els = [].slice.call(document.querySelectorAll('.rv,[data-draw]'));
  if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('in'); }); }
  else {
    var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }); }, { threshold: .1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (e) { io.observe(e); });
  }
  // Compteurs (page formation)
  var cs = [].slice.call(document.querySelectorAll('[data-count]'));
  function run(el) { var t = +el.dataset.count, s = el.dataset.suffix || '', st = null; function f(ts) { if (!st) st = ts; var p = Math.min((ts - st) / 1400, 1); el.textContent = Math.round(t * (1 - Math.pow(1 - p, 3))) + s; if (p < 1) requestAnimationFrame(f); } requestAnimationFrame(f); }
  if ('IntersectionObserver' in window && !(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches)) { cs.forEach(function (c) { var r = c.getBoundingClientRect(); if (r.top > innerHeight) c.textContent = '0' + (c.dataset.suffix || ''); }); var co = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { run(e.target); co.unobserve(e.target); } }); }, { threshold: .5 }); cs.forEach(function (c) { co.observe(c); }); }
  else { cs.forEach(function (c) { c.textContent = c.dataset.count + (c.dataset.suffix || ''); }); }
  document.querySelectorAll('video[data-auto]').forEach(function (v) { v.muted = true; var p = v.play(); if (p && p.catch) p.catch(function () {}); });
})();
(function () {
  if (!window.matchMedia || !matchMedia('(hover:hover)').matches) return;
  document.querySelectorAll('.pole').forEach(function (p) {
    p.addEventListener('pointermove', function (e) {
      var r = p.getBoundingClientRect();
      p.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      p.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });
})();
(function () {
  var rm = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  var nav = document.querySelector('.home .nav'), drawer = document.getElementById('drawer');
  var m = document.querySelector('[data-manif]'), words = m ? [].slice.call(m.children) : [];
  var px = rm ? [] : [].slice.call(document.querySelectorAll('[data-parallax]'));
  if (m && !rm) m.classList.add('js');
  var ticking = false;
  function frame() {
    ticking = false;
    var vh = window.innerHeight;
    if (nav) nav.classList.toggle('solid', window.scrollY > 30 || (drawer && drawer.classList.contains('open')));
    if (m && !rm) {
      var r = m.getBoundingClientRect();
      var p = Math.min(1, Math.max(0, (vh * 0.85 - r.top) / (r.height + vh * 0.35)));
      var n = Math.round(p * words.length);
      words.forEach(function (w, i) { w.classList.toggle('on', i < n); });
    }
    px.forEach(function (el) {
      var r = el.parentNode.getBoundingClientRect();
      if (r.bottom < 0 || r.top > vh) return;
      var c = (r.top + r.height / 2 - vh / 2) / (vh + r.height);
      el.style.transform = 'translate3d(0,' + (c * -16).toFixed(2) + '%,0)';
    });
  }
  function req() { if (!ticking) { ticking = true; requestAnimationFrame(frame); } }
  window.addEventListener('scroll', req, { passive: true });
  window.addEventListener('resize', req);
  var b = document.getElementById('burger'); if (b) b.addEventListener('click', function () { setTimeout(req, 0); });
  frame();
})();
(function () {
  var box = document.getElementById('vbox');
  if (!box) return;
  var inner = box.querySelector('.vbox-in');
  function open(el) {
    var d = el.dataset;
    if (d.link || typeof box.showModal !== 'function') { window.open(d.link || d.video || '#', '_blank', 'noopener'); return; }
    if (d.video) {
      var v = document.createElement('video');
      v.src = d.video; v.controls = true; v.autoplay = true; v.playsInline = true;
      if (d.title) v.setAttribute('aria-label', d.title);
      inner.appendChild(v);
    } else if (d.embed) { inner.innerHTML = d.embed; }
    box.showModal();
  }
  function close() { box.close(); }
  box.addEventListener('close', function () { inner.innerHTML = ''; });
  box.addEventListener('click', function (e) { if (e.target === box) close(); });
  box.querySelector('.vbox-x').addEventListener('click', close);
  document.querySelectorAll('.vcard,.qplay').forEach(function (el) {
    el.addEventListener('click', function () { open(el); });
  });
  if (window.matchMedia && matchMedia('(hover:hover)').matches && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.vcard').forEach(function (c) {
      var v = c.querySelector('video'); if (!v) return;
      c.addEventListener('pointerenter', function () { var p = v.play(); c.classList.add('playing'); if (p && p.catch) p.catch(function () {}); });
      c.addEventListener('pointerleave', function () { v.pause(); c.classList.remove('playing'); });
    });
  }
  document.querySelectorAll('[data-rail]').forEach(function (b) {
    b.addEventListener('click', function () {
      var r = b.closest('.wrap').querySelector('.rail');
      if (r) r.scrollBy({ left: +b.dataset.rail * r.clientWidth * 0.8, behavior: 'smooth' });
    });
  });
})();
