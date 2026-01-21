<?php
require_once 'config.php';

requireAdmin();

$templateParams["titolo"] = "Area amministrazione";
$templateParams["nome"] = "templates/menu-gestione-template.php";

require_once 'templates/base.php';
