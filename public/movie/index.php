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

?>

<html>

  <head></head>

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
          <p class="lead"><span class = "font-weight-bold">Cast:</span> <?php echo generateActorLinks($titleData['actors']);?></p>
          <p class="lead"><span class = "font-weight-bold">Summary:</span> <?php echo $titleData['summary'];?></p>
          <p class="lead"><span class = "font-weight-bold">IMDB Ratings:</span> <?php echo $titleData['rating'];?></p>
          <p class="lead"><span class = "font-weight-bold">Release Date:</span> <?php echo $titleData['release'];?></p>
          <p class="lead"><span class = "font-weight-bold">Platforms:</span> </p>
					<div class = 'row platforms ml-1'>
						<div class ='platformsyes rounded'>
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
      </div>
    </div>

		<!-- END BODY -->
		<div class = "footer mt-4 pt-4"></div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
	</body>
</html>
