<?php
require_once 'config.php';

if(!isUserAdmin()){
    header("Location: index.php");
    exit();
}

if (isset($_POST['action'], $_POST['idarticolo'])) {
    $action = $_POST['action'];
    $id = intval($_POST['idarticolo']);
    $success = false;

    if ($action === 'approve') {
        $success = $dbh->approveArticle($id);
    } elseif ($action === 'reject') {
        $success = $dbh->deleteArticle($id);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit;
}

$templateParams["titolo"] = "uniNotes - Approvazione Appunti";
$templateParams["nome"] = "templates/approvazione.php";
require_once 'templates/base.php';