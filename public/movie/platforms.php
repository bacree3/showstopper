<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

if (isset($_GET['id']) && isset($_GET['name'])) {
	$name = $_GET['name'];
	$id = $_GET['id'];
	//echo $name;
	//$title = 'Avengers: Age of Ultron';
	//$id = 'tt2395427';
	$platforms = scrapePlatforms($name);
	insertPlatforms($id, $platforms);
	if ($_GET['update']) {
		updateTitle($id);
	}
	echo json_encode($platforms);
}

?>
