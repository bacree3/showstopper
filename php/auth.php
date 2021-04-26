<?php
/**
 * This file is used as a library for user authentication and any data relative to the user
 *
 * @author Team 0306
 *
 * @since 1.0
 */

// start PHP session for user info
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aws\Ssm\SsmClient;

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';
require $_SERVER['DOCUMENT_ROOT'] . '/php/PHPMailer/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/php/PHPMailer/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/php/PHPMailer/PHPMailer/src/SMTP.php';

/**
 * Send an email for user authentication
 * @param  string $address email address of recipient
 * @param  string $type    type of email to send
 */
function sendMail($address, $type) {
	global $SMTPuser, $SMTPsecret;

	if (!emailExists($address)) {
		return;
	} else {
		$result = query("SELECT id, pass FROM users WHERE email = " . str($address), true)[0];
		$secureString = $result['pass'];
	}

	if ($type == "password") {
		$subject = 'Password Reset Confirmation';
		$bodyHtml = "
			<h1>Reset your password By clicking the following link.</h1>
			<p>If this was a mistake, you may want to reset your password.</p>
			<a href='http://" . $_SERVER['HTTP_HOST'] . "/reset-password?s=" . $secureString . "'>Reset Password</a>";
		allowPasswordReset($result['id']);
	} else if ($type == "verify") {
		$subject = 'ShowStopper Email Verification';
		$bodyHtml = "
			<h1>Verify your email by clicking the following link and continue your registration.</h1>
			<p>If this was a mistake, you may want to reset your password.</p>
			<a href='http://" . $_SERVER['HTTP_HOST'] . "/actsetup?s=" . $secureString . "'>Verify Email</a>";
	} else if ($type == "notification") {
		$subject = 'ShowStopper Content Update Notification';
		$bodyHtml = "
			<h1>One of your favorites just updated, go check it out now!</h1>
			<a href='http://" . $_SERVER['HTTP_HOST'] . "/notificationCenter'>View Notifications</a>";
	}

	// Replace sender@example.com with your "From" address.
	// This address must be verified with Amazon SES.
	$sender = 'help@showstopper.app';
	$senderName = 'ShowStopper';

	// Replace recipient@example.com with a "To" address. If your account
	// is still in the sandbox, this address must be verified.
	$recipient = $address;

	// Replace smtp_username with your Amazon SES SMTP user name.
	$usernameSmtp = $SMTPuser;

	// Replace smtp_password with your Amazon SES SMTP password.
	$passwordSmtp = $SMTPsecret;

	// Specify a configuration set. If you do not want to use a configuration
	// set, comment or remove the next line.
	//$configurationSet = 'ConfigSet';

	// If you're using Amazon SES in a region other than US West (Oregon),
	// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
	// endpoint in the appropriate region.
	$host = 'email-smtp.us-east-1.amazonaws.com';
	$port = 587;

	$mail = new PHPMailer(true);

	try {
	    // Specify the SMTP settings.
	    $mail->isSMTP();
	    $mail->setFrom($sender, $senderName);
	    $mail->Username   = $usernameSmtp;
	    $mail->Password   = $passwordSmtp;
	    $mail->Host       = $host;
	    $mail->Port       = $port;
	    $mail->SMTPAuth   = true;
	    $mail->SMTPSecure = 'tls';
	    //$mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

	    // Specify the message recipients.
	    $mail->addAddress($recipient);
	    // You can also add CC, BCC, and additional To recipients here.

	    // Specify the content of the message.
	    $mail->isHTML(true);
	    $mail->Subject    = $subject;
	    $mail->Body       = $bodyHtml;
	    //$mail->AltBody    = $bodyText;
	    $mail->Send();
	    //echo "Email sent!" , PHP_EOL;
	} catch (phpmailerException $e) {
	    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
	    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}
}

/**
 * Check to see if a user is logged in
 * @return boolean return true if logged in
 */
function isLoggedIn() {
	return isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'];
}

/**
 * Logout the user
 */
function logout() {
	if (isLoggedIn()) {
		session_destroy();
		$_SESSION = [];
		echo "successful logout";
	} else {
		echo "user not logged in";
	}
}

/**
 * Login the user
 * @param  string  $email email of user
 * @param  string  $pass  password input by user to check against hash
 * @return boolean return true if successful login
 */
function logIn($email, $pass) {
	$email = strtolower($email);
	if (emailExists($email)) {
		$result = query("SELECT id, pass, verified FROM users WHERE email = " . str($email), true)[0];
		//print_r($result);
		if ($result['verified'] == 0) {
			header("Location:/verify?email=" . $email);
			exit();
		} else if (password_verify($pass, $result['pass'])) {
			//session_destroy();
			//session_start();
			$_SESSION['isLoggedIn'] = true;
			$_SESSION['userID'] = $result['id'];
			return true;
		}
	}
	return false;
}

/**
 * Get the current user ID if set
 * @return string user ID
 */
function getCurrentUserID() {
	return $_SESSION['userID'];
}

/**
 * Check to see if an email exists in the user table
 * @param  string $email email put in during registration
 * @return array  user with registered email if exists
 */
function emailExists($email) {
	$query = "SELECT * FROM users WHERE email = " . str($email) . ";";
	return query($query, true);
}

/**
 * Create a new uers
 * @param  string  $email email to be associated with user
 * @param  string  $pass  password to be hashed
 * @return boolean return true if successful creation
 */
function createUser($email, $pass) {
	$email = strtolower($email);
	if (!emailExists($email)) {
		$hashed = password_hash($pass, PASSWORD_DEFAULT);
		$id = uniqid();
		insert(['id', 'email', 'pass', 'favorites', 'services'], [str($id), str($email), str($hashed), str('[]'), str('[]')], "users");
		sendMail($email, 'verify');
		header("Location:/verify");
		exit();
		//return login($email, $pass);
	} else {
		return false;
	}
}

/**
 * Get non-sensitive info for a user
 * @param  string $id userID to get data for
 * @return array array of user data
 */
function getUserInfo($id) {
	$query = "SELECT name, email, services, favorites, delivery, actorNotification, titleNotification FROM users WHERE id = " . str($id) . ";";
	return query($query, true)[0];
}

 /**
  * Complete account setup based on form data during registration
  * @param  string $id       user ID
  * @param  string $name     user's new name
  * @param  array  $services array of services user is subscribed to
  * @return array  user data if successful
  */
function accountSetup($id, $name, $services) {
	return update($id, "users", ['name', 'services'], [str($name), json($services)]);
}

/**
 * Update a user's account data from the form data from the account page
 * @param  string $id       user ID
 * @param  string $name     user's new name
 * @param  string $email    user's new email
 * @param  array  $services array of services user is subscribed to
 * @return array  user data if successful
 */
function updateAccount($id, $name, $email, $services) {
	return update($id, "users", ['name', 'email', 'services'], [str($name), str($email), json($services)]);
}

/**
 * Update a user's password
 * @param  string $id user ID
 * @param  string $pass new hashed password
 * @return array  user data if successful
 */
function updatePassword($id, $pass) {
	return update($id, "users", ['pass'], [str($pass)]);
}

/**
 * Allow a user to reset their password based on request from form data
 * @param string $id user ID
 */
function allowPasswordReset($id) {
	$query = "UPDATE users SET reset = 1 WHERE id = " . str($id) . ";";
	echo $query;
	query($query, false);
}

/**
 * Disable password reset ability for a user after one-time link has been used
 * @param string $id user ID
 */
function disallowPasswordReset($id) {
	$query = "UPDATE users SET reset = 0 WHERE id = " . str($id) . ";";
	query($query, false);
}

/**
 * Check to see if a user can reset their password
 * @param string $id user ID
 * @return boolean return true if a user can reset their passwrod
 */
function passwordResetAllowed($id) {
	$query = "SELECT reset FROM users WHERE id = " . str($id) . ";";
	$reset = query($query, true)[0]['reset'];
	if ($reset == 1) {
		return true;
	} else {
		return false;
	}
}

/**
 * Remove duplicate objects from an array
 * @param  array $data input array
 * @return array output array with no duplicates
 */
function removeDuplicates($data) {
	$array = json_decode( $data, TRUE );

	// Only keep unique values, by using array_unique with SORT_REGULAR as flag.
	// We're using array_values here, to only retrieve the values and not the keys.
	// This way json_encode will give us a nicely formatted JSON string later on.
	$array = array_values( array_unique( $array, SORT_REGULAR ) );

	// Make a JSON string from the array.
	$result = json_encode( $array );
}

/**
 * Check to see if an element was favorited by a user
 * @param  string  $favoriteID id of element to check
 * @return boolean return true if the element is currently favorited
 */
function isFavorited($favoriteID) {
	if (!isLoggedIn()) {
		return 0;
	}
	$user = getCurrentUserID();
	$favorites = json_decode(getUserInfo($user)['favorites']);
	//print_r($favorites);
	if (in_array($favoriteID, $favorites)) {
		return 1;
	} else {
		return 0;
	}
}

/**
 * Get a users favorites if logged in
 * @return array array of favorited titles and people's ids
 */
function getFavorites() {
	return json_decode(getUserInfo(getCurrentUserID())['favorites']);
}

/**
 * Get an array of timestamps of a user's history corresponding to the content they visited
 * @return array array of user history
 */
function getHistory() {
  $user = getCurrentUserID();
  $history = query("SELECT title, person, updated FROM history WHERE user_id = " . str($user) . " ORDER BY updated desc;", true);
  return $history;
}

function clearHistory() {
	$user = getCurrentUserID();
	$result = query("DELETE FROM history WHERE user_id = " . str($user) . ";", false);
	header("Location:/history");
}

/**
 * Verify an email if the link was followed
 * @param  string $email email to verify
 */
function verifyEmail($email) {
  query("UPDATE users SET verified = 1 WHERE email = " . str($email) . ";", false);
}

/**
 * Delete a user's account data
 */
function deleteAccount() {
  $userID = getCurrentUserID();
	$query = "DELETE FROM users WHERE id = " . str($userID) . ";";
	//echo $query;
	query($query, false);
}

function sendEmailNotification($user) {
	sendEmail(getElementByID($user, 'users')['email'], 'notification');
}

function sendPushNotification($user) {
	?>
	<script>
		//if (checkClientPermissions()) {
			//sendPushNotification();
		//}
	</script>
	<?php
}

?>

<script>
	<?php
		// check if user is logged in to set session veriable
		if (isset($_SESSION['isLoggedIn'])) {
			$loggedIn = true;
			$myServices = getElementById(getCurrentUserID(), 'users')['services'];
		} else {
			$loggedIn = false;
			$myServices = "[]";
		}
	?>
  var loggedin = "<?php echo $loggedIn; ?>";
	var myServices = <?php echo $myServices; ?>;
</script>
