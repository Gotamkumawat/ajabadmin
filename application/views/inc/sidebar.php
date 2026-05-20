<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?php echo base_url('add_new'); ?>" class="brand-link" style="text-decoration: none;">
    <img src="<?php echo base_url('dist/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Ajab Shahar</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="<?php echo base_url('add_new'); ?>" class="nav-link">
            <i class="nav-icon fas fa-plus-circle"></i>
            <p>Add New</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url('list'); ?>" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>List</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<!-- Active Link JS -->
<script>
$(document).ready(function() {
  // Automatically highlight active menu link
  const currentLocation = window.location.href;
  const menuItems = document.querySelectorAll('.nav-link');

  menuItems.forEach(link => {
    if (currentLocation.includes(link.getAttribute('href'))) {
      link.classList.add('active');
    }
  });

  // Ensure treeview widget works (only if AdminLTE Treeview plugin is loaded)
  if (typeof $.fn.tree === 'function') { $('.nav-sidebar').tree(); }
});
</script>

<style>
  /* Active link style */
  .nav-sidebar .nav-link.active {
    background-color: #1e88e5 !important; /* sky blue highlight */
    color: #fff !important;
    font-weight: bold;
  }

  .nav-sidebar .nav-link.active i {
    color: #fff !important;
  }

  /* Hover effect */
  .nav-sidebar .nav-link:hover {
    background-color: #1e88e5 !important; /* dark sky blue */
    color: #fff !important;
  }
</style>