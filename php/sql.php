<?php
/**
 * This file includes basic SQL operations for abstraction of data manipulation within the application
 *
 * @author Team 0306
 *
 * @since 1.0
 */

//establish connection to MySQL Database
$conn = new mysqli($ip, $user, $password, $schema);
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error();
}

/**
 * Steralizes input from user to block SQL injection attacks
 * @param  string $str raw input string from user
 * @return string steralized string
 */
function steralizeString($str) {
  global $conn;
  return mysqli_real_escape_string($conn, $str);
}

/**
 * Translates mysqli results into a PHP array
 * @param  array $result resuls from mysqli
 * @return array array of SQL results
 */
function sqlToArray($result) {
  $arr = [];
  while ($row = $result->fetch_assoc()) {
      array_push($arr, $row);
  }
  return $arr;
}

/**
 * Function to pass in a query string and perform it on the database
 * @param  string  $query  SQL formatted query string
 * @param  boolean $return boolean to determine a return based query or just an operation on the db
 * @return array   PHP array results from the db
 */
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

/**
 * insert new row into table, takes in an array of columns and an array of values to insert into the corresponding table
 * @param  array  $columns array of column titles
 * @param  array  $values  array of values to be inserted
 * @param  string $table  table identifier to perform operation on
 */
function insert($columns, $values, $table) {
  $query = "INSERT INTO " . $table . " (" . getString($columns) . ") VALUES (" . getString($values) . ");";
  query($query, false);
}

/**
 * Updates a row in a specified table with new values
 * @param  string $identifier id value of row to be updated
 * @param  string $table      table to perform operation
 * @param  array  $params     array of column titles to update
 * @param  array  $values     array of new values
 */
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
  // removes last comma
  $updates = rtrim($updates, ",");
  $query = "UPDATE " . $table . " SET " . $updates . " WHERE id = " . "'" . $identifier . "';";
  query($query, false);
}

/**
 * Deletes row from table
 * @param  string $id    id value of row to be deleted
 * @param  string $table table to perform operation
 */
function delete($id, $table) {
  query("DELETE FROM " . $table . " WHERE id = " . $id . ";");
}
