<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['valutazione'])) {
    if (isUserLoggedIn()) {
        $idarticolo = $_GET['id'];
        $idutente = $_SESSION['idutente'];
        $valutazione = intval($_POST['valutazione']);
        
        if ($valutazione >= 1 && $valutazione <= 5 && !$dbh->hasUserReviewed($idarticolo, $idutente)) {
            $dbh->addReview($idarticolo, $idutente, $valutazione);
            header("Location: articolo.php?id=" . $idarticolo);
            exit();
        }
    }
}

// templateParams
$templateParams["titolo"] = "uniNotes - nome aricolo";
$templateParams["nome"] = "templates/dettagli-articolo.php";

require_once 'templates/base.php';
