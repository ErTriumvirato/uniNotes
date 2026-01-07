<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['valutazione'])) {
    if (isUserLoggedIn()) {
        $idappunto = $_GET['id'];
        $idutente = $_SESSION['idutente'];
        $valutazione = intval($_POST['valutazione']);
        
        if ($valutazione >= 1 && $valutazione <= 5 && !$dbh->hasUserReviewed($idappunto, $idutente)) {
            $dbh->addReview($idappunto, $idutente, $valutazione);
            header("Location: appunto.php?id=" . $idappunto);
            exit();
        }
    }
}

// templateParams
$templateParams["titolo"] = "uniNotes - nome aricolo";
$templateParams["nome"] = "templates/dettagli-appunto.php";

require_once 'templates/base.php';
