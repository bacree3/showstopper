<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_GET['email'])) {
  sendMail($_GET['email'], 'verify');
	$error = "
	<div class='alert alert-danger text-center' role='alert'>
  	Another verification email has been sent. Please verify your email to login.
	</div>";
} else {
	$error = "";
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
          <?php echo $error; ?>
					<div class="loginform form">
						<div class="logo col-md-12 text-center">
							<h1>Verify Your Email</h1>
						</div>
              <div class="logo col-md-12 text-center">
								<p>A verification email was sent to the address you signed up with. Go to your email and click to verification link to continue.</p>
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
