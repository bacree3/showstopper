<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

$popTitles = getPopularTitles();
// $forYouTitles = getDemoForYouTitles();
$forYouTitles = array();
$name = "";
if (isLoggedIn()) {
  $forYouTitles = getUserRecTitles(getCurrentUserID());
  $info = getUserInfo($_SESSION['userID']);
  $name = " ";
  $name .= $info['name'];
}

?>
<html>
  <head></head>
  <body>
    <nav class="header navbar navbar-expand-lg navbar-dark"></nav>

    <!-- BODY -->

    <div class="jumbotron jumbotron-fluid d-flex flex-column align-items-center justify-content-start">
      <h1 class="display-4">ShowStopper</h1>
      <p class="lead text-center">Get the most out of your streaming services</p>
      <div class="d-flex justify-content-around">
        <p id="signup" class="mr-1">Don't have an account?</p>
        <p id="welcome" class="mr-1">Welcome back, <?php echo $name; ?>!</p>
        <a id="signuplink"href="/register">Sign up today</a>
      </div>
    </div>

    <!-- movies container -->
    <div class="container">

      <!-- trending movies -->
      <div class="row pb-5">
        <div class="col-12 col-lg-3 align-self-center text-center">
            <h3 class="text-white pr-4">Trending</p>
        </div>

        <?php
          $resultsHTML = "";
          foreach($popTitles as $key => $title) {
            $movieImg = "
            <div class='col'>
              <div class='' onclick='location.href=" . "\"" . '/movie/?title=' . $title['id'] . "\"" .  "' style='cursor: pointer;'>
                <img src='" . $title['img'] . "' class='rounded title movieImg' alt='...'>
              </div>
            </div>
            ";
            $resultsHTML .= $movieImg;
          }
          echo $resultsHTML;
        ?>
      </div>

      <!-- displays if not logged in -->
      <div class="row justify-content-center" id="seereccs">
        <h4 class="text-white text-center">Sign in to see recommendations made just for you!</p>
      </div>

      <!-- displays if logged in -->
      <div class="row" id="reccs">
        <div class="col-12 col-lg-3 align-self-center text-center">
          <h3 class="text-white">Recommended For You</p>
        </div>
        <?php
          $resultsHTML = "";
          foreach($forYouTitles as $key => $movie) {
            $title = getElementByID($movie['movie_id'], 'titles');
            $movieImg = "
            <div class='col'>
              <div class='' onclick='location.href=" . "\"" . '/movie/?title=' . $title['id'] . "\"" .  "' style='cursor: pointer;'>
                <img src='" . $title['img'] . "' class='rounded title movieImg' alt='...'>
              </div>
            </div>
            ";
            $resultsHTML .= $movieImg;
          }
          echo $resultsHTML;
        ?>
      </div>
    </div>

    <!-- END BODY -->
    <!--<div class = "footer mt-4 pt-4"></div> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
	</body>
</html>

<script>
  if (loggedin) {
  	document.getElementById("signup").hidden = true;
    document.getElementById("signuplink").hidden = true;
    document.getElementById("seereccs").hidden = true;
  } else {
    document.getElementById("welcome").hidden = true;
    document.getElementById("reccs").hidden = true;
  }

</script>
