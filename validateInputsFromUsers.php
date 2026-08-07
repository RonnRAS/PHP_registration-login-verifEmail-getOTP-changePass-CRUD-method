<?php
require "config.php";
function showError($field, $errors)
{

    if (!empty($errors[$field])) {
        return "<p class='errorHandler' style='color:red;'>" . htmlspecialchars($errors[$field]) . "</p>";
    } else {
        return "<p class='errorHandler'></p>";
    }
}

function redirectWithErrors ($errors, $activeForm, $redirectTo){
    if (!empty(array_filter($errors))) {
        $_SESSION['errors'] = $errors;
        $_SESSION['active_form'] = $activeForm;
        $_SESSION['old'] = $_POST;

        header("Location: $redirectTo");
        exit();
    }

}

function redirectWithForm($toActive, $toNewlocation){
    $_SESSION['active_form'] = $toActive;
    header("Location: $toNewlocation");
    exit();
}

function checkInput($value, $rules, $conn = null)
{
    // for radio check if it's true 
    if (isset($rules['option'])) {
        if (empty($value)) {
            return $rules['properties'] . " is required";
        }
        return "";
    }

    $value = trim($value);

    //check if the inputs have a value
    if (isset($rules['required']) && $value === "") {
        return $rules['properties'] . " is required!";
    }
    //check if the specific inputs only have a letters chars
    if (isset($rules['lettersOnly']) && !preg_match('/^[A-Za-z\s]+$/', $value)) {
        return $rules['properties'] . " must contain letters only!";
    }

    if (isset($rules['minLength']) && strlen($value) < $rules['minLength']) {
        return $rules['properties'] . " must be at least " . $rules['minLength'] . " characters long.";
    }

    if (isset($rules['upperCase'])) {
        if (!preg_match('/[A-Z]/', $value)) {
            return $rules['properties'] . " Password must contain at least one uppercase letter.";
        }
    }

    if (isset($rules['haveNum'])) {
        if (!preg_match('/[0-9]/', $value)) {
            return $rules['properties'] . " At least one number(0-9)";
        }
    }

    if (isset($rules['emailExists'])) {
        $stmt = $conn->prepare("SELECT email FROM tbl1_users WHERE email = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $checkEmail = $stmt->get_result();


        if ($checkEmail->num_rows > 0) {
            return $rules['properties'] .  " is already registered.";
        }
    }

    // LOGIN AREA
    //check if the email is registered
    if (isset($rules['emailMustExists'])) {
        $stmt = $conn->prepare("SELECT email FROM tbl1_users WHERE email = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $checkEmail = $stmt->get_result();

        if ($checkEmail->num_rows === 0) {
            return $rules['properties'] .  " is not registered.";
        }
    }
}

//array of errors
$errors = [];



//===========================================================================================================
// REGISTER ACCOUNT
//===========================================================================================================

if (isset($_POST["RegisterAccount"])) {


    $firstName = trim($_POST['firstName']);
    $middleName = trim($_POST['middleName']);
    $lastName = trim($_POST['lastName']);
    $userSex = trim($_POST['userSex']);
    $userEmail = trim($_POST['userEmail']);
    $userRole = trim($_POST['userRole']);
    $registerPassword = trim($_POST['registerPassword']);

    $passwordhashed = password_hash($registerPassword, PASSWORD_DEFAULT);

    $errors['firstName'] = checkInput($_POST['firstName'], ['properties' => 'First name', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
    $errors['middleName'] = checkInput($_POST['middleName'], ['properties' => 'Middle name', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
    $errors['lastName'] = checkInput($_POST['lastName'], ['properties' => 'Last name', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
    $errors['userSex'] = checkInput($_POST['userSex'] ?? '', ['properties' => 'Sex', 'option' => true]);
    $errors['userEmail'] = checkInput($_POST['userEmail'], ['properties' => 'Email', 'required' => true, 'emailExists' => true], $conn);
    $errors['userRole'] = checkInput($_POST['userRole'] ?? '', ['properties' => 'Role', 'option' => true]);
    $errors['registerPassword'] = checkInput($_POST['registerPassword'], ['properties' => 'Password', 'required' => true, 'minLength' => 9, 'upperCase' => true, 'haveNum' => true, 'specialChar' => true]);

    redirectWithErrors($errors, 'registrationOfAccountForm', 'index.php');

    $stmt = $conn->prepare("INSERT INTO tbl1_users (firstName, middleName, lastName, sex, email, role_type, password) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssss', $firstName, $middleName, $lastName, $userSex, $userEmail, $userRole, $passwordhashed);
    $stmt->execute();


    redirectWithForm('loginAccountForm', 'index.php');
}

//===========================================================================================================
// lOGIN ACCOUNT
//===========================================================================================================

if (isset($_POST["loginAccount"])) {
    $loginEmail = $_POST["loginEmail"];
    $loginPassword = $_POST["loginPassword"];

    $errors['loginEmail'] = checkInput($_POST['loginEmail'], ['properties' => 'Email', 'required' => true, 'emailMustExists' => true], $conn);
    $errors['loginPassword'] = checkInput($_POST['loginPassword'], ['properties' => 'Password', 'required' => true]);

    
    redirectWithErrors($errors, 'loginAccountForm', 'index.php');

    $stmt = $conn->prepare("SELECT * FROM tbl1_users WHERE email = ?");
    $stmt->bind_param('s', $loginEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();

    if (!$userData || !password_verify($loginPassword, $userData['password'])) {

        redirectWithErrors(['loginPassword' => 'Invalid Email or Password!'], 'loginAccountForm', 'index.php');
    }

    if ($userData['role_type'] === 'Admin') {
        header("Location: adminDashboard.php");
    } else if ($userData['role_type'] === 'User') {
        header("Location: userDashboard.php");
    }
    exit();
}


if (isset($_POST['verifyEmailAccount'])) {
    $verifyEmail = $_POST['verifyEmail'];

    $errors['verifyEmail'] = checkInput($_POST['verifyEmail'], ['properties' => 'Email', 'required' => true, 'emailMustExists' => true], $conn);

    redirectWithErrors($errors, 'verifyEmailAccount', 'verifyEmail.php');

    redirectWithForm('otpVerifyForm', 'verifyEmail.php');
}