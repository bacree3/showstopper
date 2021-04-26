<?php

include $_SERVER['DOCUMENT_ROOT'] . '/php/auth.php';

deleteAccount(getCurrentUserID());

logout();

header("Location:/");

?>
