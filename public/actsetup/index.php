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
							<h1>Tell us some more about yourself!</h1>
						</div>
						<form action="" method="post" name="actdetails">
							<div class="form-group">
								<label>What's your name?</label>
								<input type="text" name="name" class="form-control" placeholder="Enter first name">
							</div>
							<div class="form-group">
								<label>What subscription services are you already subscribed to?</label>
								<div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="ck1a">
                                            <label class="custom-control-label" for="ck1a">
                                                <img src="/src/img/netflix.jpg" alt="Netflix" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="ck1b">
                                            <label class="custom-control-label" for="ck1b">
                                                <img src="/src/img/hulu.png" alt="Hulu" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="ck1c">
                                            <label class="custom-control-label" for="ck1c">
                                                <img src="/src/img/disneyplus.jpg" alt="Disney+" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="ck1d">
                                            <label class="custom-control-label" for="ck1d">
                                                <img src="/src/img/prime.jpg" alt="Amazon Prime Video" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="ck1d">
                                            <label class="custom-control-label" for="ck1d">
                                                <img src="/src/img/hbo.png" alt="HBO Max" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="col-md-12 text-center mb-3">
								<button type="submit" class="btn btn-block loginbtn btn-primary">Next</button>
							</div>
                            <div class="col-md-12 text-center">
								<button type="submit" class="btn loginbtn btn-secondary">Skip</button>
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
