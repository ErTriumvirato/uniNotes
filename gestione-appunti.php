<?php
require_once 'config.php';

requireAdmin();

// Handle Delete Action
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['idappunto'])) {
    $idappunto = (int)$_POST['idappunto'];
    $success = $dbh->deleteArticle($idappunto);
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit();
    }
    header("Location: gestione-appunti.php");
    exit();
}

// Handle Filter Action (AJAX)
if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    $search = $_GET['search'] ?? '';
    $orderBy = $_GET['orderBy'] ?? 'data_pubblicazione';
    $order = $_GET['order'] ?? 'DESC';

    $articles = $dbh->getAdminApprovedArticles($search, $orderBy, $order);

    header('Content-Type: application/json');
    foreach ($articles as &$article) {
        $article['data_formattata'] = date('d/m/Y', strtotime($article['data_pubblicazione']));
    }
    echo json_encode($articles);
    exit();
}

$templateParams["titolo"] = "Gestione Appunti";
$templateParams["nome"] = "templates/appunti-da-gestire.php";
array_push($templateParams["script"], "js/appunti.js");

require_once 'templates/base.php';
