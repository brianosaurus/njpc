(function () {
  // Lifecycle picker — go button navigates to selected URL
  var sel = document.getElementById('mc-lc-select');
  var go  = document.getElementById('mc-lc-go');
  if (sel && go) {
    function navigate() {
      var v = sel.value;
      if (v && v !== '#') window.location.href = v;
    }
    go.addEventListener('click', navigate);
    sel.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); navigate(); }
    });
  }

  // Mobile menu toggle
  var burger = document.querySelector('.mc-burger');
  var nav    = document.querySelector('.mc-nav-primary');
  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
})();
