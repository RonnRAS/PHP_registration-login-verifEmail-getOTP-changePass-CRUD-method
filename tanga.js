
console.log("MY SCRIPT IS RUNNING");
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

const middleName = document.getElementById("middleName");
const noMiddleName = document.getElementById("noMiddleName");

console.log("dasda");

noMiddleName.addEventListener("change", function (){
    console.log("checkbox changed, checked:", this.checked);
    if (this.checked) {
        middleName.disabled = true;
        middleName.value = "";
    }else {
        middleName.disabled = false;
        middleName.value = "";
        middleName.focus();
    }
});