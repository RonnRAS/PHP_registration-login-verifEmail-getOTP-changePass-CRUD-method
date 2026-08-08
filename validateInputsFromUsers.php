<?php
require "config.php";


//show error message
function showError($field, $errors)
{

    if (!empty($errors[$field])) {
        return "<p class='errorHandler' style='color:red;'>" . htmlspecialchars($errors[$field]) . "</p>";
    } else {
        return "<p class='errorHandler'></p>";
    }
}

// sendBack is for rejecting user to access without using the login
function sendBack($type){
    if(empty($_SESSION['user_id']) || $_SESSION['role_type'] !== $type){
        header("Location: index.php");
        exit();
    }
}

//handle the errors and send it back
function redirectWithErrors ($errors, $activeForm, $redirectTo){
    if (!empty(array_filter($errors))) {
        $_SESSION['errors'] = $errors;
        $_SESSION['active_form'] = $activeForm;
        $_SESSION['old'] = $_POST;

        header("Location: $redirectTo");
        exit();
    }
}

//valid input ?? New location
function redirectWithForm($toActive, $toNewlocation){
    $_SESSION['active_form'] = $toActive;
    header("Location: $toNewlocation");
    exit();
}

//check the input if valid
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

    // LOGIN VALIDATION
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

    // for CHANGE PASSWORD newPass ? confirmPass
    if(isset($rules['equlToNewPassword'])){
        if($value !== $rules['equlToNewPassword']){
            return $rules['properties'] . " does not match!";
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
    $lastName = trim($_POST['lastName']);
    $userSex = trim($_POST['userSex']);
    $userEmail = trim($_POST['userEmail']);
    $userRole = trim($_POST['userRole']);
    $registerPassword = trim($_POST['registerPassword']);

    $passwordhashed = password_hash($registerPassword, PASSWORD_DEFAULT);

    $errors['firstName'] = checkInput($_POST['firstName'], ['properties' => 'Firstname', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
    if(isset($_POST['noMiddleName'])){
        $errors['middleName'] = "";
        $middleName = "N/A";
    }else{
        $errors['middleName'] = checkInput($_POST['middleName'], ['properties' => 'Middlename', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
        $middleName = trim($_POST['middleName']);

    }
    $errors['lastName'] = checkInput($_POST['lastName'], ['properties' => 'Lastname', 'required' => true, 'minLength' => 2, 'lettersOnly' => true]);
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

    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['role_type'] = $userData['role_type'];

    if ($userData['role_type'] === 'Admin') {
        header("Location: adminDashboard.php");
    } else if ($userData['role_type'] === 'User') {
        header("Location: userDashboard.php");
    }
    exit();
}

//===========================================================================================================
// VERIFY EMAIL
//===========================================================================================================

if (isset($_POST['verifyEmailAccount'])) {
    $verifyEmail = $_POST['verifyEmail'];

    $errors['verifyEmail'] = checkInput($_POST['verifyEmail'], ['properties' => 'Email', 'required' => true, 'emailMustExists' => true], $conn);

    redirectWithErrors($errors, 'verifyEmailAccountForm', 'verifyEmail.php');

    redirectWithForm('otpVerifyForm', 'verifyEmail.php');
}


//===========================================================================================================
// VERIFY OTP
//===========================================================================================================
if (isset($_POST['verifyOtpCode'])){
    $otpCode = $_POST['otpCode'];

    $errors['otpCode'] = checkInput($_POST['otpCode'], ['properties' => 'OTP', 'required' => true, 'mustCorrect' => true]);

    redirectWithErrors($errors, 'otpVerifyForm', 'verifyEmail.php');

    redirectWithForm('changePasswordForm', 'changePassword.php');

}

//===========================================================================================================
// CHANGE PASSWORD
//===========================================================================================================
if(isset($_POST['changePassword'])){
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    $errors['newPassword'] = checkInput($_POST['newPassword'], ['properties' => 'Password', 'required' => true, 'minLength' => 9, 'upperCase' => true, 'haveNum' => true, 'specialChar' => true]);
    $errors['confirmPassword'] = checkInput($_POST['confirmPassword'], ['properties' => 'Password', 'required' => true, 'equlToNewPassword' => $newPassword] );

    redirectWithErrors($errors, 'changePasswordForm', 'changePassword.php');

    redirectWithForm('loginAccountForm','index.php');
}