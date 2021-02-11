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
  echo $query;
  query($query, false);
}

// update line with
function update($identifier, $table, $params, $values) {

}

// steralize string for mysql command
function steralizeString($str) {
  global $conn;
  return mysqli_real_escape_string($conn, $str);
}

function updateTitle($id) {
  $updatedData = getTitleInfo($id);
  if (checkCache($id, 'titles')) {
    //update($id, 'titles', )
  }
}

// add title to cache db
$titleColumns = ['id', 'titles.name', 'release', 'summary', 'rating', 'img', 'genre'];
function addTitle($title) {
  global $titleColumns;
  $table = "titles";
  $values = [
    "'" . $title["imdbID"] . "'",
    "'" . $title["Year"] . "'",
    "'" . $title["Plot"] . "'",
    "'" . $title["Ratings"]['imdbRating'] . "'",
    "'" . $title["Title"] . "'",
    "'" . $title["Poster"] . "'",
    "'" . $title["Genre"] . "'",
  ];
  print_r($values);
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
  addTitle(json_decode(file_get_contents($api_url), true));
}

// using omdb get details from api
function getTitleInfo($id) {
  global $omdbURL;
  $api_url = $omdbURL . "i=" . toSearchString($id);
  addTitle(json_decode(file_get_contents($api_url), true));
}

function checkCache($title, $table) {
  return getElementByID($title['id'], $table);
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
