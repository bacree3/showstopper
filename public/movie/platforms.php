<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

if (isset($_GET['id']) && isset($_GET['name'])) {
	$title = $_GET['name'];
	$id = $_GET['id'];
	//$title = 'Avengers: Age of Ultron';
	//$id = 'tt2395427';
	$platforms = scrapePlatforms($title);
	insertPlatforms($id, $platforms);
	echo json_encode($platforms);
}

?>
