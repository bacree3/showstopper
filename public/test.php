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

//print_r($servicesReference);

//echo getServicesHTML(["Hulu", "HBO Max"]);

/* if (isset($_SESSION['isLoggedIn'])) {
	echo "true";
} else {
	echo "false";
} */

//The url you wish to send the POST request to
/*$url = $file_name;

//The data you want to send via POST
$fields = [
    'title' => 'the avengers'
];

//url-ify the data for the POST
$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);
echo $result;*/
?>
<html>
  <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<link rel="stylesheet" href="/src/css/style.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<title>ShowStopper</title>
		<link rel="shortcut icon" href="/src/img/logo.png" />
  </head>
  <body>


		<div class = "footer mt-4 pt-4"></div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
  </body>
</html>

<script>
function test() {
	console.log("function started");
	var returnObject = {};
	var dataObject = {
		title: "the avengers"
	};
	var apiURL = '';
	$.ajax({
		type: 'POST',
		async: false,
		url: apiURL,
		crossDomain: true,
		dataType: 'json',
		contentType: 'application/json',
		data: JSON.stringify(dataObject),
		success: function(response){
		 console.log('object was returned');
		 //replaceWithButton('.' + button, buttonHTML);
		 returnObject = response;
		},
		error: function(response){
		 console.log('This query failed.');
		}
	});
	console.log("function ended.");
	return returnObject;
}
console.log(test());
</script>
