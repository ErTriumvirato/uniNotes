"use strict";

document.addEventListener("DOMContentLoaded", () => {
	const followBtn = document.getElementById("followBtn");
	if (followBtn) {
		followBtn.addEventListener("click", () => handleFollowClick(followBtn));
	}
});

function handleFollowClick(button) {
	handleButtonAction(
		button,
		"corso.php?id=" + encodeURIComponent(button.dataset.idcorso),
		"toggleFollow=" + encodeURIComponent(button.dataset.idcorso),
		(data, el) => {
			el.innerHTML = data.following ? "Smetti di seguire" : "Segui corso";
			el.classList.replace(
				data.following ? "btn-outline-primary" : "btn-outline-danger",
				data.following ? "btn-outline-danger" : "btn-outline-primary",
			);
		},
	);
}