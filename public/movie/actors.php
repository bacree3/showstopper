<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$actors = scrapeActors($id);
	$actors = addActors($actors, $id);
	$actors = array_unique($actors);
	$query = "UPDATE titles SET actors = " . json(json_encode($actors)) . " WHERE id = " . str($id) . ";";
	//echo $query;
	query($query, false);
}

?>
