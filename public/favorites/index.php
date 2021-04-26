<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (!isLoggedIn()) {
  header('Location:/');
} else {
  $favorites = getFavorites();
}

?>

<html>
  <head></head>

  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->

    <div class="container">
      <div class="srow rounded">
        <h1 class="lead font-weight-bold">Your Favorites</h1>
        <p class = "lead text-center">Click the "X" to remove a title or actor from your favorites.</p>
      </div>
      <div class="srow rounded">
        <h1 class="lead font-weight-bold">Favorited Titles</h1>
      </div>
      <div class="col pt-4">
        <?php
          $count = 0;
          $html = "
          <div class='row justify-content-md-center'>
          ";
          foreach ($favorites as $key => $favorite) {
            $actor = getElementByID($favorite, 'people');
            if (substr($favorite, 0 ,2) == 'tt') {
              $titleData = getElementByID($favorite, 'titles');
              $html .= "
              <div id = '" . $titleData['id'] . "' class='col-2.5 bg-light rounded p-2 m-2 favorite' onclick='location.href=" . "\"" . '/movie/?title=' . $titleData['id'] . "\"" .  "' style='cursor: pointer;'>
                <div class='row pl-1'>
                  <div class='col-1'>
                    <button type='button' onclick = 'changeFavStatus(" . str($titleData['id']) . "); removeFavoriteCard(" . str($titleData['id']) . ")' class='btn btn-dark btn-sm align-left'>X</button>
                  </div>
                  <div class='col'>
                    <p class = 'lead text-center'>" . $titleData['name'] . "</p>
                  </div>
                </div>
                <img src='" . $titleData['img'] . "' class='rounded title' alt='...'>
              </div>
              ";
            }
          }
          $html .= "</div>";
          echo $html;
        ?>
      </div>
      <hr />
      <div class="srow rounded">
        <h1 class="lead font-weight-bold">Favorited Actors</h1>
      </div>
      <div class="col pt-4">
        <?php
          $count = 0;
          $html = "
          <div class='row justify-content-md-center'>
          ";
          foreach ($favorites as $key => $favorite) {
            $actor = getElementByID($favorite, 'people');
            if (substr($favorite, 0 ,2) != 'tt') {
              $html .= "
              <div id = '" . $actor['id'] . "' class='col-2.5 bg-light rounded p-2 m-2 favorite' onclick='location.href=" . "\"" . '/search/?actor=' . $actor['id'] . "\"" .  "' style='cursor: pointer;'>
                <div class='row pl-1'>
                  <div class='col-1'>
                    <button type='button' onclick = 'changeFavStatus(" . str($actor['id']) . "); removeFavoriteCard(" . str($actor['id']) . ")' class='btn btn-dark btn-sm align-left'>X</button>
                  </div>
                  <div class='col'>
                    <p class = 'lead text-center'>Actor</p>
                  </div>
                </div>
                <h1 class = 'text-center favorite-actor'>" . $actor['name'] . "</h1>
              </div>
              ";
            }
          }
          $html .= "</div>";
          echo $html;
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
