<?php
require_once 'config.php';

if(!isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

if(isset($_POST["invia"]) && isset($_POST["course"]) &&isset($_POST["title"]) && isset($_POST["text"])) {
    $dbh->createArticle($_POST["course"], $_POST["title"], $_POST["text"], $_SESSION["idutente"]);
}

$templateParams["titolo"] = "uniNotes - Creazione appunti";
$templateParams["nome"] = "templates/upload_form.php";

require_once 'templates/base.php';
