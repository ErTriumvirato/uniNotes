<div class="container">
    <header class="row mb-4">
        <div class="col-12">
            <h1 id="titolo-gestione-utenti" class="display-5 fw-bold h2">Gestione SSD</h1>
        </div>
    </header>

    <!-- Sezione SSD -->
    <section aria-labelledby="titolo-gestione-ssd" class="card shadow-sm border-0 mb-5">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <button type="button" class="btn btn-outline-primary" id="btn-new-ssd">
                    Nuovo SSD
                </button>
                <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#ssdFiltersCollapse" aria-expanded="false" aria-controls="ssdFiltersCollapse">
                    <em class="bi bi-filter"></em> Filtri
                </button>
            </div>

            <div class="collapse d-md-block" id="ssdFiltersCollapse">
                <div class="mb-4">
                    <label for="searchSSD" class="form-label small fw-semibold">Cerca per SSD</label>
                    <input type="search" id="searchSSD" name="ssd_search" class="form-control" placeholder='es. "IUS/01" o "MAT/05"' />
                </div>
            </div>

            <div>
                <table class="table table-hover align-middle table-sm">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Codice</th>
                            <th scope="col">Descrizione</th>
                            <th scope="col" class="text-end">Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="ssdTableBody" aria-live="polite">
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>


<!-- Modal SSD -->
<div class="modal fade" id="ssdModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="ssdModalTitle">Nuovo SSD</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <form id="ssdForm">
                    <input type="hidden" id="ssdId" name="id" />
                    <div class="mb-3">
                        <label for="ssdName" class="form-label">Codice (es. INF/01)</label>
                        <input type="text" class="form-control" id="ssdName" name="nome" required />
                    </div>
                    <div class="mb-3">
                        <label for="ssdDescription" class="form-label">Descrizione</label>
                        <input type="text" class="form-control" id="ssdDescription" name="descrizione" required />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" id="btn-save-ssd">Salva</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annulla</button>
            </div>
        </div>
    </div>
</div>