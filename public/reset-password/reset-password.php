<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_POST['reset'])) {
	$pass = steralizeString($_POST['password']);
	$pass = password_hash($pass, PASSWORD_DEFAULT);
	$s = steralizeString($_POST['s']);
	$email = steralizeString($_POST['email']);
	$query = "SELECT id, email, pass FROM users WHERE email = " . str($email) . " AND pass = " . str($s) . ";";
	$result = query($query, true)[0];
	if (!$result == null) {
		$query = "UPDATE users SET pass = " . str($pass) . " WHERE id = " . str($result['id']) . ";";
		query($query, false);
		goToLogin();
	} else {
		goTo404();
	}
} else {
	goTo404();
}

?>
