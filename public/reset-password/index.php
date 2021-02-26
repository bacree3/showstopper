<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_GET['s']) && !empty($_GET['s']) && passwordResetAllowed()) {
  $s = steralizeString($_GET['s']);
  $result = query("SELECT id, email FROM users WHERE pass = " . str($s) . ";", true)[0];
  if ($result != null && passwordResetAllowed()) {
    $email = $result['email'];
    disallowPasswordReset($result['id']);
  } else {
    header('Location:/');
  }
} else {
  header('Location:/');
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
							<h1>Reset Password</h1>
						</div>
						<form action="reset-password.php" method="post" name="reset" onsubmit="return checkPasswords()">
              <input type="hidden" name="reset" value=""/>
              <input type="hidden" name="email" value="<?php echo $email; ?>"/>
              <input type="hidden" name="s" value="<?php echo $s; ?>"/>
							<div class="form-group">
                <label>New Password</label>
								<input type="password" name="password" id="password" class="form-control validate" placeholder="Password">
							</div>
              <div class="form-group">
                <label>Confirm New Password</label>
								<input type="password" name="confirmpassword" id="confirmpassword" class="form-control validate" placeholder="Confirm Password">
							</div>


							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-block loginbtn btn-primary" onsubmit>Reset</button>
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
