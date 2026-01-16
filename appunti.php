<?php
require_once 'config.php';

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
