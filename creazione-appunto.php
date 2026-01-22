<?php
require_once 'config.php';

requireLogin(); // È necessaria l'autenticazione

if (isset($_POST["invia"]) && isset($_POST["course"]) && isset($_POST["title"]) && isset($_POST["text"])) {
    $dbh->createNote($_POST["course"], $_POST["title"], $_POST["text"], $_SESSION["idutente"]); // Crea l'appunto
}

$templateParams["titolo"] = "Creazione appunti";
$templateParams["nome"] = "templates/creazione-appunto-template.php";
$allNotes = $dbh->getNotesWithFilters(idutente: $_SESSION["idutente"], approvalFilter: 'all');

// Filtra gli appunti non approvati
$templateParams["unapprovedNotes"] = array_filter($allNotes, function ($note) {
    return $note['stato'] !== 'approvato';
});

require_once 'templates/base.php';
