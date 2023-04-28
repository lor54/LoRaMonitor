<header class="shadow-sm p-3 mb-5 border-bottom bg-white sticky-top">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li><a href="#" class="nav-link px-2 <?php if($actual_page == "dashboard") echo 'link-secondary disabled'; else echo 'link-body-emphasis';?>">Dashboard</a></li>
        <li><a href="#" class="nav-link px-2 <?php if($actual_page == "stats") echo 'link-secondary disabled'; else echo 'link-body-emphasis';?>">Stats</a></li>
      </ul>

      <?php if($actual_page == "dashboard") echo ' <a href="pages/antennareg.php">
                                                      <button type="button" class="btn btn-sm btn-outline-secondary">Aggiungi antenna +</button>
                                                   </a>';?>

      <?php if($actual_page == "dashboard") echo '<form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
        <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
      </form>';?>

      <div class="dropdown text-end">
        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
        </a>
        <ul class="dropdown-menu text-small">
          <li><a class="dropdown-item" href="#">New project...</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/actions/logout.php">Sign out</a></li>
        </ul>
      </div>
    </div>
  </div>
</header>