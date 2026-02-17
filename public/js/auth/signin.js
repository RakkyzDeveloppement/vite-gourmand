const mailInput = document.getElementById("emailinput");
const passwordInput = document.getElementById("passwordinput");
const btnSignin = document.getElementById("btnsignin");

btnSignin.addEventListener("click", checkCredentials);

function checkCredentials() {
    // Ici , appeler l'api pour vérifier les credentials en BDD

    if(mailInput.value == "test@mail.com" && passwordInput.value == "123") {
        // Il faudra récupérer le vrai token
        const token = "gfkgjsdhjqkqqjkgjqhqehboqreibe"
        setToken(token);
        // Placer ce token en cookies

        setCookie("role", "admin", 7);

        window.location.replace("/");
    } else {
        mailInput.classList.add("is-invalid");
        passwordInput.classList.add("is-invalid");
    }
}