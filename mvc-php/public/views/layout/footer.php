    </div> <!-- row -->
</div> <!-- container-fluid -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts adicionales -->
<script>
// Inicializar tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Inicializar popovers
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl);
});

// Animaciones de entrada
document.addEventListener('DOMContentLoaded', function() {
  const elements = document.querySelectorAll('.animate-fadeIn, .animate-fadeInUp, .animate-slideInRight');
  elements.forEach((el, index) => {
    setTimeout(() => {
      el.style.opacity = '1';
      el.style.transform = 'translateY(0) translateX(0)';
    }, index * 100);
  });
});

// Efecto de loading en botones
document.addEventListener('click', function(e) {
  if (e.target.matches('.btn[type="submit"]') || e.target.closest('.btn[type="submit"]')) {
    const btn = e.target.matches('.btn[type="submit"]') ? e.target : e.target.closest('.btn[type="submit"]');
    if (!btn.disabled) {
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
      btn.disabled = true;
    }
  }
});
</script>

<!-- Archivos propios -->
<script src="../../assets/js/script.js"></script>
</body>
</html>
