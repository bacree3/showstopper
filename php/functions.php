<?php

include 'parameters.php';

//establish connection to MySQL Database
$conn = new mysqli($ip, $user, $password, $schema);
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error();
}

$servicesReference = ["Netflix", "Hulu", "Disney+", "Amazon Prime", "HBO Max"];
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
  $actors = explode(", ",$updatedData['Actors']);
  //print_r($actors);
  $actors = addActors($actors, $updatedData["imdbID"]);
  $query = "UPDATE titles SET actors = " . json(json_encode($actors)) . " WHERE id = " . str($id) . ";";
  query($query, false);
}

function addActors($actors, $title) {
  return addPeople($actors, 'actor', $title);
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
  $titleColumns = ['id', 'titles.name', 'titles.release', 'summary', 'rating', 'img', 'genre'];
  $table = "titles";
  $values = [
    "'" . $title["imdbID"] . "'",
    "'" . $title["Title"] . "'",
    "'" . $title["Year"] . "'",
    "\"" . $title["Plot"] . "\"",
    "'" . $title["Ratings"][0]['Value'] . "'",
    "'" . $title["Poster"] . "'",
    "'" . $title["Genre"] . "'",
  ];
  $actors = explode(", ",$title['Actors']);
  $actors = addActors($actors, $title["imdbID"]);
  insert($titleColumns, $values, $table);
  $query = "UPDATE titles SET actors = " . json(json_encode($actors)) . " WHERE id = " . str($title["imdbID"]) . ";";
  query($query, false);
  //addDirectors();
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
  $data = json_decode(file_get_contents($api_url), true);
  if (!inCache($data['imdbID'], 'titles')) {
    addTitle($data);
  } else {
    updateTitle($data['imdbID']);
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
    $stopWords = array('i','a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www');

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

function getTitlePath($id) {
  return "'/movie/?title=" . $id . "'";
}

function populateServices() {

}

function searchByGenre($genre) {
  $query = "SELECT * FROM titles WHERE genre LIKE " . str('%' . $genre . '%') . ";";
  //echo $query;
  return query($query, true);
}

function searchByActor($actor) {
  $query = "SELECT * FROM titles WHERE actors LIKE " . str('%' . $actor . '%') . ";";
  //echo $query;
  return query($query, true);
}

function getServicesHTML($arr) {
  global $servicesReference, $serviceIMG;
  foreach ($servicesReference as $key => $value) {
    $temp[$key] = $value;
  }
  //print_r($temp);
  $html = "<div class = 'row platforms ml-2'><div class ='platformsyes'>";
  foreach ($arr as $key => $service) {
    $key = array_search($service, $temp);
    unset($temp[$key]);
    $html .= "<img src='/src/img/" .  $serviceIMG[$service] . "' class='title rounded' alt=''...''>";
  }
  $html .= "</div><div class ='platformsno'>";
  //print_r($temp);
  foreach ($temp as $key => $service) {
    $html .= "<img src='/src/img/" . $serviceIMG[$service] . "' class='title rounded' alt=''...''>";
  }
  $html .= "</div></div>";
  return $html;
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

?>
