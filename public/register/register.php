<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_POST['register'])) {
	$email = steralizeString($_POST['email']);
	$pass = steralizeString($_POST['password']);
	if (createUser($email, $pass)) {
		header("Location:/actsetup");
	} else {
		header("Location:/register");
	}
}

if (isset($_POST['setup'])) {
	$name = steralizeString($_POST['name']);

	$netflix = (isset($_POST['netflix'])) ? true : false;
	$hulu = (isset($_POST['hulu'])) ? true : false;
	$disney = (isset($_POST['disney+'])) ? true : false;
	$prime = (isset($_POST['prime'])) ? true : false;
	$hbo = (isset($_POST['hbo'])) ? true : false;

	$services = [$netflix, $hulu, $disney, $prime, $hbo];

	accountSetup($_SESSION['userID'], $name, json_encode(translateServices($services)));
	header("Location:/");
}


?>
