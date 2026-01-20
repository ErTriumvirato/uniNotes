<?php
require_once 'config.php';

if (isset($_GET['action']) && $_GET['action'] == 'filter') {
    header('Content-Type: application/json');
    $search = $_GET['search'] ?? null;
    $ssd = $_GET['ssd'] ?? null;
    $filterType = $_GET['filterType'] ?? 'all';
    $idutente = $_SESSION["idutente"] ?? null;

    $courses = $dbh->getCoursesWithFilters($idutente, $ssd, $filterType, $search);

    $result = [];
    foreach ($courses as $course) {
        $course['isFollowing'] = $idutente ? $dbh->isFollowingCourse($idutente, $course['idcorso']) : false;
        $result[] = $course;
    }

    echo json_encode($result);
    exit;
}

if (isset($_POST["toggleFollow"])) {
    header('Content-Type: application/json');

    if (!isUserLoggedIn()) {
        echo json_encode(['error' => 'login_required']);
        exit;
    }

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
$templateParams["titolo"] = "Corsi";
$templateParams["nome"] = "templates/lista-corsi.php";
$templateParams["ssds"] = $dbh->getAllSSD();
array_push($templateParams["script"], "js/lista-corsi.js");

require_once 'templates/base.php';
