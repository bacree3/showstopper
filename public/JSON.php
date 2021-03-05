<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/functions.php';
header( "Content-type: application/json" );
echo json_encode(getTitlesForSearch());
?>
