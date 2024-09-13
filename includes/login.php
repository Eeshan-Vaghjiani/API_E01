<?php
session_start(); // Start the session

// Correct paths for includes
include '../db.php'; // Adjusted path for db.php
include '../structure/User.php'; // Adjusted path for User.php
// Initialize the User class
$user = new User($conn);

// Initialize flash message variables
$flashMessage = '';
$flashMessageTime = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if login form is submitted
    if (isset($_POST['login'])) {
        $user->handleLogin();
    } elseif (isset($_POST['sign_up'])) {
        $user->handleSignUp();
    }
}

// Function to set a flash message
function setFlashMessage($message) {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_message_time'] = time();
}

// Function to get and clear flash messages
function getFlashMessage() {
    if (isset($_SESSION['flash_message']) && isset($_SESSION['flash_message_time'])) {
        $message = $_SESSION['flash_message'];
        $messageTime = $_SESSION['flash_message_time'];
        $currentTime = time();
        
        // Check if the message is older than 10 seconds
        if (($currentTime - $messageTime) < 10) {
            return $message;
        } else {
            // Clear the message if it's older than 10 seconds
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_message_time']);
        }
    }
    return null;
}

// Get the flash message to display if available
$flashMessage = getFlashMessage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tab-content {
            margin-top: 20px;
        }
        .tab-content > div {
            display: none;
        }
        #tab-1:checked ~ .tab-content .sign-in,
        #tab-2:checked ~ .tab-content .sign-up {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">User Authentication</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
                <label for="tab-1" class="tab">Sign In</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up">
                <label for="tab-2" class="tab">Sign Up</label>

                <div class="tab-content">
                    <div class="sign-in">
                        <h3>Sign In</h3>
                        <?php
                        // Display flash message if available
                        if ($flashMessage) {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($flashMessage) . '</div>';
                        }
                        ?>
                        <form action="" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="login_username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="login_username" name="login_username" required>
                                <div class="invalid-feedback">Please enter your username.</div>
                            </div>
                            <div class="mb-3">
                                <label for="login_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="login_password" name="login_password" required>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Sign In</button>
                        </form>
                    </div>
                    <div class="sign-up">
                        <h3>Sign Up</h3>
                        <?php
                        // Display the sign-up form
                        $user->displaySignUpForm();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Bootstrap form validation
        (function() {
            "use strict";
            window.addEventListener("load", function() {
                var forms = document.getElementsByClassName("needs-validation");
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener("submit", function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add("was-validated");
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
