<?php
require_once 'config.php';

function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

function isUserAdmin() {
    return isUserLoggedIn() && $_SESSION['isAdmin'] == true;
}
