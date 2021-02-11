<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

if (isset($_GET['search'])) {
	$searchString = steralizeString($_GET['search']);
} else {
	header("Location:/");
}

searchByTitle($searchString); // get data from api if not in cache

$likeStatment = formLike(extractCommonWords($searchString), 'name'); // get keywords for search in cache

$results = query("SELECT * FROM titles " . $likeStatment . ";", true);

//print_r($results);

//$jsonString = '{"a": "test", "b": "test", "c": "test"}';
//$json = json_encode($jsonString);
//jsonToArray("a, b", $json);
//search($searchString);

/* $titleData = getElementByID('tt0848228', 'titles');

print_r($titleData);

echo $titleData['src/src/img']; */

//echo $json -> Title;

?>

<html>
  <head></head>
  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->


		<div class="container">
      <div class="srow rounded">
        <p class="lead font-weight-bold">Showing Results for: <?php echo $searchString; ?></p>
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

					<?php
					$resultsHTML = "";
					foreach ($results as $key => $title) {
					  $row = "
					    <div onclick = 'location.href=" . "\"" . '/movie/?title=' . $title['id'] . "\"" .  "' style = 'cursor: pointer;' class='row pt-4 bg-light pb-4 mt-4 rounded movie " . $title['id'] ."'>
					      <div class='col-xs-12 col-md-4 text-center'>
					        <img src='" . $title['img'] . "' class='rounded title' alt='...'>
					      </div>
					      <div class='col-xs-12 col-md-8 text-left'>
					        <h1 class='display-3'>" . $title['name'] . "</h1>
					        <p class='lead'><span class = 'font-weight-bold'>Cast:</span> " . $title['actors'] . "</p>
					        <p class='lead'><span class = 'font-weight-bold'>Summary:</span> " . $title['summary'] . "</p>
					        <p class='lead'><span class = 'font-weight-bold'>IMDB Rating:</span> " . $title['rating'] . "</p>
					        <p class='lead'><span class = 'font-weight-bold'>Release Date:</span> " . $title['release'] . "</p>
					        <p class='lead'><span class = 'font-weight-bold'>Platforms:</span> </p>
					      </div>
					    </div>"
					  ;
					  $resultsHTML .= $row;
					}
					echo $resultsHTML;
					?>

        </div>
      </div>
    </div>

		<!-- END BODY -->
		<div class = "footer mt-4 pt-4"></div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
	</body>
</html>
