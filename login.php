<?php
require_once 'config.php';

if (isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = $dbh->getUserByUsername($username);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['idutente'] = $user['idutente'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['isAdmin'] = $user['isAdmin'];
        
        // Redirect to the requested page, if there is one
        $redirect = $_GET['redirect'] ?? null;
        if (isValidRedirect($redirect)) {
            header('Location: ' . $redirect);
            exit();
        }
        header("Location: index.php");
        exit();
    } else {
        $templateParams["error"] = "Username o password errati";
    }
}

function isValidRedirect($url)
{
    if (!isset($url) || empty($url)) {
        return false;
    }
    return true;
}

$templateParams["titolo"] = "uniNotes - Login";
$templateParams["nome"] = "templates/login-form.php";

require 'templates/base.php';
