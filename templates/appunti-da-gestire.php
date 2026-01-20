<div class="container">

    <!-- Titolo -->
    <header class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold h2">Gestione appunti</h1>
        </div>
    </header>

    <!-- Sezione gestione appunti -->
    <section aria-label="Gestione appunti" class="card shadow-sm border-0 mb-5">
        <h2 class="visually-hidden">Gestione appunti</h2>
        <div class="card-body p-3 p-md-4">
            <?php
            // Usate da `templates/appunti-template.php`
            $titoloFiltri = "";
            $defaultMessage = "Nessun appunto disponibile.";

            // Inclusione del template che genera la lista degli appunti
            include 'templates/appunti-template.php';
            ?>
        </div>
    </section>
</div>