<?php
require_once 'config.php';

// Gestione delle richieste POST per eliminare, approvare o rifiutare appunti
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['idappunto'])) {
    requireAdmin(); // L'utente deve essere un admin

    $action = $_POST['action'];
    $id = intval($_POST['idappunto']);
    $success = false;

    if ($action === 'delete') {
        // Per l'eliminazione, controlliamo se l'utente è admin O se è l'autore dell'appunto
        if (isUserAdmin()) {
            $success = $dbh->deleteNote($id);
        } else {
            $note = $dbh->getNoteById($id);
            if ($note && $note['idutente'] == $_SESSION['idutente']) {
                $success = $dbh->deleteNote($id);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
                exit;
            }
        }
    } elseif ($action === 'approve' || $action === 'reject') {
        // Approvazione e rifiuto sono solo per admin
        if (!isUserAdmin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
            exit;
        }

        if ($action === 'approve') {
            $success = $dbh->approveNote($id);
        } elseif ($action === 'reject') {
            $success = $dbh->rejectNote($id);
        }
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit;
}

// Gestione delle richieste GET per filtrare e ordinare appunti
if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    $sort = $_GET['sort'] ?? 'data_pubblicazione';
    $order = $_GET['order'] ?? 'DESC';
    $idutente = isset($_GET['idutente']) ? $_GET['idutente'] : '';
    $idcorso = isset($_GET['idcorso']) ? $_GET['idcorso'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $approvalFilter = isset($_GET['approval']) ? $_GET['approval'] : 'approved';

    $appunti = $dbh->getNotesWithFilters(idutente: $idutente, idcorso: $idcorso, sort: $sort, order: $order, search: $search, approvalFilter: $approvalFilter);

    $response = array_map(function ($res) {
        $res['numero_visualizzazioni'] = $res['numero_visualizzazioni'];
        $res['data_formattata'] = date('d/m/y', strtotime($res['data_pubblicazione']));
        $res['media_recensioni'] = $res['media_recensioni'] ?: 'N/A';
        $res['numero_recensioni'] = $res['numero_recensioni'];
        return $res;
    }, $appunti);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

header('Content-Type: application/json');
echo json_encode(['error' => 'Invalid action']);
