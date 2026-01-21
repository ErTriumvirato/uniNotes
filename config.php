<?php
if (session_status() === PHP_SESSION_NONE) { // Avvia la sessione se non è già avviata
    session_start();
}

error_reporting(0); // Per non mostrare gli eventuali errori di PHP all'utente finale

define("UPLOAD_DIR", "./upload/"); // Cartella di upload dei file
$templateParams["script"] = array("js/base.js"); // Script JS presente in tutte le pagine

require_once("functions.php");
require_once("db/database.php");

$dbh = new DatabaseHelper("localhost", "root", "", "uniNotes", 3306); // Connessione al database
