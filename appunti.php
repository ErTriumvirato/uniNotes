<?php
require_once 'config.php';

if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    $sort = $_GET['sort'] ?? 'data_pubblicazione';
    $order = $_GET['order'] ?? 'DESC';
    $nomeutente = isset($_GET['nomeutente']) ? $_GET['nomeutente'] : null;
    $nomecorso = isset($_GET['nomecorso']) ? $_GET['nomecorso'] : null;

    $appunti = $dbh->getApprovedArticlesWithFilters($nomeutente, $nomecorso, $sort, $order);

    $response = array_map(function ($art) {
        $art['numero_visualizzazioni'] = (int)$art['numero_visualizzazioni'];
        $art['data_formattata'] = date('d/m/y', strtotime($art['data_pubblicazione']));
        $art['media_recensioni'] = $art['media_recensioni'] ?: 'N/A';
        return $art;
    }, $appunti);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

header('Content-Type: application/json');
echo json_encode(['error' => 'Invalid action']);
