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
        header("Location: index.php");
        exit();
    } else {
        $templateParams["error"] = "Username o password errati";
    }
}

$templateParams["titolo"] = "uniNotes - Login";
$templateParams["nome"] = "templates/login-form.php";

require 'templates/base.php';
