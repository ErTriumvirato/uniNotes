<div>
    <h2>Gestione Corsi e SSD</h2>

    <div>
        <!-- Sezione Corsi -->
        <div id="corsi-section">
            <h3>Corsi</h3>
            <div>
                <div>
                    <input type="text" id="searchCourse" placeholder="Cerca corso...">
                </div>
                <div>
                    <select id="filterSSD">
                        <option value="">Tutti gli SSD</option>
                    </select>
                </div>
                <div>
                     <select id="sortCourses">
                        <option value="nome">Ordina per Nome</option>
                    </select>
                </div>
                <div>
                    <button type="button" onclick="openCourseModal()">Nuovo Corso</button>
                </div>
            </div>

            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>SSD</th>
                            <th>Descrizione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="coursesTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <!-- Sezione SSD -->
        <div id="ssd-section">
            <h3>SSD</h3>
            <div>
                <div>
                    <button type="button" onclick="openSSDModal()">Nuovo SSD</button>
                </div>
            </div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Codice</th>
                            <th>Descrizione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="ssdTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Corso -->
<div id="courseModal" hidden>
    <div>
        <div>
            <div>
                <h5 id="courseModalTitle">Nuovo Corso</h5>
                <button type="button" onclick="closeModal('courseModal')">Chiudi</button>
            </div>
            <div>
                <form id="courseForm">
                    <input type="hidden" id="courseId" name="id">
                    <div>
                        <label for="courseName">Nome Corso</label>
                        <input type="text" id="courseName" name="nome" required>
                    </div>
                    <div>
                        <label for="courseSSD">SSD</label>
                        <select id="courseSSD" name="idssd" required>
                            <!-- Populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="courseDesc">Descrizione</label>
                        <textarea id="courseDesc" name="descrizione" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div>
                <button type="button" onclick="closeModal('courseModal')">Annulla</button>
                <button type="button" onclick="saveCourse()">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal SSD -->
<div id="ssdModal" hidden>
    <div>
        <div>
            <div>
                <h5 id="ssdModalTitle">Nuovo SSD</h5>
                <button type="button" onclick="closeModal('ssdModal')">Chiudi</button>
            </div>
            <div>
                <form id="ssdForm">
                    <input type="hidden" id="ssdId" name="id">
                    <div>
                        <label for="ssdName">Codice (es. INF/01)</label>
                        <input type="text" id="ssdName" name="nome" required>
                    </div>
                    <div>
                        <label for="ssdDesc">Descrizione</label>
                        <textarea id="ssdDesc" name="descrizione" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div>
                <button type="button" onclick="closeModal('ssdModal')">Annulla</button>
                <button type="button" onclick="saveSSD()">Salva</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCourses();
    loadSSDs();
    
    // Event Listeners for filters
    document.getElementById('searchCourse').addEventListener('input', debounce(loadCourses, 300));
    document.getElementById('filterSSD').addEventListener('change', loadCourses);
});

// const courseModal = new bootstrap.Modal(document.getElementById('courseModal'));
// const ssdModal = new bootstrap.Modal(document.getElementById('ssdModal'));

function closeModal(modalId) {
    document.getElementById(modalId).hidden = true;
}

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
                            <td><span>${course.nomeSSD}</span></td>
                            <td><small>${course.descrizioneCorso.substring(0, 50)}...</small></td>
                            <td>
                                <button type="button" onclick="editCourse(${course.idcorso})">Modifica</button>
                                <button type="button" onclick="deleteCourse(${course.idcorso})">Elimina</button>
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
                            <td>
                                <button type="button" onclick="editSSD(${ssd.idssd})">Modifica</button>
                                <button type="button" onclick="deleteSSD(${ssd.idssd})">Elimina</button>
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
    document.getElementById('courseModal').hidden = false;
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
                document.getElementById('courseModal').hidden = false;
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
            closeModal('courseModal');
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
    document.getElementById('ssdModal').hidden = false;
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
                document.getElementById('ssdModal').hidden = false;
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
            closeModal('ssdModal');
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
