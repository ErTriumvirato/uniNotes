<?php
require_once 'config.php';

requireAdmin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!empty($action)) {
    header('Content-Type: application/json');
    try {
        switch ($action) {
            case 'get_users': // Recupera la lista degli utenti con filtri opzionali
                $search = $_GET['search'] ?? '';
                $role = $_GET['role'] ?? 'all';
                $users = $dbh->getUsersWithFilters(role: $role, search: $search);
                echo json_encode(['success' => true, 'data' => $users]);
                break;

            case 'get_user': // Recupera i dettagli di un singolo utente
                $id = $_GET['id'] ?? 0;
                $user = $dbh->getUserById($id);
                echo json_encode(['success' => true, 'data' => $user]);
                break;

            case 'save_user': // Crea o aggiorna un utente
                $id = $_POST['id'] ?? 0;
                $username = $_POST['username'] ?? '';
                $email = $_POST['email'] ?? '';
                $isAdmin = $_POST['ruolo'] ?? 0; // Default user role (0)
                $password = $_POST['password'] ?? '';

                if (empty($username) || empty($email)) {
                    throw new Exception("Username e Email obbligatori");
                }

                // update or create user
                if ($id > 0) {
                    // Update
                    if ($id == $_SESSION['idutente'] && $isAdmin != $_SESSION['isAdmin']) {
                        throw new Exception("Non puoi modificare il tuo stesso ruolo");
                    }
                    $result = $dbh->updateUser($id, $username, $email, $isAdmin, !empty($password) ? $password : null);
                } else {
                    // Create
                    if (empty($password)) {
                        throw new Exception("Password obbligatoria per nuovi utenti");
                    }
                    $result = $dbh->createUser($username, $email, $password, $isAdmin);
                }

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Utente salvato con successo']);
                } else {
                    throw new Exception("Errore durante il salvataggio (username o email potrebbero essere già in uso)");
                }
                break;

            case 'delete_user': // Elimina un utente
                $id = $_POST['id'] ?? 0;

                // Previene l'eliminazione del proprio account
                if ($id == $_SESSION['idutente']) {
                    throw new Exception("Non puoi eliminare il tuo stesso account");
                }

                // Previene l'eliminazione dell'ultimo admin
                if ($dbh->deleteUser($id)) {
                    echo json_encode(['success' => true, 'message' => 'Utente eliminato']);
                } else {
                    throw new Exception("Impossibile eliminare utente (potrebbe avere dati collegati)");
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Errore durante il salvataggio (username o email potrebbero essere già in uso)"]);
    }
    exit();
}

$templateParams["titolo"] = "Gestione Utenti";
$templateParams["nome"] = "templates/gestione-utenti-template.php";
array_push($templateParams["script"], "js/gestione-utenti.js");

require_once 'templates/base.php';
