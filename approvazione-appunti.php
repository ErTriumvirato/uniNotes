<?php
require_once 'config.php';

if(!isUserAdmin()){
    header("Location: index.php");
    exit();
}

if (isset($_POST['action'], $_POST['idappunto'])) {
    $action = $_POST['action'];
    $id = intval($_POST['idappunto']);
    $success = false;

    if ($action === 'approve') {
        $success = $dbh->approveArticle($id);
    } elseif ($action === 'reject') {
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
        if (empty($reason)) {
            $reason = "Nessuna motivazione specificata.";
        }
        $success = $dbh->rejectArticle($id, $reason);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit;
}

$templateParams["titolo"] = "uniNotes - Approvazione appunti";
$templateParams["nome"] = "templates/appunti-da-approvare.php";
require_once 'templates/base.php';