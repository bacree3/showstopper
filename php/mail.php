<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aws\Ssm\SsmClient;

//include $_SERVER['DOCUMENT_ROOT'] . '/php/parameters.php';
include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';
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
		allowPasswordReset($result['id']);
	}

	if ($type == "password") {
		$subject = 'Password Reset Confirmation';
		$bodyHtml = "
			<h1>Reset Your Password By Clicking the following link.</h1>
			<p>If this was a mistake, you may want to reset your password.</p>
			<a href='http://" . $_SERVER['HTTP_HOST'] . "/reset-password?s=" . $secureString . "'>Reset Password</a>";
	} else if ($type == "verify") {
		$subject = 'ShowStopper Email Verification';
		$bodyHtml = '<h1>test</h1>';
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
	    echo "Email sent!" , PHP_EOL;
	} catch (phpmailerException $e) {
	    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
	    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}
}
?>
