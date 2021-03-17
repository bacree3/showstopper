<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	echo getServicesHTML(getElementByID($id, 'titles')['services']);
}

?>
