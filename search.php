<?php

include 'php/functions.php';

if (isset($_GET['search'])) {
	$searchString = steralizeString($_GET['search']);
}
//$jsonString = '{"a": "test", "b": "test", "c": "test"}';
//$json = json_encode($jsonString);
//jsonToArray("a, b", $json);
search($searchString);
//echo $json;

//echo $json -> Title;

?>
