<html>
  	<head></head>
	<body>
		<nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>

		<!-- BODY -->
    	<div class="container mt-5">
        	<div class="row">
				<div class="col-md-8 mx-auto">
                    <div class="loginform form">
                        <div class="logo col-md-12 text-center">
                            <h1>Profile</h1>
                        </div>
                        <hr />
                        <div id="view">
                            <div class="column">
                                <div class="row">
                                    <div class="col-sm-5 col-md-4 col-5">
                                        <label style="font-weight:bold;">Name</label>
                                    </div>
                                    <div class="col-md-8 col-6">
                                        Jeremiah Wasa Bullfrog
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-5 col-md-4 col-5">
                                        <label style="font-weight:bold;">Email</label>
                                    </div>
                                    <div class="col-md-8 col-6">
                                        hewas@goodfriendof.mine
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-5 col-md-4 col-5">
                                        <label style="font-weight:bold;">Subscriptions</label>
                                    </div>
                                    <div class="col-md-8 col-6">
                                        <div class = 'row platforms mb-3'>
                                            <div class="col-md-3 col-sm-4 mb-2 platformsno">
                                                <img src="/src/img/netflix.jpg" alt="Netflix" class="img-fluid">
                                            </div>
                                            <div class="col-md-3 col-sm-4 mb-2">
                                                <img src="/src/img/hulu.png" alt="Hulu" class="img-fluid">
                                            </div>
                                            <div class="col-md-3 col-sm-4 mb-2">
                                                <img src="/src/img/disneyplus.jpg" alt="Hulu" class="img-fluid">
                                            </div>
                                            <div class="col-md-3 col-sm-4 mb-2">
                                                <img src="/src/img/prime.jpg" alt="Hulu" class="img-fluid">
                                            </div>
                                            <div class="col-md-3 col-sm-4 mb-2">
                                                <img src="/src/img/hbo.png" alt="Hulu" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn loginbtn btn-primary" onclick="editDetails()">Edit</button>
                                </div>
                            </div>
                        </div>
                    
                        <div id="edit" style="display:none">
                            <form>
                                <div class="form-group">
                                    <label>Change Name</label>
                                    <input type="name" name="name" id="name" class="form-control validate" value="Jeremiah Wasa Bullfrog">
                                </div>
                                <div class="form-group">
                                    <label>Change Email address</label>
                                    <input type="email" name="email" id="email" class="form-control validate" value="hewas@goodfriendof.mine">
                                </div>
                                <div class="form-group">
                                    <label>Edit Subscriptions</label>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-4 mb-2">
                                            <div class="custom-control custom-checkbox image-checkbox">
                                                <input type="checkbox" class="custom-control-input" name = "netflix" id="ck1a">
                                                <label class="custom-control-label" for="ck1a">
                                                    <img src="/src/img/netflix.jpg" alt="Netflix" class="img-fluid">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4 mb-2">
                                            <div class="custom-control custom-checkbox image-checkbox">
                                                <input type="checkbox" class="custom-control-input" name = "hulu" id="ck1b">
                                                <label class="custom-control-label" for="ck1b">
                                                    <img src="/src/img/hulu.png" alt="Hulu" class="img-fluid">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4 mb-2">
                                            <div class="custom-control custom-checkbox image-checkbox">
                                                <input type="checkbox" class="custom-control-input" name = "disney+" id="ck1c">
                                                <label class="custom-control-label" for="ck1c">
                                                    <img src="/src/img/disneyplus.jpg" alt="Disney+" class="img-fluid">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4 mb-2">
                                            <div class="custom-control custom-checkbox image-checkbox">
                                                <input type="checkbox" class="custom-control-input" name = "prime" id="ck1d">
                                                <label class="custom-control-label" for="ck1d">
                                                    <img src="/src/img/prime.jpg" alt="Amazon Prime Video" class="img-fluid">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4 mb-2">
                                            <div class="custom-control custom-checkbox image-checkbox">
                                                <input type="checkbox" class="custom-control-input" name = "hbo" id="ck1e">
                                                <label class="custom-control-label" for="ck1e">
                                                    <img src="/src/img/hbo.png" alt="HBO Max" class="img-fluid">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-block loginbtn btn-primary">Save Changes</button>
                                </div>
						    </form>
                        </div>
					</div>
				</div>
			</div>
      	</div>
        
        <script>
            function editDetails() {
                document.getElementById("view").style.display = "none";
                document.getElementById("edit").style.display = "inline";
            }
        </script>

		<!-- END BODY -->
		<div class = "footer mt-4 pt-4"></div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
	</body>
</html>
