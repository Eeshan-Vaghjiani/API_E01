<?php
class Admin {
    private $conn;
    public $itemsPerPage = 10;

    public function __construct($conn) {
        $this->conn = $conn;
    }

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

    public function fetchUsers($page) {
        $offset = ($page - 1) * $this->itemsPerPage;
        $query = "SELECT user_id, fullname, email, username, gender_id,role_id FROM users LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
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
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to add user: ' . $e->getMessage());
        }
    }

    public function deleteUser($userId) {
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $this->setFlashMessage('User deleted successfully!');
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to delete user: ' . $e->getMessage());
        }
    }

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
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $this->setFlashMessage('Failed to update user: ' . $e->getMessage());
        }
    }

    private function setFlashMessage($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_time'] = time();
    }

    public function getFlashMessage() {
        if (isset($_SESSION['flash_message']) && isset($_SESSION['flash_message_time'])) {
            $message = $_SESSION['flash_message'];
            $messageTime = $_SESSION['flash_message_time'];
            if ((time() - $messageTime) < 10) {
                return $message;
            } else {
                unset($_SESSION['flash_message']);
                unset($_SESSION['flash_message_time']);
            }
        }
        return null;
    }

    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }
}
?>
