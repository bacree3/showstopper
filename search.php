<?php

include 'php/functions.php';

if (isset($_GET['search'])) {
	$searchString = steralizeString($_GET['search']);
} else {
	header("Location:index.php");
}

search($searchString);

//$jsonString = '{"a": "test", "b": "test", "c": "test"}';
//$json = json_encode($jsonString);
//jsonToArray("a, b", $json);
//search($searchString);
//echo $json;

/* $titleData = getElementByID('tt0848228', 'titles');

print_r($titleData);

echo $titleData['img']; */

//echo $json -> Title;

?>
