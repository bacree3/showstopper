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

// add title to cache db
function addTitle($title) {
  global $titleColumns;
  $table = "titles";
  $values = [
    "'" . $title["imdbID"] . "'",
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
  //echo $query;
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
function search($titleString) {
  global $omdbURL;
  $api_url = $omdbURL . "t=" . toSearchString($titleString);
  addTitle(json_decode(file_get_contents($api_url), true));
}


// using omdb get details from api
function getTitleInfo($titleString) {

}

function checkCache($title, $table) {
  return getElementByID($title['id'], $table);
}


?>
