<?php
require_once 'config.php';

requireLogin();

if(!isUserAdmin()) {
    header("Location: index.php");
    exit();
}

$templateParams["titolo"] = "uniNotes - Gestione Appunti";
$templateParams["nome"] = "templates/menu-appunti.php";
require_once 'templates/base.php';
