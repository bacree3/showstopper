<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_GET['search'])) {
	$searchString = steralizeString($_GET['search']);
	$id = searchByTitle($searchString); // get data from api if not in cache
	$title = getElementByID($id, 'titles');
	$likeStatment = formLike(extractCommonWords($searchString), 'name'); // get keywords for search in cache
	$results = query("SELECT * FROM titles " . $likeStatment . " ORDER BY `name`, `release` desc;", true);

    foreach ($results as $key => $title) {
        if ($title['id'] == $id) {
            addWeight($title['id'], 2);
        } else {
            addWeight($title['id'], 1);
        }
    }

	if ($title['related'] != '') {
		$related = json_decode($title['related']);
	} else {
		$related = [];
		$titleList = scrapeRelatedTitles($searchString);
		foreach ($titleList as $key => $name) {
            $relatedID = searchByTitle($name);
			array_push($related, $relatedID);
            addWeight($relatedID, 1);
		}
			/*print_r($titleList);

			for ($i = 0; $i < count($titleList); $i++) {
					//searchByTitle($titleList[%i]);
					$likeStatment = formSimpleLike($titleList[$i], 'name');
					$cacheResults = query("SELECT * FROM titles " . $likeStatment . " ORDER BY `name`, `release` desc;", true);
					if ($cacheResults) {
							$results = array_merge($results, $cacheResults);
					}

			}
			*/
			// add related id's to cache db
	}
	foreach ($related as $key => $id) {
		array_push($results, getElementByID($id, 'titles'));
	}
	$results = array_unique($results, SORT_REGULAR);
	function myFilter($var){
    return ($var !== NULL && $var !== FALSE && $var !== "" && $var['id'] !== NULL && $var['id'] !== FALSE && $var['id'] !== "");
	}

	// Filtering the array
	$results = array_filter($results, "myFilter");
	$query = "UPDATE titles SET `related` = " . json(json_encode($related)) . " WHERE id = " . str($title['id']) . ";";

	query($query, false);
	//$query = "UPDATE titles SET actors = " . json(json_encode($actors)) . " WHERE id = " . str($id) . ";";
	//print_r($results);

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
		<div class="row justify-content-center">

			<div class="col-10 justify-content-center pl-4">
				<div class="row">
					<div class="col-9">
						<div class="srow rounded">
							  	<p class="lead font-weight-bold">Showing Results for: <?php echo $searchString; ?></p>
		      	</div>
					</div>
					<div class="col-3">
						<div class="dropdown justify-content-center mt-3">
						  <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Platform Filters
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <li class = "m-1 pl-2"><a data-value="option1"><input type="checkbox"/> Netflix</a></li>
								<li class = "m-1 pl-2"><a data-value="option1"><input type="checkbox"/> Hulu</a></li>
								<li class = "m-1 pl-2"><a data-value="option1"><input type="checkbox"/> Amazon Prime</a></li>
								<li class = "m-1 pl-2"><a data-value="option1"><input type="checkbox"/> Disney+</a></li>
								<li class = "m-1 pl-2"><a data-value="option1"><input type="checkbox"/> HBO Max</a></li>

						  </div>
						</div>
					</div>
				</div>

					<?php
					$resultsHTML = "";
					foreach ($results as $key => $title) {
							if ($title['services'] == '[]' || $title['services'] == '' || $title['services'] == NULL) {
								$services = "
								<div class='spinner-border text-danger' role='status'>
									<span class='sr-only'>Loading...</span>
								</div>
								";
							} else {
								$services = getServicesHTML(json_decode($title['services'], true));
							}

							//$services = $title['services'] == '[]' || $title['services'] == 'false' ? "test" : getServicesHTML(json_decode($title['services'], true));
					  	$row = "
					    	<div class='row pt-4 bg-light pb-4 mt-4 rounded movie" . $title['id'] ."'>
								<div class='col-xs-4 col-md-1 text-center' onclick='changeFavStatus(" . str($title['id']) . ")'>
									<svg id='".$title['id'] . "isNotFavorite' xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='#283a59' class='bi bi-heart' viewBox='0 0 16 16'>
										<path d='M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
									</svg>
									<svg id='".$title['id'] . "isFavorite' xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='#283a59' class='bi bi-heart-fill' viewBox='0 0 16 16'>
										<path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>
									</svg>
								</div>
								<div class='col-xs-8 col-md-3 text-center' onclick='location.href=" . "\"" . '/movie/?title=' . $title['id'] . "\"" .  "' style='cursor: pointer;'>
					        		<img src='" . $title['img'] . "' class='rounded title movieImg' alt='...'>
					      		</div>
					      		<div id = " . $title['id'] . " class='col-xs-12 col-md-8 text-left'>
					        		<h1 class='display-4' onclick='location.href=" . "\"" . '/movie/?title=' . $title['id'] . "\"" .  "' style='cursor: pointer;'>" . $title['name'] . "</h1>
									<p class='lead'>
										<span class='font-weight-bold'>Release Date:</span> " . $title['release'] . "
									</p>
					        		<p class='lead'>
										<span class='font-weight-bold'>Platforms:</span>
									</p>
									<div class='row platforms ml-2'>
										" . $services . "
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

<?php
	$favorites = [];
	$needsUpdate = [];
	foreach ($results as $key => $title) {
		if (isFavorited($title['id'])) {
			array_push($favorites, $title['id']);
		}
		$services = $title['services'];
		if ($services == 'false' || $services == '[]' || $services == NULL) {
			array_push($needsUpdate, $title['id']);
		}
	}
	//print_r($favorites);
?>

<!-- <script>
    function alterFavStatus(movieID) {
        // $titleData = getElementByID($title, 'titles');
        var isFavorite = <?php echo isFavorited(movieID); ?>;
        changeFavStatus(<?php echo str($titleData['id']); ?>);
        if (!isFavorite) {
            $(<?php echo addWeight($titleData['id'], 1); ?>).hide();
        } else {
            $(<?php echo addWeight($titleData['id'], -1); ?>).hide();
        }
    }
</script>  -->

<script type="text/javascript">
	var results = <?php echo json_encode($results); ?>;
	var favorites = <?php echo json_encode($favorites); ?>;
	var needsUpdate = <?php echo json_encode($needsUpdate); ?>;
	//console.log(titles)

	for (key of Object.keys(results)) {
		results[key].servicesBool = getServicesBoolean(results[key].services);
		if (favorites.includes(results[key].id)) {
			isFavorite = true;
		} else {
			isFavorite = false;
		}
		if (!isFavorite) {
			$("#" + results[key].id + "isFavorite").hide();
		} else {
			$("#" + results[key].id + "isNotFavorite").hide();
		}
		var spinner = "<div class='spinner-border text-danger' role='status'><span class='sr-only'>Loading...</span></div>";
		var element = $("#" + results[key].id + " .platforms");
		if (element.children().hasClass("spinner-border") || checkDate(results[key].updated)) {
			$.ajax({
				url: '/movie/platforms.php',
				type: 'GET',
				dataType: 'text',
				contentType: 'application/json',
				data: {
					id: results[key].id,
					name: results[key].name + " " + results[key].release,
					update: checkDate(results[key].updated)
				},
				success: function(response) {
					for (id of needsUpdate) {
						updateLoadPlatforms(id);
					}
				}
			});
			$.ajax({
				url: '/movie/actors.php',
				type: 'GET',
				dataType: 'text',
				contentType: 'application/json',
				data: {
					id: results[key].id,
				},
				success: function(response) {
					console.log("success");
				}
			});
		}
	}
</script>
