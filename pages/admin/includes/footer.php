  </div><!-- /.page-content -->
</div><!-- /.main-content -->
</div><!-- /.admin-layout -->

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  
  // Auto-close flash messages
  const flashes = document.querySelectorAll('.flash');
  flashes.forEach(function(el) {
    setTimeout(function() {
      el.style.transition = 'opacity .4s';
      el.style.opacity = '0';
      setTimeout(function() { el.remove(); }, 400);
    }, 4000);
  });

  // Confirm delete
  document.querySelectorAll('[data-confirm]').forEach(function(el) {
    el.addEventListener('click', function(e) {
      if (!confirm(this.dataset.confirm || 'Yakin ingin menghapus?')) {
        e.preventDefault();
      }
    });
  });
});
</script>
</body>
</html>
