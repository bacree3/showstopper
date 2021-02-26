<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isLoggedIn()) {
	header("Location:/");
}
?>
<html>
  	<head></head>
	<body>
		<nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>

		<!-- BODY -->
    	<div class="container mt-5">
        	<div class="row">
				<div class="col-md-5 mx-auto">
					<div class="loginform form">
						<div class="logo col-md-12 text-center">
							<h1>Forgot Password</h1>
						</div>
						<form action="forgot-password.php" method="post" name="forgot">
              <input type="hidden" name="forgot" value=""/>
              <div class="logo col-md-12 text-center">
								<p>Enter the email associated with your account and we will send you a link to reset your password.</p>
							</div>
							<div class="form-group">
								<input type="email" name="email" id="email" class="form-control validate" placeholder="Enter Email">
							</div>

							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-block loginbtn btn-primary">Send Link</button>
							</div>
						</form>
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
