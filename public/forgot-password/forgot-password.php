<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_POST['forgot'])) {
	$email = steralizeString($_POST['email']);
	sendMail($email, 'password');
	header("Location:/reset-link-sent");
}

?>
