<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_GET['id']) && isLoggedIn()) {
	$favoriteID = $_GET['id'];
	//$favoriteID = "tt0478970";
	$user = getCurrentUserID();
	$favorites = getFavorites();
	//print_r($favorites);
	if (in_array($favoriteID, $favorites)) {
		$key = array_search($favoriteID, $favorites);
		unset($favorites[$key]);
	} else {
		array_push($favorites, $favoriteID);
	}
	$query = "UPDATE users SET favorites = " . json(json_encode(array_values($favorites))) . " WHERE id = " . str($user) . ";";
	query($query, false);
}
?>
