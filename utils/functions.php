<?php
define('ROLE_ADMIN', 1);
define('ROLE_USER', 2);

function isUserLoggedIn() {
    return !empty($_SESSION['username']);
}

function isUserAdmin() {
    return isUserLoggedIn() && isset($_SESSION['ruolo']) && $_SESSION['ruolo'] === ROLE_ADMIN;
}
