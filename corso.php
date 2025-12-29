<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    header("Location: index.php");
    exit();
}

// templateParams
$templateParams["titolo"] = "uniNotes - nome del corso";
$templateParams["nome"] = "templates/dettagli-corso.php";

require_once 'templates/base.php';
