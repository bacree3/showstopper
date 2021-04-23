<?php
/**
 * This file includes the main functionaity for the application.
 *
 ** Requires parameters.php and sql.php
 ** Uses PHPMailer to send email through AWS SES
 *
 * @author Team 0306
 *
 * @since 1.0
 */

include 'parameters.php';
include 'sql.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aws\Ssm\SsmClient;

// streaming services reference constant for front-end
$servicesReference = ["Netflix", "Hulu", "Disney+", "Amazon Prime Video", "HBO Max"];
$serviceIMG = array(
  $servicesReference[0] => "netflix.jpg",
  $servicesReference[1] => "hulu.png",
  $servicesReference[2] => "disneyplus.jpg",
  $servicesReference[3] => "prime.jpg",
  $servicesReference[4] => "hbo.png",
);

/**
 * Translates form data from front-end to JSON object to store which services have been selected
 * @param  array $arr array of raw HTML form data
 * @return array JSON object formatted from service reference for storage
 */
function translateServices($arr) {
  global $servicesReference, $serviceIMG;
  $actualServices = [];
  foreach ($arr as $key => $value) {
    if ($value) {
      array_push($actualServices, $servicesReference[$key]);
    }
  }
  return $actualServices;
}

/**
 * Sends the user to the 404 page
 */
function goTo404() {
  http_response_code(404);
	include '../error/404.php';
	die();
}

/**
 * Sends the user to the login page
 */
function goToLogin() {
	header("Location:/login");
}

/**
 * Encapsulates any string for SQL manipulation
 * @param  string $str any string that needs to be encapsulated
 * @return string encapsulated string
 */
function str($str) {
  return "\"" . $str . "\"";
}

/**
 * Encapsulates string for JSON standards in SQL
 * @param string  $json a decoded JSON object that needs to be encapsulated
 * @return string encapsulated JSON object
 */
function json($json) {
  return "'" . $json . "'";
}

/**
 * Takes an array of columns/values and translates it into a string for SQL manipulation
 * Used in SQL functions for use of arrays in parameters to allow for large data manipulation
 * @param  array $arr array of column or value items
 * @return string string separated by commas based on the values in $arr
 */
function getString($arr) {
  return implode(', ', $arr);
}

/**
 * Generates the links for each actor for a title
 * @param  array  $actors array of actor id's
 * @return string raw html of links for each actor in a title
 */
function generateActorLinks($actors) {
  $actors = json_decode($actors);
  $links = "";
  foreach ($actors as $key => $id) {
    $data = getElementByID($id, 'people');
    $links .= "<a href = '/search?actor=" . $id . "'>" . $data['name'] . "</a>, ";
  }
  $links = rtrim($links, ", ");
  return $links;
}


/**
 * Add a new title to the database
 * @param array $title array of title data from OMDB
 */
function addTitle($title) {
  $titleColumns = ['id', 'titles.name', 'titles.release', 'summary', 'rating', 'services', 'img', 'genre'];
  $table = "titles";
  $values = [
    str($title["imdbID"]),
    str($title["Title"]),
    str($title["Year"]),
    str($title["Plot"]),
    str($title["Ratings"][0]['Value']),
    "'[]'",
    str($title["Poster"]),
    str($title["Genre"]),
  ];
  insert($titleColumns, $values, $table);
}

/**
 * Update an existing title in the database
 * @param string $id imdbID of title to be updated from OMDB
 */
function updateTitle($id) {
  $titleColumns = ['id', 'titles.name', 'titles.release', 'summary', 'rating', 'img', 'genre'];
  $updatedData = getTitleInfo($id);
  $values = [
    str($updatedData["imdbID"]),
    str($updatedData["Title"]),
    str($updatedData["Year"]),
    str($updatedData["Plot"]),
    str($updatedData["Ratings"][0]['Value']),
    str($updatedData["Poster"]),
    str($updatedData["Genre"])
  ];
  update($id, 'titles', $titleColumns, $values);
}

/**
 * Find the id of a person that is in the database using their name
 * @param  [type] $name [description]
 * @return [type]       [description]
 */
function findPerson($name) {
  $query = "SELECT id FROM people WHERE name = " . str($name) . ";";
  return query($query, true)[0];
}

/**
 * Check to see if an actor is already associated with an existing title
 * @param  string  $personID id of the person
 * @param  string  $imdbID   id of the title
 * @return boolean return true if person has title associated with it
 */
function hasTitle($personID, $imdbID) {
  $ids = getTitles($personID);
  if ($ids == null) {
    return false;
  } else {
    return in_array($imdbID, json_decode($ids));
  }
}

/**
 * Check to see if a person exists in the people database
 * @param  string $name name of person to check existence in the people database
 * @return boolean return boolean if a person exists or not
 */
function personExists($name) {
  $result = findPerson($name);
  if ($result != null) {
    return true;
  } else {
    return false;
  }
}

/**
 * Add new people to the people database
 * @param  array  $people people's name to be added
 * @param  string $type   type of people to add (director or actor...etc.)
 * @param  string $imdbID imdbID to add an association with for these new people
 * @return array  id's of people that we're added to the database
 */
function addPeople($people, $type, $imdbID) {
  $titleColumns = ['id', '`name`', 'type'];
  $peopleIDs = [];
  foreach ($people as $key => $person) {
    if (!personExists($person)) {
      $id = uniqid();
      array_push($peopleIDs, $id);
      $values = [
        str($id),
        str($person),
        str($type),
      ];
      insert($titleColumns, $values, 'people');
      addIMDB($id, $imdbID);
    } else {
      $id = findPerson($person)['id'];
      addIMDB($id, $imdbID);
    }
    array_push($peopleIDs, $id);
  }
  return $peopleIDs;
}

/**
 * Uses add people to add a number of actors to the database
 * @param  array  $actors array of actor names to add
 * @param  string $title  id of title that the actors are associated with
 * @return array  id's of people that we're added to the database
 */
function addActors($actors, $title) {
  return addPeople($actors, 'actor', $title);
}

/**
 * Add imdbID associations with a person
 * @param string $personID id of person
 * @param string $imdbID   id of title
 */
function addIMDB($personID, $imdbID) {
  $ids = getTitles($personID);
  if (!hasTitle($personID, $imdbID)) {
    if ($ids != null) {
      $ids = json_decode($ids);
      array_push($ids, $imdbID);
    } else {
      $ids = [$imdbID];
    }
    $query = "UPDATE people SET titles  = " . json(json_encode($ids)) . " WHERE id = " . str($personID) . ";";
    query($query, false);
  }
}

/**
 * Get titles associated with a person
 * @param  string $personID id of a person
 * @return array  array of titles associated with a person
 */
function getTitles($personID) {
  $result = getElementByID($personID, 'people');
  if ($result != null) {
    return $result['titles'];
  } else {
    return null;
  }
}

/**
 * Get all data associated with an element by it's identifier
 * @param  string $id    id of of element
 * @param  string $table table element is located in
 * @return array  array of data associated with the element
 */
function getElementByID($id, $table) {
  $query = "SELECT * FROM " . $table . " WHERE id = '" . $id . "';";
  $result = query($query, true);
  if ($result) {
    return $result[0];
  }
}

/**
 * Create a URL friendly string to pass on to the search page
 * @param  string $searchString steralized string after input of user
 * @return string string that has all spaces replaced by +
 */
function toSearchString($searchString) {
  return str_replace(' ', '+', $searchString);
}

/**
 * Get titles from basic search to display on search results
 * @param  string $titleString string steralized after input by user on search
 * @return array  array of titles similar to search string
 */
function searchByTitle($titleString) {
  global $omdbURL;
  $api_url = $omdbURL . "t=" . toSearchString($titleString);
  $data = json_decode(file_get_contents($api_url), true);
  if (!inCache($data['imdbID'], 'titles')) {
    addTitle($data);
  } else {
    // updateTitle($data['imdbID']);
    // implement update after update changes to only update services
  }
  return $data['imdbID'];
}

/**
 * Get data associated with a data based on the imdbID through OMDB
 * @param  string $id imdbID of a title
 * @return array  array of a title's data from OMDB
 */
function getTitleInfo($id) {
  global $omdbURL;
  $api_url = $omdbURL . "i=" . toSearchString($id);
  return json_decode(file_get_contents($api_url), true);
}

/**
 * Check to see if an element is in the database based on it's id
 * @param  string $id    id of element
 * @param  string $table table element is located in
 * @return array  array of data associated with the element
 */
function inCache($id, $table) {
  $query = "SELECT * FROM " . $table . " WHERE id = " . str($id) . ";";
  return query($query, true);
}

/**
 * Extract common words from the search string for enhanced similarity check within SQL
 * @param  string $string steralized input string from search
 * @return string enhanced string without 'common' words within searches
 */
function extractCommonWords($string){
    $stopWords = array('i', 'part', 'a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www');

    $string = preg_replace('/\s\s+/i', '', $string); // replace whitespace
    $string = trim($string); // trim the string
    $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too…
    $string = strtolower($string); // make it lowercase

    preg_match_all('/\b.*?\b/i', $string, $matchWords);
    $matchWords = $matchWords[0];

    foreach ( $matchWords as $key=>$item ) {
        if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3 ) {
            unset($matchWords[$key]);
        }
    }
    $wordCountArr = array();
    if ( is_array($matchWords) ) {
        foreach ( $matchWords as $key => $val ) {
            $val = strtolower($val);
            if ( isset($wordCountArr[$val]) ) {
                $wordCountArr[$val]++;
            } else {
                $wordCountArr[$val] = 1;
            }
        }
    }
    arsort($wordCountArr);
    $wordCountArr = array_slice($wordCountArr, 0, 10);
    return $wordCountArr;
}

/**
 * Form a generic like statement for a basic search in SQL
 * @param  string $str steralized search input
 * @param  string $col name of column to check within the like statement
 * @return string generic like statement for a SQL query
 */
function formLike($str, $col) {
  $final = "WHERE " . $col . " LIKE " . "'%" . array_key_first($str) . "%'";
  array_shift($str);
  if (sizeof($str) > 0) {
    foreach ($str as $word => $no) {
      $final .= " OR " . $col . " LIKE " . "'%" . $word . "%'";
    }
  }
  return $final;
}

/**
 * Form a generic like statement for a basic search in SQL for just one word
 * @param  string $str steralized search input
 * @param  string $col name of column to check within the like statement
 * @return string generic like statement for a SQL query
 */
function formSimpleLike($str, $col) {
  $final = "WHERE " . $col . " LIKE " . "'%" . $str . "%'";
  return $final;
}

/**
 * Form a generic and statement for SQL abstraction
 * @param  string $str value to and
 * @param  string $col column to check against
 * @return string generic and statment for SQL
 */
function formAndStatement($str, $col) {
  $final = "WHERE " . $col . " AND " . "'%" . $str . "%'";
  return $final;
}

/**
 * Get the generic link path for a specified title
 * @param  string $id imdbID of title
 * @return string later path of a link to a title's information
 */
function getTitlePath($id) {
  return "'/movie/?title=" . $id . "'";
}

/**
 * Get all titles related to a genre
 * @param  string $genre name of genre
 * @return array  all titles related to a genre
 */
function searchByGenre($genre) {
  $query = "SELECT * FROM titles WHERE genre LIKE " . str('%' . $genre . '%') . " ORDER BY `name`;";
  return query($query, true);
}

/**
 * Get all titles related to an actor
 * @param  string $actor id of actor
 * @return array  all titles related to an actor
 */
function searchByActor($actor) {
  $query = "SELECT * FROM titles WHERE actors LIKE " . str('%' . $actor . '%') . " ORDER BY `name`;";
  return query($query, true);
}

/**
 * Retrieve data for JS manipulation on front-end
 * @return array JSON object formatted variable containing necessary data for search suggestions
 */
function getTitlesForSearch() {
  return json_encode(query("SELECT id, name, services FROM titles;", true));
}

/**
 * Display registration error
 */
function registerError() {
  header("Location:/register?error=1");
}

/**
 * Send user to final page for account setup
 */
function finishSetup() {
  header("Location:/actsetup");
}

/**
 * Perform a web scrape for actors associated with a specified title
 * @param  string $id imdbID of a title
 * @return array  an object containing names to actors in the title
 */
function scrapeActors($id) {
  global $apiURL;
  $url = $apiURL . 'getActors';
  // The data to send to the API
  $postData = array(
      'id' => $id,
  );

  // Create the context for the request
  $context = stream_context_create(array(
      'http' => array(
          // http://www.php.net/manual/en/context.http.php
          'method' => 'POST',
          'header' => "Content-Type: application/json\r\n",
          'content' => json_encode($postData)
      )
  ));

  // Send the request
  $response = file_get_contents($url, FALSE, $context);

  // Check for errors
  if($response === FALSE){
      return false;
  }

  // Decode the response
  $responseData = json_decode($response, TRUE);

  // Print the date from the response
  //echo $id . "<br>";
  //print_r($responseData);
  return $responseData;
}

/**
 * Perform a web scrape for titles related to a specified titles
 * @param  string $title name of title
 * @return array  an object containing names to related titles
 */
function scrapeRelatedTitles($title) {
  global $apiURL;
  $url = $apiURL . 'getSimilarMovies';
  // The data to send to the API
  $postData = array(
      'title' => $title,
  );

  // Create the context for the request
  $context = stream_context_create(array(
      'http' => array(
          // http://www.php.net/manual/en/context.http.php
          'method' => 'POST',
          'header' => "Content-Type: application/json\r\n",
          'content' => json_encode($postData)
      )
  ));

  // Send the request
  $response = file_get_contents($url, FALSE, $context);

  // Check for errors
  if($response === FALSE){
      return false;
  }

  // Decode the response
  $responseData = json_decode($response, TRUE);

  // Print the date from the response
  return $responseData;
}

/**
 * Perform a web scrape for streaming service platforms that contain a specified title
 * @param  string $title name of title
 * @return array  an object containing names and links to streaming platforms
 */
function scrapePlatforms($title) {
  global $apiURL;
  $url = $apiURL . 'getPlatforms';
  // The data to send to the API
  $postData = array(
      'title' => $title,
  );

  // Create the context for the request
  $context = stream_context_create(array(
      'http' => array(
          // http://www.php.net/manual/en/context.http.php
          'method' => 'POST',
          'header' => "Content-Type: application/json\r\n",
          'content' => json_encode($postData)
      )
  ));

  // Send the request
  $response = file_get_contents($url, FALSE, $context);

  // Check for errors
  if($response === FALSE){
      return false;
  }

  // Decode the response
  $responseData = json_decode($response, TRUE);

  // Print the date from the response
  return $responseData;
}

/**
 * Generates user genre and platform preferences based on their favorites
 * @param  array $favorites An array of a user's favorited titles
 * @return array An array with two keys, "genres" which points to
 * an mapping of genre to the number of titles representing that genre
 * in the user's favorites, and $platforms which does the same for platforms
 */
function findPatterns($favorites) {
  if (!$favorites || $favorites == "false") {
    $favorites = [];
  }
  $genreBreakdown = [];
  $platformBreakdown = [];
  foreach($favorites as $key => $title) {
    $titleData = getElementByID($title, 'titles');
    $services = json_decode($titleData['services'], true);

    foreach ($services as $service) {
      $serviceName = $service['name'];
      if (array_key_exists($serviceName, $platformBreakdown)) {
        $platformBreakdown[$serviceName] = $platformBreakdown[$serviceName] + 1;
      } else {
        $platformBreakdown[$serviceName] = 1;
      }
    }

    $genreString = str($titleData['genre']);
    if (array_key_exists($genreString, $genreBreakdown)) {
      $genreBreakdown[$genreString] = $genreBreakdown[$genreString] + 1;
    } else {
      $genreBreakdown[$genreString] = 1;
    }

  }

  $returnArray = array (
    "genres" => $genreBreakdown,
    "platforms" => $platformBreakdown
  );
  return $returnArray;
}

/**
 * Add value to the "weight" of a movie in the db
 * to be used in calculating global recommendations
 * @param string $title  name of title
 * @param int $amount the amount of weight to be added
 */
function addWeight($title, $amount) {
  $titleData = getElementByID($title, 'titles');
  $query = "UPDATE titles SET weight = " . ($titleData['weight'] + $amount) . " WHERE id = " . str($title) . ";";
  if (is_null($titleData['weight'])) {
    $query = "UPDATE titles SET weight = " . $amount . " WHERE id = " . str($title) . ";";
  }
  query($query, false);
}

/**
 * Add value to or create might_like relationship between
 * a user and a title.
 * @param string $userID identifier for the user, usually the current user
 * @param string $title imdb id for a movie title
 * @param int $amount the amount of weight to be added
 */
function addUserWeight($userID, $title, $amount) {
  $query = "SELECT * FROM might_like WHERE (user_id = '" . $userID . "' AND movie_id = '" . $title . "');";
  $entry = query($query, true);

  $query = "UPDATE might_like SET weight = weight + " . $amount . " WHERE (user_id = '" . $userID . "' AND movie_id = '" . $title . "');";
  if (!$entry || count($entry) == 0) {
    $query = "INSERT INTO might_like VALUES('" . $userID . "', '" . $title . "', " . $amount . ");";
  }

  query($query, false);
}

/**
 * Get the list of most popular titles by weight from the database
 * @return array Array of the 5 or fewer most popular titles
 */
function getPopularTitles() {
  $query = "SELECT * FROM titles WHERE weight IS NOT NULL ORDER BY weight DESC;";
  $titles = query($query, true);
  return array_slice($titles, 0, 5);
}

/**
 * Get the list of titles user is most likely to be interested in based on
 * their weights in the might_like relation
 * @param $userID Identifier for the user
 * @return array Array of the 5 or fewer highest weighted titles for the user
 */
function getUserRecTitles($userID) {
  $query = "SELECT movie_id, weight FROM might_like WHERE user_id = '" . $userID . "' ORDER BY weight DESC;";
  $titles = query($query, true);
  return array_slice($titles, 0, 5);
}

/**
 * Update a title's information to include streaming services platforms that were scraped successfully
 * @param  string $title     id of title
 * @param  array  $platforms array of platforms that were scraped
 */
function insertPlatforms($title, $platforms) {
  $platforms = json_encode($platforms);
  $query = "UPDATE titles SET services = " . json($platforms) . " WHERE id = " . str($title) . ";";
  query($query, false);
}

/**
 * Generate HTML for a user's subscribed platforms on their account page
 * @param  array  $arr array of services associated with a user
 * @return string HTML of services that a user is subscribed to
 */
function getUserServicesHTML($arr) {
  global $servicesReference, $serviceIMG;
   foreach ($servicesReference as $key => $value) {
     $temp[$key] = $value;
   }
   $html = "<div class = 'row platforms ml-2'><div class ='platformsyes'>";
   foreach ($arr as $key => $service) {
     $key = array_search($service, $temp);
     unset($temp[$key]);
     $html .= "<img src='/src/img/" .  $serviceIMG[$service] . "' class='title rounded' alt=''...''>";
   }
   $html .= "</div><div class ='platformsno'>";
   foreach ($temp as $key => $service) {
     $html .= "<img src='/src/img/" . $serviceIMG[$service] . "' class='title rounded' alt=''...''>";
   }
   $html .= "</div></div>";
   return $html;
}

/**
 * Generate the HTML to display services for each title based on whether the respective service contains the title
 * @param  array  $platforms $platforms array of platforms scraped from the scrapePlatforms api
 * @return string raw HTML to be displayed on search/movie pages
 */
function getServicesHTML($platforms) {
  if (!$platforms || $platforms == "false") {
    $platforms = [];
  }
  global $servicesReference, $serviceIMG;
  $platformNames = array();
  $platformLinks = array();
  foreach ($platforms as $platform) {
      $platformNames[] = $platform['name'];
      $platformLinks[$platform['name']] = $platform['link'];
  }
  // create a temp array from the references constant where elements can be removed
  foreach ($servicesReference as $key => $value) {
    $temp[$key] = $value;
  }
  // generate HTML
  $html = "<div class ='platformsyes'>";
  foreach ($temp as $key => $service) {
    if (in_array($service, $platformNames)) {
      // if service exists, add it to the div that highlights the service along with the proper link
      $key = array_search($service, $temp);
      // remove service key from the temp array to be placed in the next div element
      unset($temp[$key]);
      $html .= "<a href = " . $platformLinks[$service] . " target = '_blank'><img src='/src/img/" .  $serviceIMG[$service] . "' class='title rounded' alt=''...''></a>";
    }
  }
  $html .= "</div><div class ='platformsno'>";
  foreach ($temp as $key => $service) {
    $key = array_search($service, $temp);
    unset($temp[$key]);
    $html .= "<img src='/src/img/" .  $serviceIMG[$service] . "' class='title rounded' alt=''...''>";
  }
  $html .= "</div>";
  return $html;
}

/**
 * Executes a PHP page asynchronously so the current page does not have to wait for it to finish running.
 * @param  string $url    url for api execution
 * @param  array  $params parameters to be passed to the api
 */
function post_async($url, array $params) {
    foreach ($params as $key => &$val) {
      if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);

    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;
    fwrite($fp, $out);
    fclose($fp);
}

/**
 * Get readable notifications for the notifications page based on user information
 * @param  string $userId id of a user
 * @return string raw HTML containing notifications for a user
 */
function getNotifications($userId) {
  //$results = query("SELECT * FROM notifications;", true);
  $results = query("SELECT * FROM notifications WHERE userId = " . $userId . ";", true);
  // implement query above when notifications are finalized
  $notifications = "";
  if (count($results) == 0) {
    $notifications =  "
    <div class='text-center col-xs-12 col-sm-8 col-md-12 col-lg-14 text-left'>
      <p>You currently don't have any notifications. Please check again later!</p>
      <hr/>
    </div>
    ";
  } else {
    foreach ($results as $key => $notification) {
      $actorAlert = "";
      if (isset($notification['actorId'])) {
        $actor = getElementByID($notification['actorId'], 'people')['name'];
        $actorAlert = ", which <a href = '/search?actor=" . $notification['actorId'] . "'>" . $actor . "</a> is in";
      }
      $title = getElementByID($notification['titleId'], 'titles')['name'];
      if ($notification['action'] == "added") {
        $row = "<div class='col-xs-12 col-sm-8 col-md-12 col-lg-14 text-left'><p><a href = '/movie?title=" . $notification['titleId'] . "'>" . $title . "</a> was added to " . $notification['service'] . $actorAlert . "!</p><hr/></div>";
      } else {
        $row = "<div class='col-xs-12 col-sm-8 col-md-12 col-lg-14 text-left'><p><a href = '/movie?title=" . $notification['titleId'] . "'>" . $title . "</a> was removed from " . $notification['service'] . $actorAlert . "!</p><hr/></div>";
      }
      $notifications .= $row;
    }
  }
  return $notifications;
}

/**
 * Add content to user's history in db
 * @param string $user    the id of the user currently logged in
 * @param string $content id of the content being added to the history
 * @param string $col     the type of content being added, either a person or a title
 */
function addToHistory($user, $content, $col) {
  $columns = "(id, user_id, " . $col . ")";
  $values = "(" . str(uniqid()) . ", " . str($user) . ", " . str($content) . ")";
  $query = "INSERT INTO history " . $columns . " VALUES " . $values . ";";
  query($query, false);
}

?>
