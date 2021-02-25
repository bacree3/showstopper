<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_POST['edit'])) {
	$name = steralizeString($_POST['name']);

	$netflix = (isset($_POST['netflix'])) ? true : false;
	$hulu = (isset($_POST['hulu'])) ? true : false;
	$disney = (isset($_POST['disney+'])) ? true : false;
	$prime = (isset($_POST['prime'])) ? true : false;
	$hbo = (isset($_POST['hbo'])) ? true : false;

	$services = [$netflix, $hulu, $disney, $prime, $hbo];

	accountSetup($_SESSION['userID'], $name, json_encode(translateServices($services)));
	header("Location:/account");
} else {
	echo "form not detected";
}

?>
