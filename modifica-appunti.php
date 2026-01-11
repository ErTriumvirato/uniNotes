<?php
require_once 'config.php';

requireLogin();

$idappunto = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($idappunto === 0 && isset($_POST['idappunto'])) {
    $idappunto = (int)$_POST['idappunto'];
}

$article = $dbh->getArticleById($idappunto);

if (!$article || $article['idutente'] != $_SESSION["idutente"]) {
    header("Location: creazione-appunti.php");
    exit();
}

if(isset($_POST["salva"]) && isset($_POST["course"]) && isset($_POST["title"]) && isset($_POST["text"])) {
    $dbh->updateArticle($idappunto, $_POST["course"], $_POST["title"], $_POST["text"]);
    header("Location: creazione-appunti.php");
    exit();
}

$templateParams["titolo"] = "uniNotes - Modifica appunti";
$templateParams["nome"] = "templates/modifica-form.php";
$templateParams["article"] = $article;

require_once 'templates/base.php';
