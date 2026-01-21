<?php
require_once 'config.php';

requireAdmin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!empty($action)) {
    header('Content-Type: application/json');
    try {
        switch ($action) {
            case 'get_ssds': // Elenco SSD
                $search = $_GET['search'] ?? '';
                $ssds = $dbh->getAllSSD($search);
                echo json_encode(['success' => true, 'data' => $ssds]);
                break;

            case 'get_ssd': // Dettagli SSD
                $id = $_GET['id'] ?? 0;
                $ssd = $dbh->getSSDById($id);
                echo json_encode(['success' => true, 'data' => $ssd]);
                break;

            case 'save_ssd': // Aggiungi o modifica SSD
                $id = $_POST['id'] ?? 0;
                $nome = $_POST['nome'] ?? '';
                $descrizione = $_POST['descrizione'] ?? '';

                if (empty($nome) || empty($descrizione)) { // Tutti i campi obbligatori
                    throw new Exception("Tutti i campi sono obbligatori");
                }

                if ($id > 0) { // Update SSD
                    $result = $dbh->updateSSD($id, $nome, $descrizione);
                } else { // Create SSD
                    $result = $dbh->createSSD($nome, $descrizione);
                }

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'SSD salvato con successo']);
                } else {
                    throw new Exception("Errore durante il salvataggio del SSD");
                }
                break;

            case 'delete_ssd': // Elimina SSD
                $id = $_POST['id'] ?? 0;
                if ($dbh->deleteSSD($id)) {
                    echo json_encode(['success' => true, 'message' => 'SSD eliminato']);
                } else {
                    throw new Exception("Impossibile eliminare SSD (potrebbe essere associato a dei corsi)");
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Azione non valida']);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Errore durante il salvataggio del SSD"]);
    }
    exit();
}

$templateParams["titolo"] = "Gestione SSD";
$templateParams["nome"] = "templates/gestione-ssd-template.php";
array_push($templateParams["script"], "js/gestione-ssd.js");

require_once 'templates/base.php';
