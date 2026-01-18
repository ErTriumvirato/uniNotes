<?php
require_once 'config.php';

if(isset($_SESSION['cookieBannerClosed']) && $_SESSION['cookieBannerClosed'] === true) {
    $cookieBannerClosed = true;
}
session_unset();
session_destroy();

if(isset($cookieBannerClosed) && $cookieBannerClosed === true) {
    session_start();
    $_SESSION['cookieBannerClosed'] = true;
}

header("Location: login.php");
exit();
