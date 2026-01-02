<?php
require_once 'config.php';

if (isset($_GET['action']) && $_GET['action'] == 'filter') {
    header('Content-Type: application/json');
    $search = $_GET['search'] ?? null;
    $ssd = $_GET['ssd'] ?? null;
    $filterType = $_GET['filterType'] ?? 'all';
    $idutente = $_SESSION["idutente"] ?? null;
    
    $courses = $dbh->getCoursesWithSSD($search, $ssd, $idutente, $filterType);
    
    $result = [];
    foreach ($courses as $course) {
        $course['isFollowing'] = $idutente ? $dbh->isFollowingCourse($idutente, $course['idcorso']) : false;
        $result[] = $course;
    }
    
    echo json_encode($result);
    exit;
}

if (isUserLoggedIn() && isset($_POST["toggleFollow"])) {
    header('Content-Type: application/json');
    
    $idcorso = $_POST["toggleFollow"];
    $idutente = $_SESSION["idutente"];
    
    $isFollowing = $dbh->isFollowingCourse($idutente, $idcorso);
    
    if ($isFollowing) {
        $dbh->unfollowCourse($idutente, $idcorso);
        echo json_encode(['following' => false]);
    } else {
        $dbh->followCourse($idutente, $idcorso);
        echo json_encode(['following' => true]);
    }
    exit;
}

// templateParams
$templateParams["titolo"] = "uniNotes - Corsi";
$templateParams["nome"] = "templates/lista-corsi.php";
$templateParams["ssds"] = $dbh->getAllSSD();

require_once 'templates/base.php';
