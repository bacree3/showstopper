<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';



?>

<html>
  	<head></head>
	<body>
		<nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>

		<!-- BODY -->
    <div class="container mt-5">
      <div class="srow rounded">
        <div class="row">
          <div class="col-md-5 mx-auto">
            <div class="logo col-md-12 text-center">
              <h1>Notifications</h1>
            </div>

            <div class="custom-control custom-switch">
              <div class="row">
                <div class="col">
                  <input type="checkbox" class="custom-control-input" checked data-toggle="toggle"
                  data-on="On" data-off="Off" data-onstyle="success" data-offstyle="danger" id="customSwitch1">
                  <label class="custom-control-label" for="customSwitch1"><span class="pull-left">Favorite Actor</span></label>
                </div>
              </div>
            <div class="row">
              <div class="col">
            <!-- <div class="custom-control custom-switch"> -->
                <input type="checkbox" class="custom-control-input" id="customSwitch2">
                <label class="custom-control-label" for="customSwitch2">Favorite Movie</label>
                </div>
              </div>
            </div>
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