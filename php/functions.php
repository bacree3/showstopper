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

  addDirectors();
  addActors();
}

// add director cache to db
function addDirectors($directors) {

}

// add actor cache to db
function addActors($actors) {

}

// get details from omdb by id rather then search
function getTitleByID($id) {

}

// get titles from basic search to display on search results
function search($titleString) {

}


// using omdb get details from api
function getTitleInfo($titleString) {

}

function checkCache() {

}


?>
