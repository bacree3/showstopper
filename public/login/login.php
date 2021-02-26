<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_POST['login'])) {
	$email = steralizeString($_POST['email']);
	$pass = steralizeString($_POST['password']);
	if (login($email, $pass)) {
		header("Location:/");
	} else {
		header("Location:/login?error=1");
	}
} else {
	header("Location:/login");
}


?>
