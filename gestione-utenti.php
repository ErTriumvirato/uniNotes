<?php
require_once 'config.php';

requireLogin();

if(!isUserAdmin()){
    header("Location: index.php");
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!empty($action)) {
    header('Content-Type: application/json');
    try {
        switch ($action) {
            case 'get_users':
                $search = $_GET['search'] ?? '';
                $role = $_GET['role'] ?? 'all';
                $users = $dbh->getAllUsers($search, $role);
                echo json_encode(['success' => true, 'data' => $users]);
                break;

            case 'get_user':
                $id = $_GET['id'] ?? 0;
                $user = $dbh->getUserById($id);
                echo json_encode(['success' => true, 'data' => $user]);
                break;

            case 'save_user':
                $id = $_POST['id'] ?? 0;
                $username = $_POST['username'] ?? '';
                $email = $_POST['email'] ?? '';
                $isAdmin = $_POST['ruolo'] ?? 0; // Default user role (0)
                $password = $_POST['password'] ?? '';

                if (empty($username) || empty($email)) {
                    throw new Exception("Username ed Email obbligatori");
                }

                if ($id > 0) {
                    // Update
                    // Prevent modifying own role
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

            case 'delete_user':
                $id = $_POST['id'] ?? 0;
                
                // Prevent deleting yourself
                if ($id == $_SESSION['idutente']) {
                    throw new Exception("Non puoi eliminare il tuo stesso account");
                }
                
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
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

$templateParams["titolo"] = "uniNotes - Gestione Utenti";
$templateParams["nome"] = "templates/gestione-utenti.php";
require_once 'templates/base.php';
