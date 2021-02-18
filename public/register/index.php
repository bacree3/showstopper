<html>
  	<head></head>
	<body>
		<nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>

		<!-- BODY -->
    	<div class="container mt-5">
        	<div class="row">
				<div class="col-md-5 mx-auto">
					<div class="registerform form">
						<div class="logo col-md-12 text-center">
							<h1>Register</h1>
						</div>
						<form action="" method="post" name="register">
							<div class="form-group" >
								<label for="validationDefault01">Email address</label>
								<input type="email" name="email" id="validationDefault01" class="form-control validate" placeholder="Enter email">
							</div>
							<div class="form-group">
								<label for="validationDefault02">Password</label>
								<input type="password" name="password" id="validationDefault02" class="form-control validate" placeholder="Enter Password">
							</div>
              <div class="form-group">
								<label>Confirm Password</label>
								<input type="password" name="password" id="password" class="form-control" placeholder="Confirm Password">
							</div>
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-block registerbtn btn-primary">Register</button>
							</div>
							<div class="col-md-12">
								<div class="login-or">
									<hr class="hr-or">
									<span class="span-or">or</span>
								</div>
							</div>
							<div class="form-group">
								<p class="text-center">Already have an account? <a href="#" id="login">Login here</a></p>
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
