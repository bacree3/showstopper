<?php

include 'parameters.php';

//establish connection to MySQL Database
$conn = new mysqli($ip, $user, $password, $schema);
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error();
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
  if ($return) {
    return sqlToArray($result);
  }
}

// insert new row into table
// takes in an array of columns and an array of values to insert into the corresponding table
function insert($columns, $values, $table) {
  $query = "INSERT INTO " . $table . " (" . getString($columns) . ") VALUES (" . getString($values) . ");";
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
  echo $query;
  query($query, false);
}

// steralize string for mysql command
function steralizeString($str) {
  global $conn;
  return mysqli_real_escape_string($conn, $str);
}

function updateTitle($id) {
  global $titleColumns;
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
}

// add title to cache db
$titleColumns = ['id', 'titles.name', 'titles.release', 'summary', 'rating', 'img', 'genre'];
function addTitle($title) {
  global $titleColumns;
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
  insert($titleColumns, $values, $table);
  //addDirectors();
  //addActors();
}

// add director cache to db
function addDirectors($directors) {

}

// add actor cache to db
function addActors($actors) {

}

// get details from omdb by id rather then search
function getElementByID($id, $table) {
  $query = "SELECT * FROM " . $table . " WHERE id = '" . $id . "';";
  $result = query($query, true);
  if ($result === false) {
    return false;
  }
  return $result[0];
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
    echo "check";
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
  return getElementByID($id, $table);
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

?>
