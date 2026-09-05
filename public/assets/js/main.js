document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.querySelector('.primary-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', nav.classList.contains('open'));
    });
  }

  initHeroSlider();
  initCategorySlider();
});

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
  var carousels = Array.prototype.slice.call(document.querySelectorAll('[data-cat-slider]'));

  carousels.forEach(function (root) {
    var viewport = root.querySelector('.categories-slider');
    var track = root.querySelector('.categories-track');
    var cards = Array.prototype.slice.call(root.querySelectorAll('.category-card'));
    var controls = root.querySelector('[data-cat-controls]');
    var prevBtn = root.querySelector('[data-cat-prev]');
    var nextBtn = root.querySelector('[data-cat-next]');

    if (!viewport || !track || !cards.length) return;

    function perView() {
      var w = window.innerWidth;
      if (w <= 640) return 1;
      if (w <= 992) return 2;
      if (w <= 1200) return 3;
      return Math.min(3, cards.length);
    }

    function applyLayout() {
      var visible = perView();
      root.style.setProperty('--cat-per-view', String(visible));
      root.style.setProperty('--cat-gap', '20px');

      var canScroll = cards.length > visible;
      if (controls) {
        controls.hidden = !canScroll;
      }

      updateButtons();
    }

    function stepSize() {
      var card = cards[0];
      if (!card) return 0;
      var styles = window.getComputedStyle(track);
      var gap = parseFloat(styles.columnGap || styles.gap) || 20;
      return card.getBoundingClientRect().width + gap;
    }

    function maxScrollLeft() {
      return Math.max(0, viewport.scrollWidth - viewport.clientWidth);
    }

    function updateButtons() {
      if (!prevBtn || !nextBtn) return;
      var max = maxScrollLeft();
      var left = Math.abs(viewport.scrollLeft);
      var atStart = left <= 2;
      var atEnd = left >= max - 2;
      prevBtn.disabled = atStart;
      nextBtn.disabled = atEnd || max <= 0;
    }

    function scrollByDir(dir) {
      var amount = stepSize() * dir;
      viewport.scrollBy({ left: amount, behavior: 'smooth' });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        scrollByDir(document.documentElement.dir === 'rtl' ? 1 : -1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        scrollByDir(document.documentElement.dir === 'rtl' ? -1 : 1);
      });
    }

    viewport.addEventListener('scroll', updateButtons, { passive: true });
    window.addEventListener('resize', applyLayout);
    applyLayout();
  });
}
