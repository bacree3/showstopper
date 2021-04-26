<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (!isLoggedIn()) {
	goToLogin();
} else {
  $info = getUserInfo($_SESSION['userID']);
	//print_r($info);
  $name = $info['name'];
  $email = $info['email'];
  $services = json_decode($info['services']);
  $servicesHTML = getUserServicesHTML(json_decode($info['services']));
}
?>

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
    					<h1>Account Details</h1> </div>
    				<hr />
    				<div id="view">
    					<div class="row">
    						<div class="col-sm-5 col-md-4 col-5">
    							<label style="font-weight:bold;">Name</label>
    						</div>
    						<div class="col-md-8 col-6">
    							<?php echo $name; ?>
    						</div>
    					</div>
    					<hr />
    					<div class="row">
    						<div class="col-sm-5 col-md-4 col-5">
    							<label style="font-weight:bold;">Email</label>
    						</div>
    						<div class="col-md-8 col-6">
    							<?php echo $email; ?>
    						</div>
    					</div>
    					<hr />
    					<div class="row">
    						<div class="col-sm-5 col-md-4 col-5">
    							<label style="font-weight:bold;">Subscriptions</label>
    						</div>
    						<?php echo $servicesHTML; ?>
    					</div>
							<hr />
							<div class="row">
    						<div class="col-sm-5 col-md-4 col-5">
    							<label style="font-weight:bold;">Search History</label>
    						</div>
    						<div class="col-md-8 col-6">
    							<a href="/history">View Search History</a>
    						</div>
    					</div>
							<hr />
							<div class="row">
								<div class="col-sm-5 col-md-4 col-5">
									<label style="font-weight:bold;">Receive Notifications Through</label>
								</div>
								<div class="col-md-8 col-6">
									<label><?php echo $info['delivery'];?></label>
							</div>
						</div>
							<hr />
							<div class="row">
								<div class="col-sm-5 col-md-4 col-5">
									<label style="font-weight:bold;">Notifications</label>
								</div>
								<div class="col-md-8 col-6">
									<label>Actors: <?php echo $info['actorNotification'] ? 'On' : 'Off';?></label>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-5 col-md-4 col-5">
								</div>
								<div class="col-md-8 col-6">
									<label>Titles: <?php echo $info['titleNotification'] ? 'On' : 'Off';?></label>
								</div>
							</div>
    					<div class="col-md-12 mt-2 text-center">
    						<button type="submit" class="btn loginbtn btn-primary" onclick="editDetails()">Edit</button>
    					</div>
    				</div>



    				<div id="edit" style="display:none">
							<form action="account.php" method="post" name="register">
    						<input type="hidden" name="edit" value="" />
    						<div class="form-group">
    							<label>Change Name</label>
    							<input type="name" name="name" id="name" class="form-control validate" value="<?php echo $name; ?>"> </div>
    						<div class="form-group">
    							<label>Change Email address</label>
    							<input type="email" name="email" id="email" class="form-control validate" value="<?php echo $email; ?>"> </div>
    						<div class="form-group">
    							<label>Edit Subscriptions</label>
    							<div class="row">
    								<div class="col-md-3 col-sm-4 mb-2">
    									<div class="custom-control custom-checkbox image-checkbox">
    										<input type="checkbox" class="custom-control-input" name="netflix" id="ck1a" <?php echo in_array("Netflix", json_decode($info['services'])) ? 'checked' : '';?>>
    										<label class="custom-control-label" for="ck1a"> <img src="/src/img/netflix.jpg" alt="Netflix" class="img-fluid"> </label>
    									</div>
    								</div>
    								<div class="col-md-3 col-sm-4 mb-2">
    									<div class="custom-control custom-checkbox image-checkbox">
    										<input type="checkbox" class="custom-control-input" name="hulu" id="ck1b" <?php echo in_array("Hulu", json_decode($info['services'])) ? 'checked' : '';?>>
    										<label class="custom-control-label" for="ck1b"> <img src="/src/img/hulu.png" alt="Hulu" class="img-fluid"> </label>
    									</div>
    								</div>
    								<div class="col-md-3 col-sm-4 mb-2">
    									<div class="custom-control custom-checkbox image-checkbox">
    										<input type="checkbox" class="custom-control-input" name="disney+" id="ck1c" <?php echo in_array("Disney+", json_decode($info['services'])) ? 'checked' : '';?>>
    										<label class="custom-control-label" for="ck1c"> <img src="/src/img/disneyplus.jpg" alt="Disney+" class="img-fluid"> </label>
    									</div>
    								</div>
    								<div class="col-md-3 col-sm-4 mb-2">
    									<div class="custom-control custom-checkbox image-checkbox">
    										<input type="checkbox" class="custom-control-input" name="prime" id="ck1d" <?php echo in_array("Amazon Prime Video", json_decode($info['services'])) ? 'checked' : '';?>>
    										<label class="custom-control-label" for="ck1d"> <img src="/src/img/prime.jpg" alt="Amazon Prime Video" class="img-fluid"> </label>
    									</div>
    								</div>
    								<div class="col-md-3 col-sm-4 mb-2">
    									<div class="custom-control custom-checkbox image-checkbox">
    										<input type="checkbox" class="custom-control-input" name="hbo" id="ck1e" <?php echo in_array("HBO Max", json_decode($info['services'])) ? 'checked' : '';?>>
    										<label class="custom-control-label" for="ck1e"> <img src="/src/img/hbo.png" alt="HBO Max" class="img-fluid"> </label>
    									</div>
    								</div>
    							</div>
    						</div>
								<hr />
								<div class="form-group">
									<label>Receive Notifications Through</label>
									<div class="row">
										<div class="col-md-8 col-6">
											<div class="custom-control custom-radio">
											  <input type="radio" id="customRadio1" name="delivery" class="custom-control-input" value = "Email" <?php echo $info['delivery'] == 'Email' ? 'checked' : '' ?>>
											  <label class="custom-control-label" for="customRadio1">Email</label>
											</div>
											<div class="custom-control custom-radio">
											  <input type="radio" id="customRadio2" name="delivery" class="custom-control-input" value = "Push Notifications" <?php echo $info['delivery'] == 'Push Notifications' ? 'checked' : '' ?>>
											  <label class="custom-control-label" for="customRadio2">Push Notifications</label>
											</div>
									</div>
								</div>
							</div>
							<hr />
								<div class="form-group">
									<label>Edit Notifications</label>
									<div class="row">
										<div class="col-md-8 col-6">
											<div class="custom-control custom-switch">
											  <input name = "actorNotification" type="checkbox" class="custom-control-input" id="customSwitch1" <?php echo $info['actorNotification'] ? 'checked' : '' ?>>
											  <label class="custom-control-label" for="customSwitch1">Actor Notifications</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-8 col-6">
											<div class="custom-control custom-switch">
											  <input name = "titleNotification" type="checkbox" class="custom-control-input" id="customSwitch2" <?php echo $info['titleNotification'] ? 'checked' : '' ?>>
											  <label class="custom-control-label" for="customSwitch2">Title Notifications</label>
											</div>
										</div>
									</div>
							</div>
							<div class="row">
								<div class="col-md-4 text-center">
									<button onClick="deleteAccount(); return false;" class="btn btn-block loginbtn btn-danger">Delete Account</button>
								</div>
								<div class="col-md-4 text-center">
									<button onClick="window.location.reload(); return false;" class="btn btn-block loginbtn btn-warning">Cancel</button>
								</div>
								<div class="col-md-4 text-center">
									<button type="submit" class="btn btn-block loginbtn btn-primary">Save Changes</button>
								</div>
							</div>
    					</form>
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
