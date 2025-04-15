function validateTextInput(input, errorId) {
    let regex = /^[a-zA-ZÀ-ÿ\s]+$/; // Only letters and spaces
    let errorMsg = document.getElementById(errorId);
    
    if (!regex.test(input.value.trim())) {
        input.style.border = "2px red";
        errorMsg.innerText = "Ce champ ne doit contenir que des lettres.";
    } else {
        input.style.border = "1px solid green";
        errorMsg.innerText = "";
    }
}

// Prevent selecting future years (1920-2006)
document.addEventListener("DOMContentLoaded", function () {
    let dateInput = document.querySelector('input[name="date"]');
    let minYear = "1920-01-01";
    let maxYear = "2006-12-31";
    
    dateInput.setAttribute("min", minYear);
    dateInput.setAttribute("max", maxYear);
});

// Prevent form submission if errors exist
function validateForm(event) {
    let name = document.querySelector('input[name="nom"]');
    let surname = document.querySelector('input[name="prenom"]');
    let nameValid = /^[a-zA-ZÀ-ÿ\s]+$/.test(name.value.trim());
    let surnameValid = /^[a-zA-ZÀ-ÿ\s]+$/.test(surname.value.trim());

    if (!nameValid || !surnameValid) {
        event.preventDefault(); // Stop form submission
        alert("Veuillez corriger les erreurs avant de soumettre.");
    }
}
