// Implémenter le js de ma page

const lastnameinput = document.getElementById("lastnameinput");
const nameinput = document.getElementById("nameinput");
const emailinput = document.getElementById("emailinput");
const passwordinput = document.getElementById("passwordinput");
const validatepasswordinput = document.getElementById("validatepasswordinput");
const btnvalidation = document.getElementById("btn-validation-inscription");

lastnameinput.addEventListener("keyup", validateForm );
nameinput.addEventListener("keyup", validateForm );
emailinput.addEventListener("keyup", validateForm );
passwordinput.addEventListener("keyup", validateForm );
validatepasswordinput.addEventListener("keyup", validateForm );

function validateForm() {
    const nomOk = validateRequired(lastnameinput);
    const prenomOk = validateRequired(nameinput);
    const mailOk = validateMail(emailinput);
    const passwordOk = validatePassword(passwordinput);
    const validatepasswordOk = validateConfirmationPassword( passwordinput , validatepasswordinput);

    if(nomOk && prenomOk && mailOk && passwordOk && validatepasswordOk){
        btnvalidation.disabled = false;
    } else {
        btnvalidation.disabled = true;
    }
}

function validatePassword(input) {
    // Définir mon regex
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
    const passwordUser = input.value;
    if(passwordUser.match(passwordRegex)) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        return false;
    }
}

function validateConfirmationPassword(inputPwd , inputConfirmPwd) {
    if(inputPwd.value === inputConfirmPwd.value) {
        inputConfirmPwd.classList.add("is-valid");
        inputConfirmPwd.classList.remove("is-invalid");
        return true;
    } else {
        inputConfirmPwd.classList.remove("is-valid");
        inputConfirmPwd.classList.add("is-invalid");
        return false;
    }
}


function validateMail(input) {
    // Définir mon regex
    const emailRegex = /^[^\$@]+@[^\$@]+\.[^\$@]+$/;
    const mailUser = emailinput.value;
    if(mailUser.match(emailRegex)) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        return false;
    }
}

function validateRequired(input) {
    if(input.value != ''){
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        return false;
    }
}