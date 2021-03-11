<?php
include 'parameters.php';

//establish connection to MySQL Database
$conn = new mysqli($ip, $user, $password, $schema);
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error();
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

function delete($id, $table) {

}
