<?php

echo realpath('index.php');

include '/php/functions.php';

if (isset($_GET['title']) && !empty($_GET['title'])) {
	$title = steralizeString($_GET['title']);
	$titleData = getElementByID($title, 'titles');
	if (!$titleData) {
		header("Location:/");
	}
} else {
	header("Location:/");
}

?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <title>ShowStopper</title>
    <link rel="shortcut icon" href="/img/logo.png" />

  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->

    <div class="container">
      <div class="row pt-4 bg-light pb-4 mt-4 rounded">
        <div class="col-xs-12 col-md-4 text-center">
          <img src="<?php echo $titleData['img'];?>" class="rounded title" alt="...">
        </div>
        <div class="col-xs-12 col-md-8 text-left">
          <h1 class="display-3"><?php echo $titleData['name'];?></h1>
          <p class="lead">Cast: </p>
          <p class="lead">Summary: </p>
          <p class="lead">Rating: </p>
          <p class="lead">Release Date: </p>
          <p class="lead">Platforms: </p>
        </div>
      </div>
    </div>

		<!-- END BODY -->
		<div class = "footer mt-4 pt-4"></div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/js/script.js"></script>
	</body>
</html>
