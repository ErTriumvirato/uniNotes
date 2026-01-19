"use strict";

let courseModalBS = null;
let searchTimeout = null;

document.addEventListener("DOMContentLoaded", function () {
	courseModalBS = new bootstrap.Modal(document.getElementById("courseModal"));
	loadFiltersSSDs();
	loadCourses();
});

function debouncedLoadCourses() {
	clearTimeout(searchTimeout);
	searchTimeout = setTimeout(loadCourses, 300);
}

function loadCourses() {
	const search = document.getElementById("searchCourse").value;
	const ssd = document.getElementById("filterSSD").value;

	fetch(`gestione-corsi.php?action=get_courses&search=${encodeURIComponent(search)}&ssd=${encodeURIComponent(ssd)}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				const tbody = document.getElementById("coursesTableBody");
				tbody.innerHTML = "";
				data.data.forEach((course) => {
					tbody.innerHTML += `
                                <tr>
                                    <td class="text-break">${course.nomeCorso}</td>
                                    <td class="">${course.nomeSSD}</td>
                                    <td class="text-end col-actions-compact">
                                        <div class="d-flex gap-1 flex-column flex-md-row justify-content-end align-items-end">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editCourse(${course.idcorso})" title="Modifica">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                                <span class="visually-hidden">Modifica</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCourse(${course.idcorso}, this)" title="Elimina">
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                                <span class="visually-hidden">Elimina</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
				});
			}
		});
}

// Course Operations
function openCourseModal() {
	document.getElementById("courseForm").reset();
	document.getElementById("courseId").value = "";
	document.getElementById("courseModalTitle").innerText = "Nuovo Corso";
	courseModalBS.show();
}

function editCourse(id) {
	fetch(`gestione-corsi.php?action=get_course&id=${id}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				const course = data.data;
				document.getElementById("courseId").value = course.idcorso;
				document.getElementById("courseName").value = course.nome;
				document.getElementById("courseDescription").value = course.descrizione;
				document.getElementById("courseSSD").value = course.idssd;
				document.getElementById("courseModalTitle").innerText = "Modifica Corso";
				courseModalBS.show();
			}
		});
}

function saveCourse() {
	const formData = new FormData(document.getElementById("courseForm"));
	formData.append("action", "save_course");

	fetch("gestione-corsi.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				courseModalBS.hide();
				loadCourses();
				showSuccess(data.message);
			} else {
				showError("Errore: " + data.message);
			}
		});
}

function deleteCourse(id, btn) {
	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML = '<i class="bi bi-check-lg" aria-hidden="true"></i><span class="visually-hidden">Conferma eliminazione</span>';
		btn.classList.remove("btn-outline-danger");
		btn.classList.add("btn-danger");
		setTimeout(() => {
			if (btn.dataset.confirm) {
				delete btn.dataset.confirm;
				btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i><span class="visually-hidden">Elimina</span>';
				btn.classList.remove("btn-danger");
				btn.classList.add("btn-outline-danger");
			}
		}, 3000);
		return;
	}

	const formData = new FormData();
	formData.append("action", "delete_course");
	formData.append("id", id);

	fetch("gestione-corsi.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				loadCourses();
				showSuccess(data.message);
			} else {
				showError("Errore: " + data.message);
			}
		});
}

function loadFiltersSSDs() {
	fetch(`gestione-ssd.php?action=get_ssds`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				// Riempie Select Filtro
				const filterSelect = document.getElementById("filterSSD");
				const currentFilter = filterSelect.value;
				filterSelect.innerHTML = '<option value="">Tutti gli SSD</option>';

				// Riempie Select Modal
				const modalSelect = document.getElementById("courseSSD");
				modalSelect.innerHTML = "";

				data.data.forEach((ssd) => {
					// Filtro
					filterSelect.innerHTML += `<option value="${ssd.nome}">${ssd.nome}</option>`;

					// Modal
					modalSelect.innerHTML += `<option value="${ssd.idssd}">${ssd.nome} - ${ssd.descrizione}</option>`;
				});

				filterSelect.value = currentFilter; // TODO: mantenere selezione anche se non più presente???????
			}
		});
}
