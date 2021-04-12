<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
	$delivery = $_POST['delivery'];
	$actorNotification = isset($_POST['actorNotification']) ? 1 : 0;
	$titleNotification = isset($_POST['titleNotification']) ? 1 : 0;
	$query = "UPDATE users SET delivery = " . str($delivery) . ", actorNotification = " . $actorNotification . ", titleNotification = " . $titleNotification . " WHERE id = " . str($_SESSION['userID']) . ";";
	query($query, false);
	header("Location:/account");
} else {
	echo "form not detected";
}

?>
