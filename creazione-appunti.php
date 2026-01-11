<?php
require_once 'config.php';

requireLogin();

if(isset($_POST["invia"]) && isset($_POST["course"]) &&isset($_POST["title"]) && isset($_POST["text"])) {
    $dbh->createArticle($_POST["course"], $_POST["title"], $_POST["text"], $_SESSION["idutente"]);
}

$templateParams["titolo"] = "uniNotes - Creazione appunti";
$templateParams["nome"] = "templates/upload-form.php";
$templateParams["unapprovedArticles"] = $dbh->getUnapprovedArticlesByAuthor($_SESSION["idutente"]);

require_once 'templates/base.php';
