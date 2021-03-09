<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_GET['search'])) {
	$searchString = steralizeString($_GET['search']);
	searchByTitle($searchString); // get data from api if not in cache
	$likeStatment = formLike(extractCommonWords($searchString), 'name'); // get keywords for search in cache
	$results = query("SELECT * FROM titles " . $likeStatment . " ORDER BY `name`, `release` desc;", true);
} else if (isset($_GET['genre'])) {
	$searchString = steralizeString($_GET['genre']);
	$results = searchByGenre($searchString);
} else if (isset($_GET['actor'])) {
	$searchString = steralizeString($_GET['actor']);
	$results = searchByActor($searchString);
	$searchString = getElementByID($searchString, 'people')['name'];
} else {
	goTo404();
}



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
  <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->


		<div class="container">
      <div class="srow rounded">
        <p class="lead font-weight-bold">Showing Results for: <?php echo $searchString; ?></p>
      </div>
      <div class="row pt-4">
        <div class="col-3">
          <div class= "filters rounded" >
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
							<div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck4">
                <label class="form-check-label" for="defaultCheck4">
                  <p class="lead">Disney Plus</p>
                </label>
              </div>
          </div>
        </div>
        <div class="col-9 justify-content-center">

					<?php
					$resultsHTML = "";
					foreach ($results as $key => $title) {
					  	$row = "
					    	<div onclick='location.href=" . "\"" . '/movie/?title=' . $title['id'] . "\"" .  "' style='cursor: pointer;' class='row pt-4 bg-light pb-4 mt-4 rounded movie" . $title['id'] ."'>
								<div class='col-xs-4 col-md-1 text-center'>
									<svg id='".$title['id'] . "isNotFavorite' xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='#283a59' class='bi bi-heart' viewBox='0 0 16 16'>
										<path d='M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
									</svg>
									<svg id='".$title['id'] . "isFavorite' xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='#283a59' class='bi bi-heart-fill' viewBox='0 0 16 16'>
										<path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>
									</svg>
								</div>
								<div class='col-xs-8 col-md-3 text-center'>
					        		<img src='" . $title['img'] . "' class='rounded title movieImg' alt='...'>
					      		</div>
					      		<div class='col-xs-12 col-md-8 text-left'>
					        		<h1 class='display-4'>" . $title['name'] . "</h1>
									<p class='lead'>
										<span class='font-weight-bold'>Release Date:</span> " . $title['release'] . "
									</p>
					        		<p class='lead'>
										<span class='font-weight-bold'>Platforms:</span>
									</p>
									<div class='row platforms ml-2'>
										<div class='platformsyes rounded'>
											<img src='/src/img/netflix.jpg' class='rounded title' alt=''...''>
											<img src='/src/img/hulu.png' class='rounded title' alt=''...''>
											<img src='/src/img/prime.jpg' class='rounded title' alt=''...''>
										</div>
										<div class ='platformsno rounded'>
											<img src='/src/img/hbo.png' class='rounded title' alt=''...''>
											<img src='/src/img/disneyplus.jpg' class='rounded title' alt=''...''>
										</div>
									</div>
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

<script type="text/javascript">
	var titles = <?php echo json_encode($results); ?>;
	for (title of titles) {
		var isFavorite = false; // NEEED SERVER THING
		if (!isFavorite) {
			document.getElementById(title.id + "isFavorite").style.display = "none";
		} else {
			document.getElementById(title.id + "isNotFavorite").style.display = "none";
		}
	}	
</script>