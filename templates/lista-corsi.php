<?php
require_once 'config.php';
$idutente = $_SESSION["idutente"] ?? null;
$ssds = $templateParams["ssds"];
?>

<div class="row justify-content-center mb-3">
    <div class="col-12 text-center">
        <h1 class="display-5 fw-bold">Corsi</h1>
    </div>
</div>

<?php
$showFollowFilter = !empty($idutente);
require 'filtri-corsi.php';
?>


<!-- Lista corsi -->
<section id="courses-container" class="row g-4" aria-label="Lista corsi" aria-live="polite">
    <h2 class="visually-hidden">Lista corsi</h2>
    <!-- I corsi verranno caricati via JS -->
</section>