<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    header("Location: index.php");
    exit();
}

$appunto = $dbh->getArticleById($_GET['id']);

if (!$appunto) {
    // Appunto non trovato, lasciamo gestire al template o redirect
} else {
    $isApproved = $appunto['approvato'];
    $isAuthor = isUserLoggedIn() && ($_SESSION['idutente'] == $appunto['idutente']);
    $isAdmin = isUserAdmin();

    if (!$isApproved && !$isAuthor && !$isAdmin) {
        // Accesso negato
        header("Location: index.php");
        exit();
    }
}

$templateParams["appunto"] = $appunto;

// Gestione eliminazione recensione
if (isset($_POST['deleteReview'])) {
    if (isUserLoggedIn()) {
        $idrecensione = intval($_POST['deleteReview']);
        $idutente = $_SESSION['idutente'];
        $idappunto = $_GET['id'];

        $deleted = $dbh->deleteReview($idrecensione, $idutente);

        // Se è una richiesta AJAX, restituisci JSON
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');

            if ($deleted) {
                // Recupera i dati aggiornati
                $updatedArticle = $dbh->getArticleById($idappunto);

                echo json_encode([
                    'success' => true,
                    'new_avg' => $updatedArticle['media_recensioni'] ?: 'N/A'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Impossibile eliminare la recensione.'
                ]);
            }
            exit();
        }

        header("Location: appunto.php?id=" . $idappunto);
        exit();
    }
}

// Gestione aggiunta recensione
if (isset($_POST['valutazione'])) {
    if (isUserLoggedIn()) {

        $idappunto = $_GET['id'];
        $idutente = $_SESSION['idutente'];
        $valutazione = intval($_POST['valutazione']);

        if ($valutazione >= 1 && $valutazione <= 5 && !$dbh->hasUserReviewed($idappunto, $idutente)) {
            $dbh->addReview($idappunto, $idutente, $valutazione);

            // Se è una richiesta AJAX, restituisci JSON
            if (isset($_POST['ajax'])) {
                header('Content-Type: application/json');

                // Recupera i dati aggiornati
                $updatedArticle = $dbh->getArticleById($idappunto);
                $username = $_SESSION['username'];

                // Recupera l'ID della recensione appena creata
                $reviews = $dbh->getReviewsByArticle($idappunto);
                $newReview = null;
                foreach ($reviews as $review) {
                    if ($review['idutente'] == $idutente) {
                        $newReview = $review;
                        break;
                    }
                }

                echo json_encode([
                    'success' => true,
                    'review' => [
                        'idrecensione' => $newReview['idrecensione'],
                        'username' => $username,
                        'valutazione' => $valutazione
                    ],
                    'new_avg' => $updatedArticle['media_recensioni'] ?: 'N/A'
                ]);
                exit();
            }

            header("Location: appunto.php?id=" . $idappunto);
            exit();
        }
    }
}

// templateParams
$templateParams["titolo"] = "uniNotes - nome aricolo";
$templateParams["nome"] = "templates/dettagli-appunto.php";

require_once 'templates/base.php';
