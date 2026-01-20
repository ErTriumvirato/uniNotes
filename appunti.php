<?php
require_once 'config.php';

// Handle POST actions (approve, reject, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['idappunto'])) {
    requireLogin();

    if (!isUserAdmin()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
        exit;
    }

    $action = $_POST['action'];
    $id = intval($_POST['idappunto']);
    $success = false;

    if ($action === 'approve') {
        $success = $dbh->approveArticle($id);
    } elseif ($action === 'reject') {
        $success = $dbh->rejectArticle($id);
    } elseif ($action === 'delete') {
        $success = $dbh->deleteArticle($id);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    $sort = $_GET['sort'] ?? 'data_pubblicazione';
    $order = $_GET['order'] ?? 'DESC';
    $nomeutente = isset($_GET['nomeutente']) ? $_GET['nomeutente'] : null;
    $nomecorso = isset($_GET['nomecorso']) ? $_GET['nomecorso'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $approvalFilter = isset($_GET['approval']) ? $_GET['approval'] : 'approved';

    $appunti = $dbh->getArticlesWithFilters($nomeutente, $nomecorso, $sort, $order, $search, $approvalFilter);

    $response = array_map(function ($res) {
        $res['numero_visualizzazioni'] = (int)$res['numero_visualizzazioni'];
        $res['data_formattata'] = date('d/m/y', strtotime($res['data_pubblicazione']));
        $res['media_recensioni'] = $res['media_recensioni'] ?: 'N/A';
        $res['numero_recensioni'] = (int)$res['numero_recensioni'];
        return $res;
    }, $appunti);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

header('Content-Type: application/json');
echo json_encode(['error' => 'Invalid action']);