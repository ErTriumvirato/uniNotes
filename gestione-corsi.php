<?php
require_once 'config.php';

requireAdmin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!empty($action)) {
    header('Content-Type: application/json');
    try {
        switch ($action) {
            case 'get_courses': // Elenco corsi
                $search = $_GET['search'] ?? '';
                $ssd = $_GET['ssd'] ?? '';
                $sort = $_GET['sort'] ?? 'nome';

                $courses = $dbh->getCoursesWithSSD($search, $ssd); // Recupera corsi con filtro

                if ($sort === 'nome') { // Ordina per nome corso
                    usort($courses, function ($a, $b) {
                        return strcasecmp($a['nomeCorso'], $b['nomeCorso']);
                    });
                }

                echo json_encode(['success' => true, 'data' => $courses]);
                break;

            case 'get_course': // Dettagli corso
                $id = $_GET['id'] ?? 0;
                $course = $dbh->getCourseById($id);
                echo json_encode(['success' => true, 'data' => $course]);
                break;

            case 'save_course': // Aggiungi o modifica corso
                $id = $_POST['id'] ?? 0;
                $nome = $_POST['nome'] ?? '';
                $descrizione = $_POST['descrizione'] ?? '';
                $idssd = $_POST['idssd'] ?? 0;

                if (empty($nome) || empty($descrizione) || empty($idssd)) { // Tutti i campi obbligatori
                    throw new Exception("Tutti i campi sono obbligatori");
                }

                if ($id > 0) { // Update corso
                    $result = $dbh->updateCourse($id, $nome, $descrizione, $idssd);
                } else { // Create corso
                    $result = $dbh->createCourse($nome, $descrizione, $idssd);
                }

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Corso salvato con successo']);
                } else {
                    throw new Exception("Errore durante il salvataggio del corso");
                }
                break;

            case 'delete_course': // Elimina corso
                $id = $_POST['id'] ?? 0;
                if ($dbh->deleteCourse($id)) { // Elimina corso
                    echo json_encode(['success' => true, 'message' => 'Corso eliminato']);
                } else {
                    throw new Exception("Impossibile eliminare il corso (potrebbe avere appunti o iscritti collegati)");
                }
                break;

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
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

$templateParams["titolo"] = "Gestione Corsi";
$templateParams["nome"] = "templates/gestione-corsi.php";
require_once 'templates/base.php';
