<?php
class Admin {
    private $conn;
    public $itemsPerPage = 10; // Number of items per page

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Fetch users for the current page
    public function fetchUsers($page) {
        $offset = ($page - 1) * $this->itemsPerPage;

        // Ensure that the SQL query only selects columns that actually exist
        $query = "SELECT user_id, fullname, email, username, gender_id FROM users LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindValue(':limit', $this->itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to fetch users: ' . $e->getMessage());
            return [];
        }
    }

    // Get total number of users
    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to count users: ' . $e->getMessage());
            return 0;
        }
    }

    // Set a flash message
    private function setFlashMessage($message) {
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
        return null;
    }

    // Add a new user
    public function addUser($fullName, $email, $username, $password, $genderId) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (fullname, email, username, password, gender_id) VALUES (:fullname, :email, :username, :password, :gender_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':fullname', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':gender_id', $genderId, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $this->setFlashMessage('User added successfully!');
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to add user: ' . $e->getMessage());
        }
    }

    // Delete a user
    public function deleteUser($userId) {
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $this->setFlashMessage('User deleted successfully!');
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to delete user: ' . $e->getMessage());
        }
    }

    // Update user details
    public function updateUser($userId, $fullName, $email, $username, $password, $genderId) {
        $query = "UPDATE users SET fullname = :fullname, email = :email, username = :username, gender_id = :gender_id";
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query .= ", password = :password";
        }
        $query .= " WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':fullname', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':gender_id', $genderId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        if (!empty($password)) {
            $stmt->bindParam(':password', $hashedPassword);
        }

        try {
            $stmt->execute();
            $this->setFlashMessage('User updated successfully!');
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to update user: ' . $e->getMessage());
        }
    }

    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }
}
?>
