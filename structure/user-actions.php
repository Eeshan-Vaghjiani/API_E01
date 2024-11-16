<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            $stmt = $conn->prepare("INSERT INTO tbl_user (fullname, email, username, password, gender_id, role_id) VALUES (?, ?, ?, ?, ?, ?)");
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->execute([
                $_POST['fullname'],
                $_POST['email'],
                $_POST['username'],
                $password,
                $_POST['gender_id'],
                $_POST['role_id']
            ]);
            echo json_encode(['success' => true, 'message' => 'User created successfully']);
            break;

        case 'read':
            $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE user_id = ?");
            $stmt->execute([$_POST['userId']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $user]);
            break;

        case 'update':
            $sql = "UPDATE tbl_user SET fullname = ?, email = ?, username = ?, gender_id = ?, role_id = ?";
            $params = [
                $_POST['fullname'],
                $_POST['email'],
                $_POST['username'],
                $_POST['gender_id'],
                $_POST['role_id'],
            ];

            // Add password to update if provided
            if (!empty($_POST['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $sql .= " WHERE user_id = ?";
            $params[] = $_POST['userId'];

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
            break;

        case 'delete':
            $stmt = $conn->prepare("DELETE FROM tbl_user WHERE user_id = ?");
            $stmt->execute([$_POST['userId']]);
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 