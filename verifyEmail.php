<?php 

session_start();
include "validateInputsFromUsers.php";

$errors = $_SESSION['errors'] ?? [];
$activeForm = $_SESSION['active_form'] ?? 'verifyEmailAccount';
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
    <title>Verify Email</title>
</head>
<body>
    <div class="auth-card   <?= $activeForm === 'verifyEmailAccount' ? 'd-block' : 'd-none' ?>">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
            <h1>Find Your Account</h1>

            <p>Remember your password? <a href="index.php">login here</a></p>

            <label for="verifyEmail">Email</label>
            <input type="email" id="verifyEmail" name="verifyEmail" 
            value="<?= htmlspecialchars($old['verifyEmail'] ?? '') ?>"><br>
            <?= showError('verifyEmail', $errors) ?>

            <button type="submit" name="verifyEmailAccount">Verify Email</button>
        </form>
    </div>

    <div class="auth-card <?= $activeForm === 'otpVerifyForm' ? 'd-block' : 'd-none' ?>">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <h1>Email verification</h1>
            <p>We've e-mailed you a 6 digit code. Please check your e-mail and enter the code here to complete the verification.</p>
                <!--email -->
                <input type="text" placeholder="••••••" name="otpCode">

                <button type="submit" name="verifyOtpCode">verify</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>