<?php
require_once '../structure/Admin-class.php'; // Include the Admin class

// Database connection
include '../db.php'; // Adjusted path for db.php
include '../structure/User.php'; // Adjusted path for User.php

$admin = new Admin($conn);

// Pagination logic
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalUsers = $admin->getTotalUsers();
$totalPages = ceil($totalUsers / $admin->itemsPerPage);
$users = $admin->fetchUsers($currentPage);

// Handling form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addUser'])) {
        // Add user form submission
        $admin->addUser($_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['gender_id'], $_POST['role_id']);
    } elseif (isset($_POST['updateUser'])) {
        // Update user form submission
        $admin->updateUser($_POST['user_id'], $_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['gender_id'], $_POST['role_id']);
    } elseif (isset($_POST['deleteUser'])) {
        // Delete user form submission
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
            <th>Gender</th>
            <th>Role</th>
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
                <td><?= $user['gender_name'] ?></td>
                <td><?= $user['role_name'] ?></td>
                <td>
                    <!-- Update and Delete Buttons -->
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
                <!-- Populate options dynamically from the database -->
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
                <!-- Populate options dynamically from the database -->
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
        <!-- Update Modal -->
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

<?php
include '../db.php'; // Database connection
include '../structure/User.php'; // User class

session_start();

// Instantiate User class
$user = new User($conn);

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $user->handleAddUser();
    } elseif (isset($_POST['update_user'])) {
        $user->handleUpdateUser();
    } elseif (isset($_POST['delete_user'])) {
        $user->handleDeleteUser();
    }
}

// Get flash message
$flashMessage = $user->getFlashMessage();

// Display users
$users = $user->getUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="my-4">Admin Dashboard</h1>
    
    <!-- Flash message -->
    <?php if ($flashMessage): ?>
        <div class="alert alert-warning">
            <?php echo htmlspecialchars($flashMessage); ?>
        </div>
    <?php endif; ?>

    <!-- Add User Form -->
    <div class="mb-4">
        <h3>Add User</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="">Select...</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
        </form>
    </div>

    <!-- User Table with Pagination -->
    <h3>User List</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['gender_id']); ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateUserModal" data-id="<?php echo htmlspecialchars($user['user_id']); ?>">Edit</button>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php
            // Example pagination logic (implement according to your needs)
            $totalPages = 10; // Total pages (replace with actual logic)
            for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Update User Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="hidden" id="update_user_id" name="user_id">
                    <div class="form-group">
                        <label for="update_full_name">Full Name</label>
                        <input type="text" class="form-control" id="update_full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="update_email">Email</label>
                        <input type="email" class="form-control" id="update_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="update_username">Username</label>
                        <input type="text" class="form-control" id="update_username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="update_password">Password</label>
                        <input type="password" class="form-control" id="update_password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="update_gender">Gender</label>
                        <select class="form-control" id="update_gender" name="gender" required>
                            <option value="">Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Populate the update user modal with user data
    $('#updateUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var userId = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        
        // Load user data and populate the form (this part should be implemented as per your requirements)
        $.ajax({
            url: 'get_user.php',
            method: 'POST',
            data: { user_id: userId },
            success: function(response) {
                var user = JSON.parse(response);
                modal.find('#update_user_id').val(user.user_id);
                modal.find('#update_full_name').val(user.fullname);
                modal.find('#update_email').val(user.email);
                modal.find('#update_username').val(user.username);
                modal.find('#update_gender').val(user.gender_id);
            }
        });
    });
</script>
</body>
</html>
