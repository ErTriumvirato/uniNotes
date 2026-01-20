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
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

$templateParams["titolo"] = "Gestione Corsi";
$templateParams["nome"] = "templates/gestione-corsi-template.php";
array_push($templateParams["script"], "js/gestione-corsi.js");

require_once 'templates/base.php';
