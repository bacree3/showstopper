<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

if (isset($_GET['action'])) {
  if ($_GET['action'] == 'clear') {
    clearHistory();
  }
}

if (!isLoggedIn()) {
  header('Location:/');
} else {
  $history = getHistory();
}

?>


<html>
  <head></head>

  <body>
    <nav class= "header navbar navbar-expand-lg sticky-top navbar-dark"></nav>
		<!-- BODY -->

      <div class="mx-4 pt-4 bg-light pb-4 mt-4 rounded">
        <div class = "text-center">
          <h3>User Search History</h3>
          <a class="btn btn-danger" href="/history/?action=clear" role="button">Delete Search History</a>
        </div>
        <hr>
        <div class = "history">
          <?php
          if ($history == NULL) {
            echo "View some of the content on the platform to start generating a history!";
          }
          foreach ($history as $key => $line) {
            if ($line['title'] != NULL) {
              $html = "<div class = 'row'><div class = 'col-3 text-left'><a href = '/movie/?title=" . $line['title']. "'>" . getElementByID($line['title'], 'titles')['name'] . "</a></div><div class = 'col-6'></div><div class = 'col-3 text-right'>" . $line['updated'] . "</div></div>";
            } else {
              $html = "<div class = 'row'><div class = 'col-3 text-left'><a href = '/search/?actor=" . $line['person']. "'>" . getElementByID($line['person'], 'people')['name'] . "</a></div><div class = 'col-6'></div><div class = 'col-3 text-right'>" . $line['updated'] . "</div></div>";
            }
            echo $html;
          }
          ?>
        </div>
      </div>

		<!-- END BODY -->
		<div class = "footer mt-4 pt-4"></div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
		<script src = "/src/js/script.js"></script>
	</body>
</html>
