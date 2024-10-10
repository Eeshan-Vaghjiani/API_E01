<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Start the session at the beginning
require_once '../includes/EmailService.php';
class User {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
        // Session_start() removed here as it's already called at the top
    }

    // Method to generate and display the sign-up form
    public function displaySignUpForm() {
        $flashMessage = $this->getFlashMessage();

        echo '
        <div class="container">';
        
        // Display flash message
        if ($flashMessage) {
            echo '
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($flashMessage) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        echo '
        
        <form action="" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="full_name" required>
                <div class="invalid-feedback">Please enter your full name.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <div class="invalid-feedback">Please enter a username.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="invalid-feedback">Please enter a password.</div>
            </div>
            <div class="mb-3">
                <label for="passwordConfirm" class="form-label">Re-enter Password</label>
                <input type="password" class="form-control" id="passwordConfirm" name="password_confirm" required>
                <div class="invalid-feedback">Please confirm your password.</div>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="">Choose...</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <div class="invalid-feedback">Please select your gender.</div>
            </div>
            <button type="submit" name="sign_up" class="btn btn-primary">Sign Up</button>
        </form>
        
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
        </script>';
    }

    // Method to handle the form submission
    public function handleSignUp() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect form data
            $fullName = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $gender = $_POST['gender'] ?? '';
    
            // Validate password confirmation
            if ($password !== $passwordConfirm) {
                $this->setFlashMessage('Passwords do not match!');
                return false;
            }
    
            // Convert gender to gender ID
            $genderId = $this->convertGenderToId($gender);
    
            // Default role ID is 0
            $roleId = 0;
    
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
            // Insert the user into the database
            $query = "INSERT INTO users (fullname, email, username, password, gender_id, role_id) VALUES (:fullname, :email, :username, :password, :gender_id, :role_id)";
            $stmt = $this->conn->prepare($query);
    
            // Bind parameters
            $stmt->bindParam(':fullname', $fullName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':gender_id', $genderId, PDO::PARAM_INT);
            $stmt->bindParam(':role_id', $roleId, PDO::PARAM_INT);
    
            try {
                $stmt->execute();
                // Redirect to index.php after successful registration
                header('Location: ../index.php');
                $emailService = new EmailService();
                $emailService->sendRegistrationEmail($email, $username);
                exit(); // Ensure no further code is executed after redirection
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Handle duplicate entry (email/username exists)
                    $this->setFlashMessage('Username or email already exists!');
                } else {
                    $this->setFlashMessage('Failed to register user: ' . $e->getMessage());
                }
                return false;
            }
        }
    }
    
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            // Collect login form data
            $username = $_POST['login_username'] ?? '';
            $password = $_POST['login_password'] ?? '';
    
            if (empty($username) || empty($password)) {
                $this->setFlashMessage('Username and password cannot be empty.');
                return;
            }
    
            // Query to find the user
            $query = "SELECT user_id, password, email, role_id FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
    
            try {
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($user) {
                    // User found, now verify password
                    if (password_verify($password, $user['password'])) {
                        // Password is correct, initiate 2FA
                        $this->generate2FACode($user['user_id'], $user['email']);
                        $_SESSION['user_id'] = $user['user_id']; // Store user ID in session for later use
                        $_SESSION['email'] = $user['email']; // Store email in session to avoid issues with wrong emails being used
                        header('Location: ../structure/user-2fa.php'); // Redirect to 2FA verification page
                        exit();
                    } else {
                        // Password doesn't match
                        $this->setFlashMessage('Invalid password!');
                    }
                } else {
                    // No user found
                    $this->setFlashMessage('Invalid username or password!');
                }
            } catch (PDOException $e) {
                // Handle any errors with the query
                $this->setFlashMessage('Database error: ' . $e->getMessage());
            }
        }
    }
    
    public function getUserEmail($userId) {
        $query = "SELECT email FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['email'];
    }
    
    public function verify2FACode() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_2fa'])) {
            $inputCode = $_POST['code'] ?? '';
            $userId = $_SESSION['user_id'];
    
            // Adjust the query to select the correct column
            $query = "SELECT code FROM users WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $storedCode = $result['code'];
    
            if ($inputCode == $storedCode) {
                // Clear the code and log in the user
                $_SESSION['authenticated'] = true;
                $query = "UPDATE users SET code = NULL WHERE user_id = :user_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->execute();
                $_SESSION['loggedin'] = true;
                header('Location: ../index.php'); // Redirect to the index page
                exit();
            } else {
                return 'Invalid 2FA code!'; // Return error message for display
            }
        }
        return ''; // Return an empty string if no error
    }
    
    // Generate and send a 2FA code via email
    public function generate2FACode($userId, $email) {
        $code = rand(100000, 999999); // Generate a 6-digit random code
    
        // Fetch the username
        $query = "SELECT username FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $result['username']; // Get the username
    
        // Store the code in the database
        $query = "UPDATE users SET code = :code WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    
        // Send the email with the username
        $emailService = new EmailService();
        $emailService->send2FACode($email, $username, $code);
    }
    
    

    // Convert gender to ID
    private function convertGenderToId($gender) {
        switch ($gender) {
            case 'Male':
                return 0;
            case 'Female':
                return 1;
            default:
                return 2;
        }
    }

    // Set a flash message
    public function setFlashMessage($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_time'] = time();
    }

    // Get and clear flash messages
    public function getFlashMessage() {
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
        unset($_SESSION['flash_message']);
        return null;
    }
    // Get all users with pagination
public function getUsers() {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $query = "SELECT * FROM users LIMIT :limit OFFSET :offset";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle add user
public function handleAddUser() {
    if (isset($_POST['full_name'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['gender'])) {
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $gender = $_POST['gender'];

        $query = "INSERT INTO users (fullname, email, username, password, gender_id) VALUES (:fullname, :email, :username, :password, :gender)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullname', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute()) {
            $this->setFlashMessage('User added successfully.');
        } else {
            $this->setFlashMessage('Failed to add user.');
        }
    }
}

// Handle update user
public function handleUpdateUser() {
    if (isset($_POST['user_id'], $_POST['full_name'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['gender'])) {
        $userId = $_POST['user_id'];
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $gender = $_POST['gender'];

        $query = "UPDATE users SET fullname = :fullname, email = :email, username = :username, password = :password, gender_id = :gender WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullname', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        if ($password) {
            $stmt->bindParam(':password', $password);
        }
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) {
            $this->setFlashMessage('User updated successfully.');
        } else {
            $this->setFlashMessage('Failed to update user.');
        }
    }
}

// Handle delete user
public function handleDeleteUser() {
    if (isset($_POST['user_id'])) {
        $userId = $_POST['user_id'];

        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) {
            $this->setFlashMessage('User deleted successfully.');
        } else {
            $this->setFlashMessage('Failed to delete user.');
        }
    }
}

// Get user details for update modal
public function getUser($userId) {
    $query = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>
