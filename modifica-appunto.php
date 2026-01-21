<?php
require_once 'config.php';

requireLogin();

$noteId = isset($_GET['id']) ? $_GET['id'] : 0; // Ottiene l'ID dell'appunto da modificare
if ($noteId === 0 && isset($_POST['idappunto'])) {
    $noteId = $_POST['idappunto'];
}

$note = $dbh->getNoteById($noteId); // Ottiene l'appunto dal database

if (!$note || $note['idutente'] != $_SESSION["idutente"]) { // Verifica se l'appunto esiste e appartiene all'utente loggato
    header("Location: creazione-appunto.php");
    exit();
}

if (isset($_POST["salva"]) && isset($_POST["course"]) && isset($_POST["title"]) && isset($_POST["text"])) { // Gestisce il salvataggio delle modifiche
    $dbh->updateNote($noteId, $_POST["course"], $_POST["title"], $_POST["text"]);
    header("Location: creazione-appunto.php");
    exit();
}

$templateParams["titolo"] = "Modifica appunti";
$templateParams["nome"] = "templates/modifica-appunto-template.php";
$templateParams["appunto"] = $note;

require_once 'templates/base.php';
