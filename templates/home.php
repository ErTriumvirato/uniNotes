<header class="row mb-3">
    <div class="col-12 text-center">
        <h1 class="display-4 fw-bold">Benvenutə<?php if (isUserLoggedIn()) echo ' ' . $_SESSION['username'] ?>!</h1>
    </div>
</header>

<!-- Messaggio se l'utente non segue corsi (gestito via AJAX) -->
<div id="no-courses-banner-container"></div>

<!-- Sezione appunti recenti -->
<section aria-label="Lista appunti recenti" class="card shadow-sm border-0 mb-5">
    <div class="card-body p-3 p-md-4">
        <h2 class="h2 mb-4">Appunti recenti</h2>
        <div id="recent-notes-container" class="row g-4" aria-live="polite">
            <!-- Caricamento contenuto con AJAX -->
        </div>
    </div>
</section>

<!-- Sezione appunti più visualizzati -->
<section aria-label="Lista appunti più visualizzati" class="card shadow-sm border-0 mb-5">
    <div class="card-body p-3 p-md-4">
        <h2 class="h2 mb-4">Appunti più visualizzati</h2>
        <div id="most-viewed-notes-container" class="row g-4" aria-live="polite">
            <!-- Caricamento contenuto con AJAX -->
        </div>
    </div>
</section>