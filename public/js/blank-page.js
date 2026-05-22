// Minimal JS for blank-page.html — only necessary logic.
(function () {
  // set current year in footer
  document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('currentYear');
    if (el) el.textContent = new Date().getFullYear();
  });
})();
