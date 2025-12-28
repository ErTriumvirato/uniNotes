<?php
session_start();

require_once 'db/database.php';
require_once 'utils/functions.php';
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = $dbh->getUserByUsername($username);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['ruolo'] = $user['ruolo'];
        header("Location: index.php");
        exit();
    } else {
        $templateParams["error"] = "Username o password errati";
    }
}

$templateParams["titolo"] = "uniNotes - Login";
$templateParams["nome"] = "templates/login-form.php";

require 'templates/base.php';
