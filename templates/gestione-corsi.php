<div class="container py-5">
    <h2 class="mb-4">Gestione Corsi e SSD</h2>

    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="corsi-tab" data-bs-toggle="tab" data-bs-target="#corsi-pane" type="Corsi" role="tab">Corsi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ssd-tab" data-bs-toggle="tab" data-bs-target="#ssd-pane" type="SSD" role="tab">SSD</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        <!-- Tab Corsi -->
        <div class="tab-pane fade show active" id="corsi-pane" role="tabpanel">
            <div class="row mb-3 g-2">
                <div class="col-md-4">
                    <input type="text" id="searchCourse" class="form-control" placeholder="Cerca corso...">
                </div>
                <div class="col-md-3">
                    <select id="filterSSD" class="form-select">
                        <option value="">Tutti gli SSD</option>
                        <!-- Populated by JS -->
                    </select>
                </div>
                <div class="col-md-3">
                     <select id="sortCourses" class="form-select">
                        <option value="nome">Ordina per Nome</option>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-primary w-100" onclick="openCourseModal()">Nuovo Corso</button>
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
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab SSD -->
        <div class="tab-pane fade" id="ssd-pane" role="tabpanel">
            <div class="row mb-3">
                <div class="col-12 text-end">
                    <button class="btn btn-primary" onclick="openSSDModal()">Nuovo SSD</button>
                </div>
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
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Corso -->
<div class="modal fade" id="courseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseModalTitle">Nuovo Corso</h5>
                <button type="Nuovo corso" class="btn-close" data-bs-dismiss="modal"></button>
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
                <button type="Annulla" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="Salva" class="btn btn-primary" onclick="saveCourse()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal SSD -->
<div class="modal fade" id="ssdModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ssdModalTitle">Nuovo SSD</h5>
                <button type="Nuovo SSD" class="btn-close" data-bs-dismiss="modal"></button>
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
                <button type="Annulla" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="Salva" class="btn btn-primary" onclick="saveSSD()">Salva</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCourses();
    loadSSDs();
    
    // Event Listeners for filters
    document.getElementById('searchCourse').addEventListener('input', debounce(loadCourses, 300));
    document.getElementById('filterSSD').addEventListener('change', loadCourses);
});

const courseModal = new bootstrap.Modal(document.getElementById('courseModal'));
const ssdModal = new bootstrap.Modal(document.getElementById('ssdModal'));

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

function loadCourses() {
    const search = document.getElementById('searchCourse').value;
    const ssd = document.getElementById('filterSSD').value;
    
    fetch(`gestione-corsi.php?action=get_courses&search=${encodeURIComponent(search)}&ssd=${encodeURIComponent(ssd)}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const tbody = document.getElementById('coursesTableBody');
                tbody.innerHTML = '';
                data.data.forEach(course => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${course.nomeCorso}</td>
                            <td><span class="badge bg-secondary">${course.nomeSSD}</span></td>
                            <td><small class="text-muted">${course.descrizioneCorso.substring(0, 50)}...</small></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editCourse(${course.idcorso})">Modifica</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteCourse(${course.idcorso})">Elimina</button>
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
            if(data.success) {
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
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editSSD(${ssd.idssd})">Modifica</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteSSD(${ssd.idssd})">Elimina</button>
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
    courseModal.show();
}

function editCourse(id) {
    fetch(`gestione-corsi.php?action=get_course&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const course = data.data;
                document.getElementById('courseId').value = course.idcorso;
                document.getElementById('courseName').value = course.nome;
                document.getElementById('courseDesc').value = course.descrizione;
                document.getElementById('courseSSD').value = course.idssd; // Assuming getCourseById returns idssd
                document.getElementById('courseModalTitle').innerText = 'Modifica Corso';
                courseModal.show();
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
        if(data.success) {
            courseModal.hide();
            loadCourses();
            alert('Corso salvato!');
        } else {
            alert('Errore: ' + data.message);
        }
    });
}

function deleteCourse(id) {
    if(confirm('Sei sicuro di voler eliminare questo corso?')) {
        const formData = new FormData();
        formData.append('action', 'delete_course');
        formData.append('id', id);
        
        fetch('gestione-corsi.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                loadCourses();
            } else {
                alert('Errore: ' + data.message);
            }
        });
    }
}

// SSD Operations
function openSSDModal() {
    document.getElementById('ssdForm').reset();
    document.getElementById('ssdId').value = '';
    document.getElementById('ssdModalTitle').innerText = 'Nuovo SSD';
    ssdModal.show();
}

function editSSD(id) {
    fetch(`gestione-corsi.php?action=get_ssd&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const ssd = data.data;
                document.getElementById('ssdId').value = ssd.idssd;
                document.getElementById('ssdName').value = ssd.nome;
                document.getElementById('ssdDesc').value = ssd.descrizione;
                document.getElementById('ssdModalTitle').innerText = 'Modifica SSD';
                ssdModal.show();
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
        if(data.success) {
            ssdModal.hide();
            loadSSDs();
            loadCourses(); // Reload courses as SSD names might have changed
            alert('SSD salvato!');
        } else {
            alert('Errore: ' + data.message);
        }
    });
}

function deleteSSD(id) {
    if(confirm('Sei sicuro di voler eliminare questo SSD?')) {
        const formData = new FormData();
        formData.append('action', 'delete_ssd');
        formData.append('id', id);
        
        fetch('gestione-corsi.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                loadSSDs();
            } else {
                alert('Errore: ' + data.message);
            }
        });
    }
}
</script>
