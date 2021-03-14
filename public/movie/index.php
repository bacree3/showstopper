<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_GET['title']) && !empty($_GET['title'])) {
	$title = steralizeString($_GET['title']);
	//updateTitle($_GET['title']);
	$titleData = getElementByID($title, 'titles');
	if (!$titleData) {
		goTo404();
	}
} else {
	goTo404();
}

//$platformsList = scrapePlatforms($titleData['name']);
$platformsList = [];
$platformNames = array();
$platformLinks = array();
foreach ($platformsList as $platform) {
    $platformNames[] = $platform['name'];
    $platformLinks[$platform['name']] = $platform['link'];
}


?>

<html>

  <head></head>

  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->

    <div class="container">
		<div class="row pt-4 bg-light pb-4 mt-4 rounded">
			<div class="d-flex flex-row align-items-center">
				<div class="ml-4 mr-4" onclick='changeFavStatus(<?php echo str($titleData['id']); ?>)'>
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
        	<div class="col-xs-12 col-sm-4 col-md-4 text-center">
          		<img src="<?php echo $titleData['img'];?>" class="rounded title img-fluid searchImg" alt="...">
       		</div>
        	<div class="col-xs-12 col-sm-8 col-md-8 text-left">
				<p class="lead"><span class = "font-weight-bold">Cast:</span> <?php echo generateActorLinks($titleData['actors']);?></p>
				<p class="lead"><span class = "font-weight-bold">Summary:</span> <?php echo $titleData['summary'];?></p>
				<p class="lead"><span class = "font-weight-bold">IMDB Ratings:</span> <?php echo $titleData['rating'];?></p>
				<p class="lead"><span class = "font-weight-bold">Release Date:</span> <?php echo $titleData['release'];?></p>
				<p class="lead"><span class = "font-weight-bold">Platforms:</span> </p>
				<div class = 'row platforms ml-1'>
                    <a id="netflixLink" class="<?=in_array('Netflix', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded'?>" href="<?php echo $platformLinks['Netflix'];?>">
                        <img src='/src/img/netflix.jpg' class='rounded title' alt='...'>
                    </a>
                    <a id="huluLink" class="<?=in_array('Hulu', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded'?>" href="<?php echo $platformLinks['Hulu'];?>">
                        <img src='/src/img/hulu.png' class='rounded title' alt='...'>
                    </a>
                    <a class="<?=in_array('Amazon Prime Video', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded'?>" href="<?php echo $platformLinks['Amazon Prime Video'];?>">
                        <img src='/src/img/prime.jpg' class='rounded title' alt='...'>
                    </a>
                    <a class="<?=in_array('HBO Max', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded'?>" href="<?php echo $platformLinks['HBO Max'];?>">
                        <img src='/src/img/hbo.png' class='rounded title' alt='...'>
                    </a>
                    <a class="<?=in_array('Disney+', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded'?>" href="<?php echo $platformLinks['Disney+'];?>">
                        <img src='/src/img/disneyplus.jpg' class='rounded title' alt='...'>
                    </a>
				</div>
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

<script>
// NEED SERVER LOGIC
	var isFavorite = <?php echo isFavorited($titleData['id']); ?>;
  	if (!isFavorite) {
			$("#" + <?php echo str($titleData['id']); ?> +"isFavorite").hide();
  	} else {
			$("#" + <?php echo str($titleData['id']); ?> + "isNotFavorite").hide();
  	}

</script>
