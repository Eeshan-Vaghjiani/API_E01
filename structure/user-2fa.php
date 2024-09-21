<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Verify the 2FA code entered by the user
    public function verify2FACode() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_2fa'])) {
            $inputCode = $_POST['2fa_code'] ?? '';

            // Get the stored 2FA code from the session or database
            $userId = $_SESSION['user_id'];
            $query = "SELECT two_factor_code FROM users WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $storedCode = $result['two_factor_code'];

            if ($inputCode == $storedCode) {
                // Correct 2FA code, authenticate the user
                $_SESSION['authenticated'] = true;

                // Clear the 2FA code from the database
                $query = "UPDATE users SET two_factor_code = NULL WHERE user_id = :user_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->execute();

                // Redirect to the index page
                header('Location: index.php');
                exit();
            } else {
                $this->setFlashMessage('Invalid 2FA code! Please try again.');
            }
        }
    }

    // Set flash messages
    private function setFlashMessage($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_time'] = time();
    }

    // Display the 2FA verification form
    public function display2FAForm() {
        echo '
        <div class="container">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="2fa_code" class="form-label">Enter 2FA Code</label>
                    <input type="text" class="form-control" id="2fa_code" name="2fa_code" required>
                </div>
                <button type="submit" name="verify_2fa" class="btn btn-primary">Verify</button>
            </form>
        </div>';
    }
}

$user = new User($conn);
$user->verify2FACode();
$user->display2FAForm();
