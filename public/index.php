<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

?>
<html>
  <head></head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

    <!-- BODY -->

    <div class="jumbotron jumbotron-fluid d-flex flex-column align-items-center justify-content-start">
      <h1 class="display-3">ShowStopper</h1>
      <p class="lead">Get the most out of your streaming services</p>
      <div class="d-flex justify-content-around mt-3">
        <p id="signup" class="mr-1">Don't have an account?</p>
        <p id="welcome" class="mr-1">Welcome Back!</p>
        <a id="signuplink"href="/register">Sign up today</a>
      </div>
    </div>

    <div class="container">
      <div class="row text-center">
        <div class="col-xs-6 col-md-3">
          <div class="">
            <img src="/src/img/avengers.jpg" class="rounded title" alt="...">
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div>
            <img src="/src/img/star-wars.jpg" class="rounded title" alt="...">
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div>
            <img src="/src/img/harry-potter.jpg" class="rounded title" alt="...">
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div>
            <img src="/src/img/extraction.jpg" class="rounded title" alt="...">
          </div>
        </div>
      </div>
    </div>

    <!-- END BODY -->
    <div class = "footer mt-4 pt-4"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
	</body>
</html>

<script>
  if (loggedin) {
  	document.getElementById("signup").hidden = true;
    document.getElementById("signuplink").hidden = true;
  } else {
    document.getElementById("welcome").hidden = true;
  }

</script>
