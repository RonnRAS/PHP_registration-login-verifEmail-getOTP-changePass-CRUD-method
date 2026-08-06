<?php
session_start();
include "validateInputsFromUsers.php";

$errors = $_SESSION['errors'] ?? [];
$activeForm = $_SESSION['active_form'] ?? 'registrationOfAccountForm';
$old = $_SESSION['old'] ?? [];
session_unset();

function showError($field, $errors)
{

    if (!empty($errors[$field])) {
        return "<p class='errorHandler' style='color:red;'>" . htmlspecialchars($errors[$field]) . "</p>";
    } else {
        return "<p class='errorHandler'></p>";
    }
}



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
                    value="<?= htmlspecialchars($old['middleName'] ?? '') ?>">
                <input type="checkbox" class="NoMiddleName">
                <label for="NoMiddleName">No middle name</label>
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
                <input type="radio" id="admin" name="userRole" value="admin"
                    <?= ($old['userRole'] ??  '') === 'admin' ? 'checked' : '' ?>>
                <label for="admin">Admin</label>
                <input type="radio" id="user" name="userRole" value="user">
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
                <input type="email" id="loginEmail" name="loginEmail">
                <p class="errorHandler"></p>
            </div>

            <div class="inputHandler">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="loginPassword" data-target="loginPassword">
                <input type="checkbox" class="showPassword" data-target="loginPassword">
                <label for="showPassword">Show password</label>
                <p class="errorHandler"></p>
            </div>
            <p>Don't have an account? <a href="#" onclick="showForm('registrationOfAccountForm'); return false;">Register Here</a></p>

            <button type="submit" name="loginAccount">login</button>
        </form>
    </div>

    <script src="script.js">
        alert("JavaScript is working!");
    </script>
</body>

</html>