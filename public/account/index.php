<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (!isLoggedIn()) {
	goToLogin();
} else {
  $info = getUserInfo($_SESSION['userID'])[0];
  $name = $info['name'];
  $email = $info['email'];
  $servicesHTML = getServicesHTML(json_decode($info['services']));
}

?>
<html>
  <head></head>

  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->

    <div class="container col-md-5 mx-auto">
      <div class="row pt-4 bg-light pb-4 mt-4 rounded">
        <div class="logo col-md-12 text-center">
          <h1>Hi <?php echo $name; ?>! Here's your account details: </h1>
          <hr class="hr-or">
        </div>
        <div class="col-xs-12 col-md-8 text">
          <p class="lead"><span class = "font-weight-bold">Name: <?php echo $name; ?></span> </p>
          <p class="lead"><span class = "font-weight-bold">Email: <?php echo $email; ?></span> </p>
          <p class="lead"><span class = "font-weight-bold">Current Subscriptions:</span> </p>
          <?php echo $servicesHTML; ?>
        </div>
        <div class="col-md-12 text-center">
          <button onclick = "location.href='/edit-account'" type="submit" class="btn btn-block loginbtn btn-primary">Edit Profile</button>
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
