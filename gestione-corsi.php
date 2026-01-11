<?php
require_once 'config.php';

requireLogin();

if(!isUserAdmin()) {
    header("Location: index.php");
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!empty($action)) {
    header('Content-Type: application/json');
    try {
        switch ($action) {
            case 'get_courses':
                $search = $_GET['search'] ?? '';
                $ssd = $_GET['ssd'] ?? '';
                $sort = $_GET['sort'] ?? 'nome'; 
                
                $courses = $dbh->getCoursesWithSSD($search, $ssd);
                
                if ($sort === 'nome') {
                    usort($courses, function($a, $b) {
                        return strcasecmp($a['nomeCorso'], $b['nomeCorso']);
                    });
                }
                
                echo json_encode(['success' => true, 'data' => $courses]);
                break;

            case 'get_course':
                $id = $_GET['id'] ?? 0;
                $course = $dbh->getCourseById($id);
                echo json_encode(['success' => true, 'data' => $course]);
                break;

            case 'save_course':
                $id = $_POST['id'] ?? 0;
                $nome = $_POST['nome'] ?? '';
                $descrizione = $_POST['descrizione'] ?? '';
                $idssd = $_POST['idssd'] ?? 0;

                if (empty($nome) || empty($descrizione) || empty($idssd)) {
                    throw new Exception("Tutti i campi sono obbligatori");
                }

                if ($id > 0) {
                    $result = $dbh->updateCourse($id, $nome, $descrizione, $idssd);
                } else {
                    $result = $dbh->createCourse($nome, $descrizione, $idssd);
                }

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Corso salvato con successo']);
                } else {
                    throw new Exception("Errore durante il salvataggio del corso");
                }
                break;

            case 'delete_course':
                $id = $_POST['id'] ?? 0;
                if ($dbh->deleteCourse($id)) {
                    echo json_encode(['success' => true, 'message' => 'Corso eliminato']);
                } else {
                    throw new Exception("Impossibile eliminare il corso (potrebbe avere appunti o iscritti collegati)");
                }
                break;

            case 'get_ssds':
                $ssds = $dbh->getAllSSD();
                echo json_encode(['success' => true, 'data' => $ssds]);
                break;

            case 'get_ssd':
                $id = $_GET['id'] ?? 0;
                $ssd = $dbh->getSSDById($id);
                echo json_encode(['success' => true, 'data' => $ssd]);
                break;

            case 'save_ssd':
                $id = $_POST['id'] ?? 0;
                $nome = $_POST['nome'] ?? '';
                $descrizione = $_POST['descrizione'] ?? '';

                if (empty($nome) || empty($descrizione)) {
                    throw new Exception("Tutti i campi sono obbligatori");
                }

                if ($id > 0) {
                    $result = $dbh->updateSSD($id, $nome, $descrizione);
                } else {
                    $result = $dbh->createSSD($nome, $descrizione);
                }

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'SSD salvato con successo']);
                } else {
                    throw new Exception("Errore durante il salvataggio del SSD");
                }
                break;
                
            case 'delete_ssd':
                $id = $_POST['id'] ?? 0;
                if ($dbh->deleteSSD($id)) {
                    echo json_encode(['success' => true, 'message' => 'SSD eliminato']);
                } else {
                    throw new Exception("Impossibile eliminare SSD (potrebbe essere associato a dei corsi)");
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

$templateParams["titolo"] = "uniNotes - Gestione Corsi";
$templateParams["nome"] = "templates/gestione-corsi.php";
require_once 'templates/base.php';
