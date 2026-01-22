<div class="container">
    <header class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold h2">Gestione corsi</h1>
        </div>
    </header>

    <!-- Sezione Corsi -->
    <section aria-labelledby="titolo-gestione-corsi" class="card shadow-sm border-0 mb-5">
        <div class="card-body p-3 p-md-4">
            <?php
            // Prepare extra buttons for filters
            ob_start();
            ?>
            <button type="button" class="btn btn-outline-primary" id="btn-new-course">
                Nuovo Corso
            </button>
            <?php
            $extraButtons = ob_get_clean();

            $searchId = 'searchCourse';
            $ssdId = 'filterSSD';
            $searchCallback = 'loadCourses()';
            $ssdCallback = 'loadCourses()';
            $showFollowFilter = false;
            require 'filtri-corsi.php';
            ?>

            <div>
                <table class="table table-hover align-middle table-sm">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">SSD</th>
                            <th scope="col" class="text-end">Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="coursesTableBody" aria-live="polite">
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal Corso -->
    <div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="courseModalTitle">Nuovo corso</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body">
                    <form id="courseForm" data-courseId="">
                        <input type="hidden" id="courseId" name="id" />
                        <div class="mb-3">
                            <label for="courseName" class="form-label">Nome corso</label>
                            <input type="text" class="form-control" id="courseName" name="nome" required />
                        </div>
                        <div class="mb-3">
                            <label for="courseDescription" class="form-label">Descrizione</label>
                            <input type="text" class="form-control" id="courseDescription" name="descrizione" required />
                        </div>
                        <div class="mb-3">
                            <label for="courseSSD" class="form-label">SSD</label>
                            <select class="form-select" id="courseSSD" name="idssd" required>
                                <!-- Riempito da JS -->
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" id="btn-save-course">Salva</button>
                </div>
            </div>
        </div>
    </div>