<?php
include "config.php";

function checkInput($value, $rules, $conn = null)
{
    // for radio check if it's true 
    if(isset($rules['option'])){
        if(empty($value)){
            return $rules['properties'] . " is required";
        }
        return "";
    }

    $value = trim($value);

    //check if the inputs have a value
    if(isset($rules['required'])&& $value === ""){
        return $rules['properties'] . " is required!";
    }
    //check if the specific inputs only have a letters chars
    if (isset($rules['lettersOnly']) && !preg_match('/^[A-Za-z\s]+$/', $value)) {
        return $rules['properties'] . " must contain letters only!";
    }
}


if (isset($_POST["RegisterAccount"])) {

    $errors = [];


    $errors['firstName'] = checkInput($_POST['firstName'], ['properties' => 'First name', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
    $errors['middleName'] = checkInput($_POST['middleName'], ['properties' => 'Middle name', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
    $errors['lastName'] = checkInput($_POST['lastName'], ['properties' => 'Last name', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
    $errors['userSex'] = checkInput($_POST['userSex'] ?? '', ['properties' => 'Sex', 'option' => true]);
    $errors['userEmail'] = checkInput($_POST['userEmail'], ['properties' => 'Email', 'required' => true, 'email' => true, 'emaiExists' => true], $conn);
    $errors['userRole'] = checkInput($_POST['userRole'] ?? '', ['properties' => 'Role', 'option' => true]);
    $errors['registerPassword'] = checkInput($_POST['registerPassword'], ['properties' => 'Password', 'required' => true, 'minLength' => 9, 'upperCase' => true, 'haveNum' => true]);

    if(!empty($errors)){
        $_SESSION['errors'] = $errors;
        $_SESSION['active_form'] = 'registerAccountForm';
        $_SESSION['old'] = $_POST;

        header("Location: index.php");
        exit();
    }


}

if (isset($_POST["loginAccount"])) {
    $userEmail = $_POST["loginEmail"];
    $password = $_POST["loginPassword"];
}
