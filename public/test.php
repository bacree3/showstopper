<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

//print_r(getTitleInfo('tt0848228'));

/*$titles = query("SELECT id FROM titles;", true);

foreach ($titles as $key => $title) {
	updateTitle($title['id']);
} */

//updateTitle('the dark knight');
//
/*if (isset($_POST['setup'])) {
	$name = steralizeString($_POST['name']);

	$netflix = (isset($_POST['netflix'])) ? true : false;
	$hulu = (isset($_POST['hulu'])) ? true : false;
	$disney = (isset($_POST['disney+'])) ? true : false;
	$prime = (isset($_POST['prime'])) ? true : false;
	$hbo = (isset($_POST['hbo'])) ? true : false;

	$services = [$netflix, $hulu, $disney, $prime, $hbo];
	//echo $name;
	//print_r($services);
	//print_r();
	json_encode(translateServices($services));
} */
//print_r($serviceIMG);
//echo getServicesHTML(["Netflix", "HBO Max"]);

print_r($services);

?>
