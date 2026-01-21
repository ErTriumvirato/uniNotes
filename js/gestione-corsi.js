"use strict";

let courseModalBS = new bootstrap.Modal(document.getElementById("courseModal"));
loadFiltersSSDs();
loadCourses();

document.getElementById("btn-new-course").addEventListener("click", openCourseModal);
document.getElementById("btn-save-course").addEventListener("click", saveCourse);

function createCourseRow(course) {
	return `
		<tr>
			<td class="text-break">${course.nomeCorso}</td>
			<td>${course.nomeSSD}</td>
			<td class="text-end col-actions-compact">
				<div class="d-flex gap-1 flex-column flex-md-row justify-content-end align-items-end">
					<button type="button" class="btn btn-sm btn-outline-secondary btn-edit-course" data-id="${course.idcorso}" title="Modifica">
						<em class="bi bi-pencil" aria-hidden="true"></em>
						<span class="visually-hidden">Modifica</span>
					</button>
					<button type="button" class="btn btn-sm btn-outline-danger btn-delete-course" data-id="${course.idcorso}" title="Elimina">
						<em class="bi bi-trash" aria-hidden="true"></em>
						<span class="visually-hidden">Elimina</span>
					</button>
				</div>
			</td>
		</tr>`;
}

function loadCourses() {
	const search = document.getElementById("searchCourse").value;
	const ssd = document.getElementById("filterSSD").value;
	const url = `gestione-corsi.php?action=get_courses&search=${encodeURIComponent(search)}&ssd=${encodeURIComponent(ssd)}`;

	handleButtonAction(null, url, null, (data) => {
		if (!data.success) return;

		document.getElementById("coursesTableBody").innerHTML = data.data.map(createCourseRow).join("");

		document.querySelectorAll(".btn-edit-course").forEach((btn) => {
			btn.addEventListener("click", () => editCourse(btn.dataset.id));
		});

		document.querySelectorAll(".btn-delete-course").forEach((btn) => {
			btn.addEventListener("click", () => deleteCourse(btn.dataset.id, btn));
		});
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
	handleButtonAction(null, `gestione-corsi.php?action=get_course&id=${id}`, null, (data) => {
		if (!data.success) return;

		const course = data.data;
		document.getElementById("courseId").value = course.idcorso;
		document.getElementById("courseName").value = course.nome;
		document.getElementById("courseDescription").value = course.descrizione;
		document.getElementById("courseSSD").value = course.idssd;
		document.getElementById("courseModalTitle").innerText = "Modifica Corso";
		courseModalBS.show();
	});
}

function saveCourse() {
	const formData = new FormData(document.getElementById("courseForm"));
	formData.append("action", "save_course");

	handleButtonAction(null, "gestione-corsi.php", new URLSearchParams(formData).toString(), (data) => {
		if (data.success) {
			courseModalBS.hide();
			loadCourses();
			showSuccess(data.message);
		} else {
			showError(data.message);
		}
	});
}

function resetDeleteCourseButton(btn) {
	delete btn.dataset.confirm;
	btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em><span class="visually-hidden">Elimina</span>';
	btn.classList.replace("btn-danger", "btn-outline-danger");
}

function deleteCourse(id, btn) {
	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML =
			'<em class="bi bi-check-lg" aria-hidden="true"></em><span class="visually-hidden">Conferma eliminazione</span>';
		btn.classList.replace("btn-outline-danger", "btn-danger");
		setTimeout(() => {
			if (btn.dataset.confirm) resetDeleteCourseButton(btn);
		}, 3000);
		return;
	}

	handleButtonAction(btn, "gestione-corsi.php", `action=delete_course&id=${id}`, (data) => {
		if (data.success) {
			loadCourses();
			showSuccess(data.message);
		} else {
			showError(data.message);
			resetDeleteCourseButton(btn);
		}
	});
}

function loadFiltersSSDs() {
	handleButtonAction(null, "gestione-ssd.php?action=get_ssds", null, (data) => {
		if (!data.success) return;

		const filterSelect = document.getElementById("filterSSD");
		const currentFilter = filterSelect.value;

		filterSelect.innerHTML =
			'<option value="">Tutti gli SSD</option>' +
			data.data.map((ssd) => `<option value="${ssd.nome}">${ssd.nome}</option>`).join("");

		document.getElementById("courseSSD").innerHTML = data.data
			.map((ssd) => `<option value="${ssd.idssd}">${ssd.nome} - ${ssd.descrizione}</option>`)
			.join("");

		filterSelect.value = currentFilter;
	});
}
