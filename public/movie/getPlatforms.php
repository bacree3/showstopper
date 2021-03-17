<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

if (isset($_GET['platforms'])) {
	$platforms = $_GET['platforms'];
	echo getServicesHTML(json_decode($platforms, true));
}

?>
