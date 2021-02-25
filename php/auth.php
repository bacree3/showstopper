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
		echo $loggedIn;
	?>
  var loggedin = "<?php echo $loggedIn; ?>";
  console.log(loggedin);
</script>
