<?php
session_start();
include "validateInputsFromUsers.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if(isset($_SESSION['user_id'])){
    if ($_SESSION['role_type'] === 'Admin') {
        header("Location: adminDashboard.php");
        exit();
    } else if ($_SESSION['role_type'] === 'User') {
        header("Location: userDashboard.php");
        exit();
    }
}

$errors = $_SESSION['errors'] ?? [];
$activeForm = $_SESSION['active_form'] ?? 'loginAccountForm';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['active_form'], $_SESSION['old']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <title>Registration Account</title>
</head>

<body>
    <div class="auth-card <?= $activeForm === 'registrationOfAccountForm' ? 'd-block' : 'd-none' ?>" id="registrationOfAccountForm">
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h1>Register Account</h1>
            <div class="inputHandler">
                <label for="firstName">First name</label>
                <input type="text" id="firstName" name="firstName"
                    value="<?= htmlspecialchars($old['firstName'] ?? '') ?>">
                <?= showError('firstName', $errors) ?>
            </div>

            <div class="inputHandler">
                <label for="middleName">Middle name</label>
                <input type="text" id="middleName" name="middleName"
                    value="<?= htmlspecialchars($old['middleName'] ?? '') ?>"
                    <?= htmlspecialchars($old['noMiddleName'] ?? '') === 'on' ? 'disabled' : '' ?>>
                <input type="checkbox" id="noMiddleName" name="noMiddleName" value="on" 
                <?=  htmlspecialchars($old['noMiddleName'] ?? '') === 'on' ? 'checked' : '' ?>>
                <label for="noMiddleName">No middle name</label>
                <?= showError('middleName', $errors) ?>
            </div>

            <div class="inputHandler">
                <label for="lastName">Last name</label>
                <input type="text" id="lastName" name="lastName"
                    value="<?= htmlspecialchars($old['lastName'] ?? '') ?>">
                <?= showError('lastName', $errors) ?>
            </div>

            <div class="inputHandler">
                <label for="userSex">Sex</label>
                <input type="radio" id="male" name="userSex" value="Male"
                    <?= ($old['userSex'] ?? '') === 'Male' ? 'checked' : '' ?>>
                <label for="male">Male</label>
                <input type="radio" id="female" name="userSex" value="Female"
                    <?= ($old['userSex'] ?? '') === 'Female' ? 'checked' : '' ?>>
                <label for="female">Female</label>
                <?= showError('userSex', $errors) ?>
            </div>

            <div class="inputHandler">
                <label for="userEmail">Email</label>
                <input type="email" id="userEmail" name="userEmail"
                    value="<?= htmlspecialchars($old['userEmail'] ?? '') ?>">
                <?= showError('userEmail', $errors) ?>
            </div>

            <div class="inputHandler">
                <label for="userRole">Role</label>
                <input type="radio" id="admin" name="userRole" value="Admin"
                    <?= ($old['userRole'] ??  '') === 'Admin' ? 'checked' : '' ?>>
                <label for="admin">Admin</label>
                <input type="radio" id="user" name="userRole" value="User"
                    <?= ($old['userRole'] ?? '') === 'User' ? 'checked' : '' ?>>
                <label for="user">User</label>
                <?= showError('userRole', $errors) ?>
            </div>

            <div class="inputHandler">
                <label for="registerPassword">Password</label>
                <input type="password" id="registerPassword" name="registerPassword" data-target="registerPassword"
                    value="<?= htmlspecialchars($old['registerPassword'] ?? '') ?>">
                <input type="checkbox" class="showPassword" id="showPassword" data-target="registerPassword">
                <label for="showPassword">Show password</label>
                <?= showError('registerPassword', $errors) ?>
            </div>

            <p>Already have an account? <a href="#" onclick="showForm('loginAccountForm'); return false;">here</a></p>

            <button type="submit" name="RegisterAccount">Register Account</button>

        </form>

    </div>

    <div class="auth-card <?= $activeForm === 'loginAccountForm' ? 'd-block' : 'd-none' ?>" id="loginAccountForm">
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <h1>Login Account</h1>
            <div class="inputHandler">
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="loginEmail"
                    value="<?= htmlspecialchars($old['loginEmail'] ?? '') ?>">
                <?= showError('loginEmail', $errors) ?>
            </div>

            <div class="inputHandler">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="loginPassword" data-target="loginPassword">
                <input type="checkbox" class="showPassword" data-target="loginPassword">
                <label for="showPassword">Show password</label>
                <?= showError('loginPassword', $errors) ?>
            </div>
            <p>Don't have an account? <a href="#" onclick="showForm('registrationOfAccountForm'); return false;">Register Here</a></p>

            <button type="submit" name="loginAccount">login</button><br>
            <a href="verifyEmail.php">forgot password</a>
        </form>
    </div>

    <script src="tanga.js"></script>
</body>

</html>