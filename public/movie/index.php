<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';
//include $_SERVER['DOCUMENT_ROOT'] . '/php/platforms.php';

if (isset($_GET['title']) && !empty($_GET['title'])) {
	$title = steralizeString($_GET['title']);
	//updateTitle($_GET['title']);
	$titleData = getElementByID($title, 'titles');
	if (isLoggedIn()) {
		addToHistory(getCurrentUserID(), $title, "title");
	}
	if (!$titleData) {
		goTo404();
	} else {
		addWeight($titleData['id'], 1);
		if (isLoggedIn()) {
			addUserWeight(getCurrentUserID(), $titleData['id'], 1);
		}
	}
} else {
	goTo404();
}

//$platformsList = scrapePlatforms($titleData['name']);
//$params['title'] = $titleData['name'];
//$platformsList = insertPlatforms($apiURL . '/getPlatforms', $params);

?>

<html>

  <head></head>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->

    <div class="container">
		<div class="row pt-4 bg-light pb-4 mt-4 rounded">
			<div class="d-flex flex-row align-items-center">
				<!-- <div class="ml-4 mr-4" onclick='changeFavStatus(<?php echo str($titleData['id']); ?>)'> -->
				<div class="ml-4 mr-4" onclick='alterFavStatus()'>
					<svg id="<?php echo $titleData['id']; ?>isNotFavorite" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#283a59" class="bi bi-heart" viewBox="0 0 16 16">
						<path d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
					</svg>
					<svg id="<?php echo $titleData['id']; ?>isFavorite" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#283a59" class="bi bi-heart-fill" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
					</svg>
				</div>
				<div class="text-left">
					<h1 class="display-3"><?php echo $titleData['name'];?></h1>
				</div>
			</div>
		</div>
      	<div class="row pt-4 bg-light pb-4 mt-1 rounded">
        	<div class="col-xs-12 col-sm-4 col-md-4 mb-3 text-center">
          		<img src="<?php echo $titleData['img'];?>" class="rounded title img-fluid searchImg" alt="...">
       		</div>
        	<div id = "<?php echo $titleData['id']; ?>" class="col-xs-12 col-sm-8 col-md-8 text-left">
						<div class="lead"><span class = "font-weight-bold">Cast:</span>
							<span class = "actors">
								<?php
								if ($titleData['actors'] == '[]' || $titleData['actors'] == '' || $titleData['actors'] == NULL) {
									echo "
									<div class='spinner-border text-danger' role='status'>
										<span class='sr-only'>Loading...</span>
									</div>
									";
									?>
									<script>
										$.ajax({
											url: '/movie/actors.php',
											type: 'GET',
											dataType: 'text',
											contentType: "application/json",
											data: {
												id: <?php echo str($titleData['id']); ?>,
											},
											success: function(response) {
												console.log(response);
												updateLoadActors(<?php echo str($titleData['id']); ?>);
											}
										});
									</script>
									<?php
								} else {
									echo generateActorLinks($titleData['actors']);
								}
								?>
							</span>
						</div>
						<p class="lead"><span class = "font-weight-bold">Summary:</span> <?php echo $titleData['summary'];?></p>
						<p class="lead"><span class = "font-weight-bold">IMDB Ratings:</span> <?php echo $titleData['rating'];?></p>
						<p class="lead"><span class = "font-weight-bold">Release Date:</span> <?php echo $titleData['release'];?></p>
						<p class="lead"><span class = "font-weight-bold">Platforms:</span> </p>
						<div class = 'row platforms ml-2'>
							<?php
								//echo $titleData['services'];
								if ($titleData['services'] == '[]' || $titleData['services'] == '' || $titleData['services'] == NULL || $titleData['services'] == 'false') {
									echo "
									<div class='spinner-border text-danger' role='status'>
										<span class='sr-only'>Loading...</span>
									</div>
									";
									//$data['title'] = $titleData['name'];
									//$data['id'] = $titleData['id'];
									//print_r($data);
									//$url = $_SERVER['DOCUMENT_ROOT'] . 'php/platforms.php';
									//$url = 'http://localhost/php/platforms.php';
									//echo $url;
									//post_async($url, $data);
									?>
									<script>
										$.ajax({
									    url: '/movie/platforms.php',
									    type: 'GET',
									    dataType: 'text',
									    contentType: "application/json",
									    data: {
									      id: <?php echo str($titleData['id']); ?>,
									      name: <?php echo str($titleData['name'] . " " . $titleData['release']); ?>
									    },
									    success: function(response) {
												//console.log("getting data");
												console.log(response);
												var titleID = <?php echo str($titleData['id']); ?>;
												//console.log(test);
									      updateLoadPlatforms(titleID);
									    }
									  });
									</script>
									<?php
								} else {
									echo getServicesHTML(json_decode($titleData['services'], true));
								}
							?>
						</div>
        	</div>
      	</div>
    </div>

		<!-- END BODY -->
		<div class = "footer mt-4 pt-4"></div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
	</body>
</html>

<script>
	var isFavorite = <?php echo isFavorited($titleData['id']); ?>;
  	if (!isFavorite) {
			$("#" + <?php echo str($titleData['id']); ?> +"isFavorite").hide();
  	} else {
			$("#" + <?php echo str($titleData['id']); ?> + "isNotFavorite").hide();
  	}

  	function alterFavStatus() {
  		changeFavStatus(<?php echo str($titleData['id']); ?>);
  		if (!isFavorite) {
  			$(<?php echo addWeight($titleData['id'], 1); ?>).hide();
  		} else {
  			$(<?php echo addWeight($titleData['id'], -1); ?>).hide();
  		}
  	}
</script>
