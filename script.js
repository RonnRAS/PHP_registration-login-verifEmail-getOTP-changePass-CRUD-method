

const showPasswordBox = document.querySelectorAll(".showPassword");
showPasswordBox.forEach((checkbox) => {
    checkbox.addEventListener("change", function(){
        const passwordInput = document.getElementById(this.dataset.target);
        passwordInput.type = this.checked ? "text" :  "password";
    });
});


function showForm(formId){
    const forms = document.querySelectorAll(".auth-card");

    forms.forEach(form => {
        form.classList.remove("d-block");
        form.classList.add("d-none");
    });
    const target = document.getElementById(formId);
    target.classList.remove("d-none");
    target.classList.add("d-block");
}