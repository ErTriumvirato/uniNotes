<?php
require_once 'config.php';

if (isUserLoggedIn() && isset($_POST["toggleFollow"])) {
    header('Content-Type: application/json');
    
    $idcorso = $_POST["toggleFollow"];
    $idutente = $_SESSION["idutente"];
    
    // Verifica se già seguito
    $isFollowing = $dbh->isFollowingCourse($idutente, $idcorso);
    
    if ($isFollowing) {
        // Se già seguito, rimuovi
        $dbh->unfollowCourse($idutente, $idcorso);
        echo json_encode(['following' => false]);
    } else {
        // Se non seguito, aggiungi
        $dbh->followCourse($idutente, $idcorso);
        echo json_encode(['following' => true]);
    }
    exit;
}

/*if(isUserLoggedIn()) {
    if (isset($_POST["followCourse"])) {
        $idcorso = $_POST["followCourse"];
        $idutente = $_SESSION["idutente"];
        $dbh->followCourse($idutente, $idcorso);
    }
    if (isset($_POST["unfollowCourse"])) {
        $idcorso = $_POST["unfollowCourse"];
        $idutente = $_SESSION["idutente"];
        $dbh->unfollowCourse($idutente, $idcorso);
    }
}*/

// templateParams
$templateParams["titolo"] = "uniNotes - Corsi";
$templateParams["nome"] = "templates/lista-corsi.php";

require_once 'templates/base.php';
