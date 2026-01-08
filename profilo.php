<?php
require_once 'config.php';

$idProfile = null;

if (isset($_GET['id'])) {
    $idProfile = intval($_GET['id']);
} elseif (isUserLoggedIn()) {
    $idProfile = $_SESSION['idutente'];
} else {
    header("Location: login.php");
    exit();
}

$userProfile = $dbh->getUserById($idProfile);

if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    $sort = $_GET['sort'] ?? 'data_pubblicazione';
    $order = $_GET['order'] ?? 'DESC';

    $appunti = $dbh->getApprovedArticlesByUserIdWithFilters($idProfile, $sort, $order);
    
    $response = array_map(function ($art) {
        $art['views'] = (int)$art['numero_visualizzazioni'];
        $art['data_formattata'] = date('d/m/y', strtotime($art['data_pubblicazione']));
        $art['media_recensioni'] = $art['media_recensioni'] ?: '0.0';
        return $art;
    }, $appunti);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

if (!$userProfile) {
    header("Location: index.php");
    exit();
}

$isOwner = isUserLoggedIn() && ($_SESSION['idutente'] == $idProfile);
// Gli admin e il proprietario vedono tutto, gli altri solo approvati
$viewAll = $isOwner || isUserAdmin();

$templateParams["userProfile"] = $userProfile;
$templateParams["isOwner"] = $isOwner;

$templateParams["stats"] = [
    "followed_courses" => $dbh->getFollowedCoursesCount($idProfile),
    "articles_written" => $dbh->getArticlesCountByAuthor($idProfile, true),
    "avg_rating" => $dbh->getAuthorAverageRating($idProfile)
];
$templateParams["articles"] = $dbh->getArticlesByAuthor($idProfile, true);

// templateParams
$templateParams["titolo"] = "uniNotes - Profilo di " . $userProfile['username'];
$templateParams["nome"] = "templates/profilo-utente.php";

require_once 'templates/base.php';
