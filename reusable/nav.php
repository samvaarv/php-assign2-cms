<?php
  // Get the current page name
  $current_page = basename($_SERVER['PHP_SELF']);
?>
<header>
  <nav class="navbar navbar-expand-lg position-relative p-0 h-100">
    <div class="container">
      <a class="navbar-brand" href="index.php"><img src="./assets/images/logo.png" alt="Galleria-Logo"/></a>
      <button class="navbar-toggler p-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavBar" aria-controls="mainNavBar" aria-expanded="false" aria-label="Toggle navigation">
      <i class="navbar-toggler-icon fa-solid fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="mainNavBar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
          </li>
          <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'addart.php') ? 'active' : ''; ?>" href="addart.php">Add Art</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>