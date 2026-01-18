<div class="container">

    <!-- Titolo -->
    <header class="row mb-4">
        <div class="col-12">
            <h2 class="display-5 fw-bold">Gestione appunti</h2>
        </div>
    </header>

    <!-- Sezione gestione appunti -->
    <section aria-label="Gestione appunti" class="card shadow-sm border-0 mb-5">
        <div class="card-body p-3 p-md-4">
            <?php
            // Usate da `lista-appunti.php`
            $titoloFiltri = "";
            $messaggioVuoto = "Nessun appunto disponibile.";

            // Inclusione del template che genera la lista degli appunti
            include 'lista-appunti.php';
            ?>
        </div>
    </section>
</div>