function openModal(id) {
    document.getElementById(id).style.display = 'block';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}




// hapus akun



//logika eye icon
document.getElementById("eye-icon").addEventListener("click", function () {
    const passwordField = document.getElementById("password");
    const eyeIcon = document.getElementById("eye-icon");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.innerHTML = "🙉";
    } else {
        passwordField.type = "password";
        eyeIcon.innerHTML = "🙈";
    }
});

//sama tapi untuk register
document.getElementById("eye-icon-register").addEventListener("click", function () {
    const passwordField = document.getElementById("password-register");
    const eyeIcon = document.getElementById("eye-icon-register");


    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.innerHTML = "🙉";
    } else {
        passwordField.type = "password";
        eyeIcon.innerHTML = "🙈";
    }
});



//drag n dropp form

function makeDraggable(elementId) {
    const element = document.getElementById(elementId);
    let isDragging = false;
    let offsetX, offsetY;

    element.addEventListener("mousedown", function (e) {
        isDragging = true;
        offsetX = e.clientX - element.offsetLeft;
        offsetY = e.clientY - element.offsetTop;
        element.style.cursor = "grabbing";
    });

    document.addEventListener("mousemove", function (e) {
        if (isDragging) {
            const x = e.clientX - offsetX;
            const y = e.clientY - offsetY;
            element.style.left = `${x}px`;
            element.style.top = `${y}px`;
        }
    });

    document.addEventListener("mouseup", function () {
        isDragging = false;
        element.style.cursor = "move";
    });
}

// Aktifkan drag-and-drop untuk kedua form
makeDraggable("form");
makeDraggable("formRegister");
