document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.querySelector('.primary-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', nav.classList.contains('open'));
    });
  }

  initLangSwitcher();
  initHeroSlider();
  initCategorySlider();
});

function initLangSwitcher() {
  var root = document.documentElement;
  var buttons = Array.prototype.slice.call(document.querySelectorAll('.lang-btn'));
  if (!buttons.length) return;

  function applyLang(lang) {
    var isAr = lang === 'ar';
    root.setAttribute('lang', isAr ? 'ar' : 'en');
    root.setAttribute('dir', isAr ? 'rtl' : 'ltr');
    try { localStorage.setItem('areva-lang', lang); } catch (e) {}
    buttons.forEach(function (btn) {
      var active = btn.getAttribute('data-lang') === lang;
      btn.classList.toggle('is-active', active);
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
  }

  var saved = 'en';
  try { saved = localStorage.getItem('areva-lang') || 'en'; } catch (e) {}
  applyLang(saved === 'ar' ? 'ar' : 'en');

  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      applyLang(btn.getAttribute('data-lang'));
    });
  });
}
function initHeroSlider() {
  var slider = document.querySelector('[data-hero-slider]');
  if (!slider) return;

  var slides = Array.prototype.slice.call(slider.querySelectorAll('.hero-slide'));
  var buttons = Array.prototype.slice.call(document.querySelectorAll('.hero-nav-btn'));
  var index = 0;
  var timer = null;
  var delay = 6000;

  function goTo(i) {
    index = (i + slides.length) % slides.length;
    slides.forEach(function (slide, n) {
      var active = n === index;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', active ? 'false' : 'true');
    });
    buttons.forEach(function (btn, n) {
      var active = n === index;
      btn.classList.toggle('is-active', active);
      btn.setAttribute('aria-selected', active ? 'true' : 'false');
    });
  }

  function next() { goTo(index + 1); }

  function start() {
    stop();
    timer = setInterval(next, delay);
  }

  function stop() {
    if (timer) clearInterval(timer);
    timer = null;
  }

  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      goTo(parseInt(btn.getAttribute('data-goto'), 10));
      start();
    });
  });

  var hero = slider.closest('.hero');
  if (hero) {
    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
  }

  goTo(0);
  start();
}

function initCategorySlider() {
  var root = document.querySelector('[data-cat-slider]');
  if (!root) return;

  var track = root.querySelector('.categories-track');
  var cards = Array.prototype.slice.call(track.querySelectorAll('.category-card'));
  var prevBtn = document.querySelector('[data-cat-prev]');
  var nextBtn = document.querySelector('[data-cat-next]');
  var index = 0;

  function perView() {
    var w = window.innerWidth;
    if (w <= 640) return 1;
    if (w <= 992) return 2;
    if (w <= 1200) return 3;
    return 4;
  }

  function maxIndex() {
    return Math.max(0, cards.length - perView());
  }

  function update() {
    if (index > maxIndex()) index = maxIndex();
    var card = cards[0];
    if (!card) return;
    var gap = 20;
    var step = card.getBoundingClientRect().width + gap;
    track.style.transform = 'translateX(' + (-index * step) + 'px)';
    if (prevBtn) prevBtn.disabled = index <= 0;
    if (nextBtn) nextBtn.disabled = index >= maxIndex();
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      index = Math.max(0, index - 1);
      update();
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      index = Math.min(maxIndex(), index + 1);
      update();
    });
  }

  window.addEventListener('resize', update);
  update();
}
