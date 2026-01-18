<?php
require_once 'config.php';

$idCorso = $_GET['id'] ?? null;
if (!$idCorso || !is_numeric($idCorso)) {
    header("Location: index.php");
    exit();
}

$idutente = $_SESSION["idutente"] ?? null;

if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    $sort = $_GET['sort'] ?? 'data_pubblicazione';
    $order = $_GET['order'] ?? 'DESC';

    $appunti = $dbh->getApprovedArticlesByCourseWithFilters($idCorso, $sort, $order);

    $response = array_map(function ($art) {
        $art['views'] = (int)$art['numero_visualizzazioni'];
        $art['data_formattata'] = date('d/m/y', strtotime($art['data_pubblicazione']));
        $art['media_recensioni'] = $art['media_recensioni'] ?: 'N/A';
        return $art;
    }, $appunti);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
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

$corso = $dbh->getCourseById($idCorso);
if (!$corso) {
    header("Location: index.php");
    exit();
}

// Template parameters
$templateParams["corso"] = $corso;
$templateParams["isFollowing"] = $idutente ? $dbh->isFollowingCourse($idutente, $idCorso) : false;
$templateParams["titolo"] = htmlspecialchars($corso['nome']);
$templateParams["nome"] = "templates/dettagli-corso.php";

require_once 'templates/base.php';
