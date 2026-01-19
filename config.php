<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define("UPLOAD_DIR", "./upload/"); // Cartella di upload dei file
$templateParams["script"] = array("js/base.js"); // Script JS presente in tutte le pagine

require_once("functions.php");
require_once("db/database.php");

$dbh = new DatabaseHelper("localhost", "root", "", "uniNotes", 3306);
