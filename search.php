<?php

include 'php/functions.php';

if (isset($_GET['search'])) {
	$searchString = $_GET['search'];
}

insert($titleColumns, $titleValues, "titles");

?>
