<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isLoggedIn()) {
	echo "logged in";
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
							<h1>Login</h1>
						</div>
						<form action="login.php" method="post" name="login">
              <input type="hidden" name="login" value=""/>
							<div class="form-group">
								<label>Email address</label>
								<input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
							</div>
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-block loginbtn btn-primary">Login</button>
							</div>
							<div class="col-md-12">
								<div class="login-or">
									<hr class="hr-or">
									<span class="span-or">or</span>
								</div>
							</div>
							<div class="form-group">
								<p class="text-center">Don't have account? <a href="/register" id="signup">Sign up here</a></p>
							</div>
							<div class="form-group">
								<p class="text-center">Forgot Password? <a href="/register" id="signup">Reset Password</a></p>
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
