<?php
session_start();

// Include necessary files
include '../db.php';
include '../structure/User.php';

// Initialize User class
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the OTP form is submitted
    if (isset($_POST['otp'])) {
        $inputOTP = $_POST['otp'];

        // Verify OTP
        if ($user->verifyOTP($inputOTP)) {
            // OTP is correct, proceed to user dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $flashMessage = "Invalid OTP. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Verify OTP</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php
                if (isset($flashMessage)) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($flashMessage) . '</div>';
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="otp" class="form-label">Enter OTP</label>
                        <input type="text" class="form-control" id="otp" name="otp" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Verify OTP</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
