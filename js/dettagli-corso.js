// Gestisce follow e unfollow dei corsi
function handleFollowClick(button) {
	// Invia richiesta AJAX per seguire o smettere di seguire il corso (definita in base.js)
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

const followBtn = document.getElementById("followBtn");
followBtn.addEventListener("click", () => handleFollowClick(followBtn));
