(function () {
  var b = document.getElementById('burger'), d = document.getElementById('drawer');
  if (b && d) {
    b.addEventListener('click', function () { var o = d.classList.toggle('open'); b.setAttribute('aria-expanded', o); });
    d.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { d.classList.remove('open'); b.setAttribute('aria-expanded', 'false'); }); });
  }
  var els = [].slice.call(document.querySelectorAll('.rv'));
  if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('in'); }); }
  else {
    var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }); }, { threshold: .1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (e) { io.observe(e); });
  }
  // Compteurs (page formation)
  var cs = [].slice.call(document.querySelectorAll('[data-count]'));
  function run(el) { var t = +el.dataset.count, s = el.dataset.suffix || '', st = null; function f(ts) { if (!st) st = ts; var p = Math.min((ts - st) / 1400, 1); el.textContent = Math.round(t * (1 - Math.pow(1 - p, 3))) + s; if (p < 1) requestAnimationFrame(f); } requestAnimationFrame(f); }
  if ('IntersectionObserver' in window) { var co = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { run(e.target); co.unobserve(e.target); } }); }, { threshold: .5 }); cs.forEach(function (c) { co.observe(c); }); }
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
