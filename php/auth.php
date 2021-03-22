<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aws\Ssm\SsmClient;

include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';
require $_SERVER['DOCUMENT_ROOT'] . '/php/PHPMailer/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/php/PHPMailer/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/php/PHPMailer/PHPMailer/src/SMTP.php';

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
			<h1>Verify your email by clicking the following link.</h1>
			<p>If this was a mistake, you may want to reset your password.</p>
			<a href='http://" . $_SERVER['HTTP_HOST'] . "/actsetup?s=" . $secureString . "'>Verify Email</a>";
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

function getCurrentUserID() {
	return $_SESSION['userID'];
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
		sendMail($email, 'verify');
		header("Location:/verify");
		exit();
		//return login($email, $pass);
	} else {
		return false;
	}
}

function getUserInfo($id) {
	$query = "SELECT name, email, services, favorites FROM users WHERE id = " . str($id) . ";";
	//echo $query;
	return query($query, true)[0];
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

function getFavorites() {
	return json_decode(getUserInfo(getCurrentUserID())['favorites']);
}

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
