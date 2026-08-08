<?php 
session_start();
include "validateInputsFromUsers.php";


$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
session_unset();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <title>Change Password</title>
</head>
<body>
    <div class="auth-card" id="changePasswordForm">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <h1>CHANGE PASSWORD</h1>
            <label for="newPassword">New password</label>
            <input type="password" id="newPassword" name="newPassword" data-target="newPassword"
            value="<?= htmlspecialchars($old['newPassword'] ?? '') ?>">
            <input type="checkbox" class="showPassword" data-target="newPassword">
            <label for="showPassword">Show password</label>
            <?= showError('newPassword', $errors) ?>
            <br>
            
            <label for="confirmPassword">Confirm password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" data-target="confirmPassword"
            value="<?= htmlspecialchars($old['confirmPassword'] ?? '') ?>">
            <input type="checkbox" class="showPassword" data-target="confirmPassword">
            <label for="showPassword">Show password</label>
            <?= showError('confirmPassword', $errors) ?>
            <br>

            <button type="submit" name="changePassword">Change password</button>
        </form>
    </div>
    

    <script src="tanga.js"></script>
</body>
</html>