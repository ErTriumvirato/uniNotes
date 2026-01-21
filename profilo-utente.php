<?php
require_once 'config.php';

$idProfilo = null;

// Determina l'ID del profilo da visualizzare (da GET o sesssione per utente loggato)
if (isset($_GET['id'])) {
    $idProfilo = intval($_GET['id']);
} elseif (isUserLoggedIn()) {
    $idProfilo = $_SESSION['idutente'];
} else {
    header("Location: login.php");
    exit();
}

$profiloUtente = $dbh->getUserById($idProfilo); // Dati dell'utente

$isOwner = isUserLoggedIn() && ($_SESSION['idutente'] == $idProfilo); // Verifica se l'utente loggato sta visualizzando il proprio profilo

$templateParams["profiloUtente"] = $profiloUtente;
$templateParams["isOwner"] = $isOwner;

$templateParams["stats"] = [
    "corsi_seguiti" => $dbh->getFollowedCoursesCount($idProfilo),
    "appunti_scritti" => $dbh->getNotesCountByAuthor($idProfilo, true),
    "media_recensioni" => $dbh->getAuthorAverageRating($idProfilo)
];

// templateParams
$templateParams["titolo"] = "Profilo di " . $profiloUtente['username'];
$templateParams["nome"] = "templates/profilo-utente-template.php";
array_push($templateParams["script"], "js/appunti.js");

require_once 'templates/base.php';
