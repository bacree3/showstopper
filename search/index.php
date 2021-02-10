<?php

include 'php/functions.php';

if (isset($_GET['search'])) {
	$searchString = steralizeString($_GET['search']);
} else {
	header("Location:/index.php");
}

search($searchString);

//$jsonString = '{"a": "test", "b": "test", "c": "test"}';
//$json = json_encode($jsonString);
//jsonToArray("a, b", $json);
//search($searchString);

/* $titleData = getElementByID('tt0848228', 'titles');

print_r($titleData);

echo $titleData['img']; */

//echo $json -> Title;

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
      <div class="srow rounded">
        <p class="lead font-weight-bold">Showing Results for: user input</p>
      </div>
      <div class="row pt-4">
        <div class="col-3">
          <div class= "filters rounded">
            <p class="lead font-weight-bold">Filters: </p>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                <label class="form-check-label" for="defaultCheck1">
                  <p class="lead">Netflix</p>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
                <label class="form-check-label" for="defaultCheck2">
                  <p class="lead">Hulu</p>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck3">
                <label class="form-check-label" for="defaultCheck3">
                  <p class="lead">Amazon Prime</p>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck4">
                <label class="form-check-label" for="defaultCheck4">
                  <p class="lead">HBOMax</p>
                </label>
              </div>
          </div>
        </div>
        <div class="col-9 justify-content-center">
          <div class="row pt-4 bg-light pb-4 rounded">
            <div class="col-xs-12 col-md-4 text-center">
              <img src="/img/avengers.jpg" class="rounded title" alt="...">
            </div>
            <div class="col-xs-12 col-md-8 text-left">
              <h1 class="display-3">Movie Title</h1>
              <p class="lead">Cast: </p>
              <p class="lead">Summary: </p>
              <p class="lead">Rating: </p>
              <p class="lead">Release Date: </p>
              <p class="lead">Platforms: </p>
            </div>
          </div>

          <div class="row pt-4 bg-light pb-4 mt-4 rounded">
            <div class="col-xs-12 col-md-4 text-center">
              <img src="/img/avengers.jpg" class="rounded title" alt="...">
            </div>
            <div class="col-xs-12 col-md-8 text-left">
              <h1 class="display-3">Movie Title</h1>
              <p class="lead">Cast: </p>
              <p class="lead">Summary: </p>
              <p class="lead">Rating: </p>
              <p class="lead">Release Date: </p>
              <p class="lead">Platforms: </p>
            </div>
          </div>

          <div class="row pt-4 bg-light pb-4 mt-4 rounded">
            <div class="col-xs-12 col-md-4 text-center">
              <img src="/img/avengers.jpg" class="rounded title" alt="...">
            </div>
            <div class="col-xs-12 col-md-8 text-left">
              <h1 class="display-3">Movie Title</h1>
              <p class="lead">Cast: </p>
              <p class="lead">Summary: </p>
              <p class="lead">Rating: </p>
              <p class="lead">Release Date: </p>
              <p class="lead">Platforms: </p>
            </div>
          </div>
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