    <div class="container">
        <header class="row mb-4">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Gestione corsi e SSD</h2>
                <p class="text-muted">Amministra i corsi di studio e i Settori Scientifico Disciplinari</p>
            </div>
        </header>

        <!-- Sezione Corsi -->
        <section aria-labelledby="corsi-title" class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h3 id="corsi-title" class="card-title mb-0">Corsi</h3>
                    <button type="button" class="btn btn-primary" onclick="openCourseModal()">
                        Nuovo Corso
                    </button>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <input type="text" id="searchCourse" class="form-control" placeholder="Cerca corso..." oninput="debouncedLoadCourses()">
                    </div>
                    <div class="col-12 col-md-6">
                        <select id="filterSSD" class="form-select" onchange="loadCourses()">
                            <option value="">Tutti gli SSD</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>SSD</th>
                                <th>Descrizione</th>
                                <th class="text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="coursesTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Sezione SSD -->
        <section aria-labelledby="ssd-title" class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h3 id="ssd-title" class="card-title mb-0">SSD (Settori Scientifico Disciplinari)</h3>
                    <button type="button" class="btn btn-secondary" onclick="openSSDModal()">
                        Nuovo SSD
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Codice</th>
                                <th>Descrizione</th>
                                <th class="text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="ssdTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Corso -->
    <div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="courseModalTitle">Nuovo Corso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="courseForm">
                        <input type="hidden" id="courseId" name="id">
                        <div class="mb-3">
                            <label for="courseName" class="form-label">Nome Corso</label>
                            <input type="text" class="form-control" id="courseName" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="courseSSD" class="form-label">SSD</label>
                            <select class="form-select" id="courseSSD" name="idssd" required>
                                <!-- Populated by JS -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="courseDesc" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="courseDesc" name="descrizione" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" onclick="saveCourse()">Salva</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal SSD -->
    <div class="modal fade" id="ssdModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ssdModalTitle">Nuovo SSD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ssdForm">
                        <input type="hidden" id="ssdId" name="id">
                        <div class="mb-3">
                            <label for="ssdName" class="form-label">Codice (es. INF/01)</label>
                            <input type="text" class="form-control" id="ssdName" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="ssdDesc" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="ssdDesc" name="descrizione" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" onclick="saveSSD()">Salva</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let courseModalBS = null;
        let ssdModalBS = null;
        let searchTimeout = null;

        document.addEventListener('DOMContentLoaded', function() {
            courseModalBS = new bootstrap.Modal(document.getElementById('courseModal'));
            ssdModalBS = new bootstrap.Modal(document.getElementById('ssdModal'));

            loadCourses();
            loadSSDs();
        });

        function debouncedLoadCourses() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadCourses, 300);
        }

        function loadCourses() {
            const search = document.getElementById('searchCourse').value;
            const ssd = document.getElementById('filterSSD').value;

            fetch(`gestione-corsi.php?action=get_courses&search=${encodeURIComponent(search)}&ssd=${encodeURIComponent(ssd)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('coursesTableBody');
                        tbody.innerHTML = '';
                        data.data.forEach(course => {
                            tbody.innerHTML += `
                        <tr>
                            <td><strong>${course.nomeCorso}</strong></td>
                            <td><span class="badge bg-light text-dark border">${course.nomeSSD}</span></td>
                            <td><small class="text-muted">${course.descrizioneCorso.substring(0, 50)}...</small></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="editCourse(${course.idcorso})">Modifica</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteCourse(${course.idcorso}, this)">Elimina</button>
                                </div>
                            </td>
                        </tr>
                    `;
                        });
                    }
                });
        }

        function loadSSDs() {
            fetch('gestione-corsi.php?action=get_ssds')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate Table
                        const tbody = document.getElementById('ssdTableBody');
                        tbody.innerHTML = '';

                        // Populate Filter Select
                        const filterSelect = document.getElementById('filterSSD');
                        const currentFilter = filterSelect.value;
                        filterSelect.innerHTML = '<option value="">Tutti gli SSD</option>';

                        // Populate Modal Select
                        const modalSelect = document.getElementById('courseSSD');
                        modalSelect.innerHTML = '';

                        data.data.forEach(ssd => {
                            // Table
                            tbody.innerHTML += `
                        <tr>
                            <td><strong>${ssd.nome}</strong></td>
                            <td>${ssd.descrizione}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="editSSD(${ssd.idssd})">Modifica</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteSSD(${ssd.idssd}, this)">Elimina</button>
                                </div>
                            </td>
                        </tr>
                    `;

                            // Filter
                            filterSelect.innerHTML += `<option value="${ssd.nome}">${ssd.nome}</option>`;

                            // Modal
                            modalSelect.innerHTML += `<option value="${ssd.idssd}">${ssd.nome} - ${ssd.descrizione}</option>`;
                        });

                        filterSelect.value = currentFilter;
                    }
                });
        }

        // Course Operations
        function openCourseModal() {
            document.getElementById('courseForm').reset();
            document.getElementById('courseId').value = '';
            document.getElementById('courseModalTitle').innerText = 'Nuovo Corso';
            courseModalBS.show();
        }

        function editCourse(id) {
            fetch(`gestione-corsi.php?action=get_course&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const course = data.data;
                        document.getElementById('courseId').value = course.idcorso;
                        document.getElementById('courseName').value = course.nome;
                        document.getElementById('courseDesc').value = course.descrizione;
                        document.getElementById('courseSSD').value = course.idssd;
                        document.getElementById('courseModalTitle').innerText = 'Modifica Corso';
                        courseModalBS.show();
                    }
                });
        }

        function saveCourse() {
            const formData = new FormData(document.getElementById('courseForm'));
            formData.append('action', 'save_course');

            fetch('gestione-corsi.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        courseModalBS.hide();
                        loadCourses();
                        showSuccess(data.message);
                    } else {
                        showError('Errore: ' + data.message);
                    }
                });
        }

        function deleteCourse(id, btn) {
            if (!btn.dataset.confirm) {
                btn.dataset.confirm = 'true';
                btn.textContent = 'Conferma?';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
                setTimeout(() => {
                    if (btn.dataset.confirm) {
                        delete btn.dataset.confirm;
                        btn.textContent = 'Elimina';
                        btn.classList.remove('btn-danger');
                        btn.classList.add('btn-outline-danger');
                    }
                }, 3000);
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete_course');
            formData.append('id', id);

            fetch('gestione-corsi.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadCourses();
                            showSuccess(data.message);
                        } else {
                            showError('Errore: ' + data.message);
                        }
                    });
        }

        // SSD Operations
        function openSSDModal() {
            document.getElementById('ssdForm').reset();
            document.getElementById('ssdId').value = '';
            document.getElementById('ssdModalTitle').innerText = 'Nuovo SSD';
            ssdModalBS.show();
        }

        function editSSD(id) {
            fetch(`gestione-corsi.php?action=get_ssd&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const ssd = data.data;
                        document.getElementById('ssdId').value = ssd.idssd;
                        document.getElementById('ssdName').value = ssd.nome;
                        document.getElementById('ssdDesc').value = ssd.descrizione;
                        document.getElementById('ssdModalTitle').innerText = 'Modifica SSD';
                        ssdModalBS.show();
                    }
                });
        }

        function saveSSD() {
            const formData = new FormData(document.getElementById('ssdForm'));
            formData.append('action', 'save_ssd');

            fetch('gestione-corsi.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ssdModalBS.hide();
                        loadSSDs();
                        loadCourses();
                        showSuccess(data.message);
                    } else {
                        showError('Errore: ' + data.message);
                    }
                });
        }

        function deleteSSD(id, btn) {
            if (!btn.dataset.confirm) {
                btn.dataset.confirm = 'true';
                btn.textContent = 'Conferma?';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
                setTimeout(() => {
                    if (btn.dataset.confirm) {
                        delete btn.dataset.confirm;
                        btn.textContent = 'Elimina';
                        btn.classList.remove('btn-danger');
                        btn.classList.add('btn-outline-danger');
                    }
                }, 3000);
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete_ssd');
            formData.append('id', id);

            fetch('gestione-corsi.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadSSDs();
                            loadCourses();
                            showSuccess(data.message);
                        } else {
                            showError('Errore: ' + data.message);
                        }
                    });
        }
    </script>