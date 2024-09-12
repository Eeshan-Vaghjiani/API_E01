<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Sign Up</h2>
    <?php
session_start(); // Start the session

// Correct paths for includes
include '../db.php'; // Adjusted path for db.php
include '../structure/User.php'; // Adjusted path for User.php

// Initialize the User class
$user = new User($conn);

// Check for flash messages and set them
$flashMessage = $user->getFlashMessage();
    if ($flashMessage) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($flashMessage) . '</div>';
    }

    // If the form has been submitted, handle the sign-up
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user->handleSignUp();
    }

    // Display the sign-up form
    $user->displaySignUpForm();
    ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
