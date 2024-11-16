<?php
 require_once "../load.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db.php';
require_once "UserAnalytics.php";
$ObjMenus->main_menu();  
// Check if user is logged in
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Modal specific styles to prevent flickering */
        .modal {
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-backdrop {
            display: none;
        }
        
        .modal.show {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .modal-dialog {
            transform: none;
            transition: transform 0.3s ease-out;
        }
        
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, 0);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Ensure modal content doesn't shift */
        .modal-content {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        /* Form control consistent styling */
        .form-control:focus {
            box-shadow: none;
            border-color: #80bdff;
        }

        /* Button hover states */
        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <?php
    try {
        $analytics = new UserAnalytics($conn);
    ?>
        <div class="container mt-4">
            <h2>User Analytics Dashboard</h2>

            <!-- Analytics Charts -->
            <?php echo $analytics->renderDashboard(); ?>

            

            <!-- User Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add/Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="userForm">
                                <input type="hidden" id="userId" name="userId">
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
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-control" id="gender" name="gender_id" required>
                                        <option value="0">Male</option>
                                        <option value="1">Female</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-control" id="role" name="role_id" required>
                                        <option value="0">User</option>
                                        <option value="1">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <small class="text-muted">Leave empty to keep existing password when editing</small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="saveUser()">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Your existing scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            let userModal;
            
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Bootstrap modal with specific options
                userModal = new bootstrap.Modal(document.getElementById('userModal'), {
                    backdrop: 'static', // Prevents closing when clicking outside
                    keyboard: false     // Prevents closing with keyboard
                });

                // Prevent modal from closing when clicking outside
                document.getElementById('userModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });

                // Smooth transition for modal
                document.getElementById('userModal').addEventListener('show.bs.modal', function () {
                    this.style.display = 'block';
                });

                // Clean up after modal is hidden
                document.getElementById('userModal').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('userForm').reset();
                });
            });

            function showAddUserModal() {
                document.getElementById('userForm').reset();
                document.getElementById('userId').value = '';
                document.getElementById('modalTitle').textContent = 'Add New User';
                userModal.show();
            }

            function editUser(userId) {
                fetch('user-actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=read&userId=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;
                        document.getElementById('userId').value = user.user_id;
                        document.getElementById('fullname').value = user.fullname;
                        document.getElementById('email').value = user.email;
                        document.getElementById('username').value = user.username;
                        document.getElementById('gender').value = user.gender_id;
                        document.getElementById('role').value = user.role_id;
                        document.getElementById('password').value = '';
                        document.getElementById('modalTitle').textContent = 'Edit User';
                        userModal.show();
                    }
                });
            }

            function saveUser() {
                const form = document.getElementById('userForm');
                const formData = new FormData(form);
                const userId = document.getElementById('userId').value;
                
                formData.append('action', userId ? 'update' : 'create');
                
                fetch('user-actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }

            function deleteUser(userId) {
                if (confirm('Are you sure you want to delete this user?')) {
                    fetch('user-actions.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete&userId=${userId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                }
            }

            // Your existing export functions
            function exportToPDF() {
                window.location.href = '?export=pdf';
            }

            function exportToCSV() {
                window.location.href = '?export=csv';
            }
        </script>

        <!-- Your existing styles -->
        <style>
            .card {
                margin-bottom: 20px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .card-title {
                color: #333;
                font-weight: bold;
                margin-bottom: 20px;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0,0,0,.05);
            }

            .btn-sm {
                padding: .25rem .5rem;
                font-size: .875rem;
                line-height: 1.5;
                border-radius: .2rem;
            }

            .analytics-card {
                background-color: #fff;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
            }

            .chart-container {
                position: relative;
                height: 500px;
                margin-bottom: 20px;
            }

            .user-table {
                margin-top: 20px;
            }

            .export-buttons {
                margin-bottom: 15px;
            }

            .export-buttons .btn {
                margin-right: 10px;
            }
        </style>
    <?php
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error loading analytics: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    ?>
</body>
</html> 