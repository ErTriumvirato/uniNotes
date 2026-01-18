<?php
require_once 'config.php';

requireLogin();

if (!isUserAdmin()) {
    header("Location: index.php");
    exit();
}

$templateParams["titolo"] = "Area amministrazione";
$templateParams["nome"] = "templates/menu-gestione.php";
require_once 'templates/base.php';
