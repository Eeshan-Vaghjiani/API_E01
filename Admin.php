<?php
require_once __DIR__ . '/structure/Admin-class.php';
include 'db.php'; 
include 'structure/User.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Instantiate Admin class
$admin = new Admin($conn);

// Pagination logic
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalUsers = $admin->getTotalUsers();
$itemsPerPage = $admin->itemsPerPage;
$totalPages = ceil($totalUsers / $itemsPerPage);
$users = $admin->fetchUsers($currentPage);

// Handling form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addUser'])) {
        $admin->addUser($_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['gender_id'], $_POST['role_id']);
    } elseif (isset($_POST['updateUser'])) {
        $admin->updateUser($_POST['user_id'], $_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['gender_id'], $_POST['role_id']);
    } elseif (isset($_POST['deleteUser'])) {
        $admin->deleteUser($_POST['user_id']);
    }
}

// Display flash messages, if any
if (isset($_SESSION['flash_message'])) {
    echo "<div class='alert alert-success'>{$_SESSION['flash_message']}</div>";
    unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-4">Admin Panel</h1>

    <!-- User Table -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Gender-ID</th>
            <th>Role-ID</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['user_id'] ?></td>
                <td><?= $user['fullname'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['gender_id'] ?></td>
                <td><?= $user['role_id'] ?></td>
                <td>
                    <form action="" method="post" style="display:inline-block;">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                        <button type="submit" name="deleteUser" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal-<?= $user['user_id'] ?>">Edit</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <nav>
        <ul class="pagination">
            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                <li class="page-item <?= $page == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <!-- Add User Form -->
    <h2>Add User</h2>
    <form action="" method="post">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="gender_id" class="form-label">Gender</label>
            <select class="form-select" id="gender_id" name="gender_id" required>
                <?php
                $genders = $conn->query("SELECT gender_id, gender_name FROM genders")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($genders as $gender) {
                    echo "<option value='{$gender['gender_id']}'>{$gender['gender_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select class="form-select" id="role_id" name="role_id" required>
                <?php
                $roles = $conn->query("SELECT role_id, role_name FROM roles")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($roles as $role) {
                    echo "<option value='{$role['role_id']}'>{$role['role_name']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" name="addUser" class="btn btn-primary">Add User</button>
    </form>

    <!-- Update User Modals -->
    <?php foreach ($users as $user): ?>
        <div class="modal fade" id="updateModal-<?= $user['user_id'] ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Update User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <div class="mb-3">
                                <label for="updateFullname-<?= $user['user_id'] ?>" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="updateFullname-<?= $user['user_id'] ?>" name="fullname" value="<?= $user['fullname'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateEmail-<?= $user['user_id'] ?>" class="form-label">Email</label>
                                <input type="email" class="form-control" id="updateEmail-<?= $user['user_id'] ?>" name="email" value="<?= $user['email'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateUsername-<?= $user['user_id'] ?>" class="form-label">Username</label>
                                <input type="text" class="form-control" id="updateUsername-<?= $user['user_id'] ?>" name="username" value="<?= $user['username'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateGender-<?= $user['user_id'] ?>" class="form-label">Gender</label>
                                <select class="form-select" id="updateGender-<?= $user['user_id'] ?>" name="gender_id" required>
                                    <?php foreach ($genders as $gender): ?>
                                        <option value="<?= $gender['gender_id'] ?>" <?= $gender['gender_id'] == $user['gender_id'] ? 'selected' : '' ?>>
                                            <?= $gender['gender_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="updateRole-<?= $user['user_id'] ?>" class="form-label">Role</label>
                                <select class="form-select" id="updateRole-<?= $user['user_id'] ?>" name="role_id" required>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['role_id'] ?>" <?= $role['role_id'] == $user['role_id'] ? 'selected' : '' ?>>
                                            <?= $role['role_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="updateUser" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
