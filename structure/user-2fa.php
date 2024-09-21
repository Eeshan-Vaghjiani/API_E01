<?php

// Include necessary files
require '../db.php'; // Adjust the path as necessary
require '../includes/EmailService.php'; // Adjust the path as necessary
require '../structure/user.php'; // Adjust the path as necessary

$user = new User($conn);
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_2fa'])) {
    $errorMessage = $user->verify2FACode(); // Capture any error messages from verification
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>2FA Verification</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">2FA Verification</h2>
        <?php if ($flashMessage = $user->getFlashMessage()): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $flashMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="2fa_code" class="form-label">Enter 2FA Code</label>
                <input type="text" class="form-control" id="code" name="code" required>
                <div class="invalid-feedback">Please enter your 2FA code.</div>
            </div>
            <button type="submit" name="verify_2fa" class="btn btn-primary">Verify</button>
            <button type="submit" name="resend_code" class="btn btn-secondary">Resend Code</button>
        </form>
    </div>

    <?php
    // Handle resend code functionality
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resend_code'])) {
        $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
        $email = $user->getUserEmail($userId); // Create this method to get the user email
        $user->generate2FACode($userId, $email); // Regenerate and send the code
        $user->setFlashMessage('A new 2FA code has been sent to your email.');
        header('Location: user-2fa.php'); // Redirect to refresh the page
        exit();
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
