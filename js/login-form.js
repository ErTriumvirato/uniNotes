"use strict";

document.addEventListener("DOMContentLoaded", function () {
    const serverMessage = document.getElementById("server-message");
    if (serverMessage) {
        const message = serverMessage.dataset.message;
        const type = serverMessage.dataset.type;
        if (type === "success") {
            showSuccess(message);
        } else {
            showError(message);
        }
    }
});
