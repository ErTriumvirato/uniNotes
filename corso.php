<?php
require_once 'config.php';

$idCorso = $_GET['id'] ?? null;
if (!$idCorso || !is_numeric($idCorso)) {
    header("Location: index.php");
    exit();
}

$idutente = $_SESSION["idutente"] ?? null;

if (isset($_POST["toggleFollow"])) {
    header('Content-Type: application/json');

    if (!isUserLoggedIn()) {
        echo json_encode(['error' => 'login_required']);
        exit;
    }

    $idcorso = $_POST["toggleFollow"];
    $idutente = $_SESSION["idutente"];

    $isFollowing = $dbh->isFollowingCourse($idutente, $idcorso);

    if ($isFollowing) { // Se l'utente sta già seguendo il corso, smette di seguirlo
        $dbh->unfollowCourse($idutente, $idcorso);
        echo json_encode(['following' => false]);
    } else { // Altrimenti inizia a seguirlo
        $dbh->followCourse($idutente, $idcorso);
        echo json_encode(['following' => true]);
    }
    exit;
}

$corso = $dbh->getCourseById($idCorso); // Ottieni i dettagli del corso dal database
if (!$corso) {
    header("Location: index.php"); // Se il corso non esiste, reindirizza alla home
    exit();
}

// Template parameters
$templateParams["corso"] = $corso;
$templateParams["isFollowing"] = $idutente ? $dbh->isFollowingCourse($idutente, $idCorso) : false;
$templateParams["titolo"] = htmlspecialchars($corso['nome']);
$templateParams["nome"] = "templates/dettagli-corso-template.php";
array_push($templateParams["script"], "js/dettagli-corso.js", "js/appunti.js");

require_once 'templates/base.php';
