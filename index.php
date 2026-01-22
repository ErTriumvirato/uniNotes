<?php
require_once 'config.php';

// Gestione della chiusura del banner dei cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'closeCookieBanner') {
    $_SESSION['cookieBannerClosed'] = true;
    echo json_encode(["status" => "success"]);
    exit;
}

// Gestione richiesta AJAX per aggiornare gli appunti della home
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getHomeNotes') {
    require_once 'functions.php';

    $userId = isUserLoggedIn() ? $_SESSION['idutente'] : null;
    $lastNotes = $dbh->getHomeNotes($userId, 'data_pubblicazione', 6);
    $mostViewedNotes = $dbh->getHomeNotes($userId, 'numero_visualizzazioni', 6);
    $seguendoCorsi = $userId ? $dbh->getFollowedCoursesCount($userId) : false;

    // Formatta i dati per il frontend
    $formatNotes = function ($notes) {
        return array_map(function ($note) {
            $note['data_formattata'] = date('d/m/Y', strtotime($note['data_pubblicazione']));
            $note['media_recensioni'] = $note['media_recensioni'] ?: 'N/A';
            return $note;
        }, $notes);
    };

    header('Content-Type: application/json');
    echo json_encode([
        "success" => true,
        "isLoggedIn" => $userId !== null,
        "seguendoCorsi" => (bool) $seguendoCorsi,
        "recentNotes" => $formatNotes($lastNotes),
        "mostViewedNotes" => $formatNotes($mostViewedNotes)
    ]);
    exit;
}

// templateParams
$templateParams["titolo"] = "Home";
$templateParams["nome"] = "templates/home.php";
array_push($templateParams["script"], "js/home.js");

require_once 'templates/base.php';
