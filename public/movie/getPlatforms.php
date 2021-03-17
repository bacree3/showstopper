<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$title = getElementByID($id, 'titles');
	//echo json_encode($title['services']);
	echo getServicesHTML(json_decode($title['services'], true));
}

?>
