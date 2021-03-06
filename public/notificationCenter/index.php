<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';



?>

<html>
  	<head></head>
	<body>
		<nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>

		<!-- BODY -->
    <div class="container">
          <div style = "background-color: white;" class="srow rounded">
            <div class="row">
              <div class="col-md-5 mx-auto">
                <div class="logo col-md-12 text-center my-2">
                  <p class="lead font-weight-bold">Notification Center:</p>
                </div>
              </div>
              <?php
              echo getNotifications(getCurrentUserID());
              ?>
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
