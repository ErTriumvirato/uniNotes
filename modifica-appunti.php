<?php
require_once 'config.php';

requireLogin();

$idappunto = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Ottiene l'ID dell'appunto da modificare
if ($idappunto === 0 && isset($_POST['idappunto'])) {
    $idappunto = (int)$_POST['idappunto'];
}

$appunto = $dbh->getArticleById($idappunto); // Ottiene l'appunto dal database

if (!$appunto || $appunto['idutente'] != $_SESSION["idutente"]) { // Verifica se l'appunto esiste e appartiene all'utente loggato
    header("Location: creazione-appunti.php");
    exit();
}

if (isset($_POST["salva"]) && isset($_POST["course"]) && isset($_POST["title"]) && isset($_POST["text"])) { // Gestisce il salvataggio delle modifiche
    $dbh->updateArticle($idappunto, $_POST["course"], $_POST["title"], $_POST["text"]);
    header("Location: creazione-appunti.php");
    exit();
}

$templateParams["titolo"] = "Modifica appunti";
$templateParams["nome"] = "templates/modifica-form.php";
$templateParams["appunto"] = $appunto;

require_once 'templates/base.php';
