<?php

include 'parameters.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aws\Ssm\SsmClient;

//establish connection to MySQL Database
$conn = new mysqli($ip, $user, $password, $schema);
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error();
}

$servicesReference = ["Netflix", "Hulu", "Disney+", "Amazon Prime Video", "HBO Max"];
$serviceIMG = array(
  $servicesReference[0] => "netflix.jpg",
  $servicesReference[1] => "hulu.png",
  $servicesReference[2] => "disneyplus.jpg",
  $servicesReference[3] => "prime.jpg",
  $servicesReference[4] => "hbo.png",
);

function translateServices($arr) {
  global $servicesReference, $serviceIMG;
  //echo $services[0];
  $actualServices = [];
  foreach ($arr as $key => $value) {
    if ($value) {
      array_push($actualServices, $servicesReference[$key]);
    }
  }
  return $actualServices;
}

function goTo404() {
  http_response_code(404);
	include '../error/404.php';
	die();
}

function goToLogin() {
	header("Location:/login");
}

function str($str) {
  return "\"" . $str . "\"";
}

function json($json) {
  return "'" . $json . "'";
}

function getString($arr) {
  return implode(', ', $arr);
}

// turn sql result into array of rows with actual values
function sqlToArray($result) {
  $arr = [];
  while ($row = $result->fetch_assoc()) {
      array_push($arr, $row);
  }
  return $arr;
}

// do mysql query
function query($query, $return) {
  global $conn;
  $result = $conn->query($query);
  if (!$result) {
    return false;
  }
  if ($return) {
    return sqlToArray($result);
  }
}

// insert new row into table
// takes in an array of columns and an array of values to insert into the corresponding table
function insert($columns, $values, $table) {
  $query = "INSERT INTO " . $table . " (" . getString($columns) . ") VALUES (" . getString($values) . ");";
  //echo $query;
  query($query, false);
}

// update entry
function update($identifier, $table, $params, $values) {
  $updates = $params[0] . "=" . $values[0] . ", ";
  array_shift($values);
  if (sizeof($values) > 0) {
    $count = 1;
    foreach ($values as $key => $value) {
      $updates .= $params[$count] . "=" . $value . ",";
      $count++;
    }
  }
  $updates = rtrim($updates, ",");
  $query = "UPDATE " . $table . " SET " . $updates . " WHERE id = " . "'" . $identifier . "';";
  query($query, false);
}

// steralize string for mysql command
function steralizeString($str) {
  global $conn;
  return mysqli_real_escape_string($conn, $str);
}

// add director cache to db
function addDirectors($directors) {

}

function generateActorLinks($actors) {
  $actors = json_decode($actors);
  $links = "";
  foreach ($actors as $key => $id) {
    $data = getElementByID($id, 'people');
    $links .= "<a href = '/search?actor=" . $id . "'>" . $data['name'] . "</a>, ";
  }
  //$links .= "</p>";
  $links = rtrim($links, ", ");
  return $links;
}


// add title to cache db
function addTitle($title) {
  $titleColumns = ['id', 'titles.name', 'titles.release', 'summary', 'rating', 'services', 'img', 'genre'];
  $table = "titles";
  $values = [
    "'" . $title["imdbID"] . "'",
    "'" . $title["Title"] . "'",
    "'" . $title["Year"] . "'",
    "\"" . $title["Plot"] . "\"",
    "'" . $title["Ratings"][0]['Value'] . "'",
    "'[]'",
    "'" . $title["Poster"] . "'",
    "'" . $title["Genre"] . "'",
  ];
  $actors = scrapeActors($title["imdbID"]);
  $actors = addActors($actors, $title["imdbID"]);
  insert($titleColumns, $values, $table);
  $actors = array_unique($actors);
  $query = "UPDATE titles SET actors = " . json(json_encode($actors)) . " WHERE id = " . str($title["imdbID"]) . ";";
  //echo $query;
  query($query, false);
  //addDirectors();
}

function updateTitle($id) {
  $titleColumns = ['id', 'titles.name', 'titles.release', 'summary', 'rating', 'img', 'genre'];
  $updatedData = getTitleInfo($id);
  $values = [
    "'" . $updatedData["imdbID"] . "'",
    "'" . $updatedData["Title"] . "'",
    "'" . $updatedData["Year"] . "'",
    "\"" . $updatedData["Plot"] . "\"",
    "'" . $updatedData["Ratings"][0]['Value'] . "'",
    "'" . $updatedData["Poster"] . "'",
    "'" . $updatedData["Genre"] . "'",
  ];
  update($id, 'titles', $titleColumns, $values);
  $actors = scrapeActors($updatedData["imdbID"]);
  //print_r($actors);
  $actors = addActors($actors, $updatedData["imdbID"]);
  $actors = array_unique($actors);
  $query = "UPDATE titles SET actors = " . json(json_encode($actors)) . " WHERE id = " . str($id) . ";";
  query($query, false);
}

function personExists($name) {
  $query = "SELECT id, name FROM people WHERE name = " . str($name) . ";";
  //echo $query;
  $result = query($query, true);
  if ($result != null) {
    return true;
  } else {
    return false;
  }
}

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

function addActors($actors, $title) {
  return addPeople($actors, 'actor', $title);
}

function findPerson($name) {
  $query = "SELECT id FROM people WHERE name = " . str($name) . ";";
  //echo $query;
  return query($query, true)[0];
}

function hasTitle($personID, $imdbID) {
  $ids = getTitles($personID);
  if ($ids == null) {
    return false;
  } else {
    return in_array($imdbID, json_decode($ids));
  }
}

function addIMDB($personID, $imdbID) {
  $ids = getTitles($personID);
  if (!hasTitle($personID, $imdbID)) {
    if ($ids != null) {
      $ids = json_decode($ids);
      array_push($ids, $imdbID);
    } else {
      $ids = [$imdbID];
    }
    //array_push($ids, $imdbID);
    $query = "UPDATE people SET titles  = " . json(json_encode($ids)) . " WHERE id = " . str($personID) . ";";
    //echo $query;
    query($query, false);
  }
}

function getTitles($personID) {
  $result = getElementByID($personID, 'people');
  if ($result != null) {
    return $result['titles'];
  } else {
    return null;
  }
}

// get details from omdb by id rather then search
function getElementByID($id, $table) {
  $query = "SELECT * FROM " . $table . " WHERE id = '" . $id . "';";
  $result = query($query, true);
  if ($result) {
    return $result[0];
  }
}

function toSearchString($searchString) {
  return str_replace(' ', '+', $searchString);
}

// get titles from basic search to display on search results
function searchByTitle($titleString) {
  global $omdbURL;
  $api_url = $omdbURL . "t=" . toSearchString($titleString);
  //echo $api_url;
  $data = json_decode(file_get_contents($api_url), true);
  //print_r($data);
  if (!inCache($data['imdbID'], 'titles')) {
    //echo "not in cache";
    addTitle($data);
  } else {
    //updateTitle($data['imdbID']);
  }
}

// using omdb get details from api
function getTitleInfo($id) {
  global $omdbURL;
  $api_url = $omdbURL . "i=" . toSearchString($id);
  return json_decode(file_get_contents($api_url), true);
}

function inCache($id, $table) {
  $query = "SELECT * FROM " . $table . " WHERE id = " . "'" . $id . "'" . ";";
  return query($query, true);
}

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

function formSimpleLike($str, $col) {
  $final = "WHERE " . $col . " LIKE " . "'%" . $str . "%'";
  return $final;
}

function getTitlePath($id) {
  return "'/movie/?title=" . $id . "'";
}

function populateServices() {

}

function searchByGenre($genre) {
  $query = "SELECT * FROM titles WHERE genre LIKE " . str('%' . $genre . '%') . " ORDER BY `name`;";
  //echo $query;
  return query($query, true);
}

function searchByActor($actor) {
  $query = "SELECT * FROM titles WHERE actors LIKE " . str('%' . $actor . '%') . " ORDER BY `name`;";
  //echo $query;
  return query($query, true);
}

function getTitlesForSearch() {
  return json_encode(query("SELECT id, name, services FROM titles;", true));
}

function registerError() {
  header("Location:/register?error=1");
}

function finishSetup() {
  header("Location:/actsetup");
}

function verifyEmail($email) {
  query("UPDATE users SET verified = 1 WHERE email = " . str($email) . ";", false);
}

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

function scrapeRelatedTitles($title) {
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

function insertPlatforms($title, $platforms) {
  $platforms = json_encode($platforms);
  $query = "UPDATE titles SET services = " . json($platforms) . " WHERE id = " . str($title) . ";";
  //echo $query;
  query($query, false);
}

function getActiveServices($platforms) {
  $platformNames = array();
  $platformLinks = array();
  foreach ($platforms as $platform) {
      $platformNames[] = $platform['name'];
      $platformLinks[$platform['name']] = $platform['link'];
  }
}


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
  foreach ($servicesReference as $key => $value) {
    $temp[$key] = $value;
  }
  //print_r($temp);
  $html = "<div class ='platformsyes'>";
  foreach ($temp as $key => $service) {
    if (in_array($service, $platformNames)) {
      $key = array_search($service, $temp);
      unset($temp[$key]);
      $html .= "<a href = " . $platformLinks[$service] . " target = '_blank'><img src='/src/img/" .  $serviceIMG[$service] . "' class='title rounded' alt=''...''></a>";
    }
  }
  $html .= "</div><div class ='platformsno'>";
  //print_r($temp);
  foreach ($temp as $key => $service) {
    //if (in_array($service, $platformNames)) {
      $key = array_search($service, $temp);
      unset($temp[$key]);
      $html .= "<img src='/src/img/" .  $serviceIMG[$service] . "' class='title rounded' alt=''...''>";
    //}
  }
  $html .= "</div>";
  return $html;
}

function generatePlatformsHTML($platforms) {
  $platformNames = array();
  $platformLinks = array();
  foreach ($platforms as $platform) {
      $platformNames[] = $platform['name'];
      $platformLinks[$platform['name']] = $platform['link'];
  }
	return "
		<a class='" . in_array('Netflix', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded' . " href='" . $platformLinks['Netflix'] . "'>
				<img src='/src/img/netflix.jpg' class='rounded title' alt='...'>
		</a>
		<a class='" . in_array('Hulu', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded' . " href='" . $platformLinks['Hulu'] . "'>
				<img src='/src/img/netflix.jpg' class='rounded title' alt='...'>
		</a>
		<a class='" . in_array('Amazon Prime Video', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded' . " href='" . $platformLinks['Amazon Prime Video'] . "'>
				<img src='/src/img/netflix.jpg' class='rounded title' alt='...'>
		</a>
		<a class='" . in_array('HBO Max', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded' . " href='" . $platformLinks['Netflix'] . "'>
				<img src='/src/img/netflix.jpg' class='rounded title' alt='...'>
		</a>
		<a class='" . in_array('Disney+', $platformNames) ? 'platformsyes rounded' : 'platformsno rounded' . " href='" . $platformLinks['Disney+'] . "'>
				<img src='/src/img/netflix.jpg' class='rounded title' alt='...'>
		</a>
	";
}

//$params['my_param'] = $a_value;
//post_async('http:://localhost/batch/myjob.php', $params);

/*
 * Executes a PHP page asynchronously so the current page does not have to wait for it to     finish running.
 *
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
    //return
}

?>
