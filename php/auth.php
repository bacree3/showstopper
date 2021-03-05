<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';

session_start();

function isLoggedIn() {
	return isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'];
}

function logout() {
	if (isLoggedIn()) {
		session_destroy();
		$_SESSION = [];
		echo "successful logout";
	} else {
		echo "user not logged in";
	}
}

function logIn($email, $pass) {
	$email = strtolower($email);
	if (emailExists($email)) {
		$result = query("SELECT id, pass FROM users WHERE email = " . str($email), true)[0];
		//print_r($result);
		if (password_verify($pass, $result['pass'])) {
			//session_destroy();
			//session_start();
			$_SESSION['isLoggedIn'] = true;
			$_SESSION['userID'] = $result['id'];
			return true;
		}
	}
	return false;
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
		return login($email, $pass);
	} else {
		return false;
	}
}

function getUserInfo($id) {
	return query("SELECT name, email, services FROM users WHERE id = " . str($id), true);
}

function accountSetup($id, $name, $services) {
	return update($id, "users", ['name', 'services'], [str($name), json($services)]);
}

function updateAccount($id, $name, $email, $services) {
	return update($id, "users", ['name', 'email', 'services'], [str($name), str($email), json($services)]);
}

function updatePassword($id, $pass) {
	return update($id, "users", ['pass'], [str($pass)]);
}

function allowPasswordReset($id) {
	$query = "UPDATE users SET reset = 1 WHERE id = " . str($id) . ";";
	echo $query;
	query($query, false);
}

function disallowPasswordReset($id) {
	$query = "UPDATE users SET reset = 0 WHERE id = " . str($id) . ";";
	query($query, false);
}

function passwordResetAllowed($id) {
	$query = "SELECT reset FROM users WHERE id = " . str($id) . ";";
	$reset = query($query, true)[0]['reset'];
	if ($reset == 1) {
		return true;
	} else {
		return false;
	}
}

function removeDuplicates($data) {
	$array = json_decode( $data, TRUE );

	// Only keep unique values, by using array_unique with SORT_REGULAR as flag.
	// We're using array_values here, to only retrieve the values and not the keys.
	// This way json_encode will give us a nicely formatted JSON string later on.
	$array = array_values( array_unique( $array, SORT_REGULAR ) );

	// Make a JSON string from the array.
	$result = json_encode( $array );
}

//createUser("bryceacree2@gmail.com", "password");

//accountSetup("6033f04273227", "bryce", $json);

//echo emailExists("bryceacree@gmail.com");
//
//login("bryceacree@gmail.com", "password");

?>

<script>
	<?php
	  //$loggedIn;
		if (isset($_SESSION['isLoggedIn'])) {
			$loggedIn = true;
		} else {
			$loggedIn = false;
		}
		//echo $loggedIn;
	?>
  var loggedin = "<?php echo $loggedIn; ?>";
	//console.log(titleObject);
  //console.log(loggedin);
</script>
