<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

function isLoggedIn($userID) {

}

function logIn($email, $pass) {
	if (emailExists($email)) {
		$result = query("SELECT id, pass FROM users WHERE email = " . str($email), true)[0];
		//print_r($result);
		if (password_verify($pass, $result['pass'])) {
			echo $result['id'];
		} else {
			echo "email or password incorrect";
		}
	} else {
		echo "email does not exist";
	}
}

function emailExists($email) {
	$query = "SELECT * FROM users WHERE email = " . str($email) . ";";
	//echo $query;
	return query($query, true);
}

function createUser($email, $pass) {
	$email = strtolower($email);
	if (!emailExists($email)) {
		$hashed = password_hash($pass, PASSWORD_DEFAULT);
		$id = uniqid();
		insert(['id', 'email', 'pass'], [str($id), str($email), str($hashed)], "users");
		login($email, $pass);
	} else {
		echo "email exists";
	}
}

function getUserID() {

}

function accountSetup($id, $name, $services) {
	update($id, "users", ['name', 'services'], [str($name), json($services)]);
}

//createUser("bryceacree2@gmail.com", "password");

//accountSetup("6033f04273227", "bryce", $json);

//echo emailExists("bryceacree@gmail.com");
//
login("bryceacree@gmail.com", "password");

?>
