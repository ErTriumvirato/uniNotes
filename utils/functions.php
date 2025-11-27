<?php

function isUserLoggedIn() {
    return !empty($_SESSION['username']);
}

function registerLoggedUser($user) {
    $_SESSION["username"] = $user["username"];
}

?>