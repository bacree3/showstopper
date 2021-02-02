<?php

include 'parameters.php';

//establish connection to MySQL Database

$conn = mysqli_connect($ip, $user, $password, $schema);
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// do mysql query
function query($query) {

}

// update line with
function update($row, $table, $params, $values) {

}

// insert new row into table
function insert($row, $table) {

}

// steralize string for mysql command
function steralizeString($str) {
  global $conn;
  return mysqli_real_escape_string($conn, $str);
}

// add title to cache db
function addTitle($title) {

}

// add director cache to db
function addDirector($director) {

}

// add actor cache to db
function addActor($actor) {

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


?>
