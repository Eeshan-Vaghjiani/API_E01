<?php
ob_start(); 

require_once __DIR__ . '/../TCPDF/tcpdf.php'; // Include TCPDF library for PDF export
class UserAnalytics {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Analytics Methods
    public function getUserAnalytics() {
        return [
            'total_users' => $this->getTotalUsers(),
            'users_by_gender' => $this->getUsersByGender(),
            'monthly_registrations' => $this->getMonthlyRegistrations(),
            'user_roles_distribution' => $this->getUserRolesDistribution()
        ];
    }

    private function getTotalUsers() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM tbl_user");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    private function getUsersByGender() {
        $stmt = $this->conn->prepare("SELECT gender_id, COUNT(*) as count FROM tbl_user GROUP BY gender_id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMonthlyRegistrations() {
        $stmt = $this->conn->prepare("
            SELECT DATE_FORMAT(created, '%Y-%m') as month, COUNT(*) as count 
            FROM tbl_user 
            GROUP BY DATE_FORMAT(created, '%Y-%m') 
            ORDER BY month DESC 
            LIMIT 12
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getUserRolesDistribution() {
        $stmt = $this->conn->prepare("SELECT role_id, COUNT(*) as count FROM tbl_user GROUP BY role_id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_user ORDER BY user_id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Export Methods
    public function exportToCSV() {
        $users = $this->getAllUsers();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_report.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // For UTF-8 BOM to ensure proper encoding in Excel
        fputcsv($output, ['ID', 'Full Name', 'Email', 'Username', 'Gender', 'Role']);

        foreach ($users as $user) {
            fputcsv($output, [
                $user['user_id'],
                $user['fullname'],
                $user['email'],
                $user['username'],
                $this->getGenderText($user['gender_id']),
                $this->getRoleText($user['role_id'])
            ]);
        }
        fclose($output);
        exit;
    }

    public function exportToPDF() {
        $users = $this->getAllUsers();

        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        $html = '<h3>User Report</h3>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Gender</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($users as $user) {
            $html .= '<tr>
                <td>' . $user['user_id'] . '</td>
                <td>' . $user['fullname'] . '</td>
                <td>' . $user['email'] . '</td>
                <td>' . $user['username'] . '</td>
                <td>' . $this->getGenderText($user['gender_id']) . '</td>
                <td>' . $this->getRoleText($user['role_id']) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('user_report.pdf', 'D');
    }

    public function getGenderText($genderId) {
        switch ($genderId) {
            case 0: return 'Male';
            case 1: return 'Female';
            default: return 'Other';
        }
    }

    public function getRoleText($roleId) {
        switch ($roleId) {
            case 1: return 'Admin';
            case 0: return 'User';
            default: return 'Unknown';
        }
    }

    public function renderDashboard() {
        $analytics = $this->getUserAnalytics();
        
        ob_start();
        ?>
        <!-- Add CSS link with explicit path -->
        <link rel="stylesheet" href="style.css">
        
        <div class="container mt-4">
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h2><?php echo $analytics['total_users']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Export Buttons -->
            <div class="export-buttons mb-4">
                <button class="btn btn-success" onclick="exportToCSV()">Export to CSV</button>
                <button class="btn btn-primary" onclick="exportToPDF()">Export to PDF</button>
            </div>
    
            <!-- Charts -->
            <!-- Users by Gender Chart -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Users by Gender</h5>
                        <canvas id="userGenderChart" style="max-height: 300px;"></canvas> <!-- Fixed height for chart -->
                    </div>
                </div>
            </div>

            <!-- Monthly Registrations Chart -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Registrations</h5>
                        <canvas id="registrationsChart" style="max-height: 300px;"></canvas> <!-- Fixed height for chart -->
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User List</h5>
                    <?php echo $this->renderUsersTable(); ?>
                </div>
            </div>
        </div>
    
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            // Gender Distribution Chart
            new Chart(document.getElementById('userGenderChart'), {
                type: 'pie',
                data: {
                    labels: ['Male', 'Female', 'Other'],
                    datasets: [{
                        data: [
                            <?php 
                                echo isset($analytics['users_by_gender'][0]) ? $analytics['users_by_gender'][0]['count'] : 0; // Male
                                echo ','; 
                                echo isset($analytics['users_by_gender'][1]) ? $analytics['users_by_gender'][1]['count'] : 0; // Female
                                echo ','; 
                                echo isset($analytics['users_by_gender'][2]) ? $analytics['users_by_gender'][2]['count'] : 0; // Other
                            ?>
                        ],
                        backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            // Monthly Registrations Chart
            new Chart(document.getElementById('registrationsChart'), {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_column($analytics['monthly_registrations'], 'month')); ?>,
                    datasets: [{
                        label: 'New Users',
                        data: <?php echo json_encode(array_column($analytics['monthly_registrations'], 'count')); ?>,
                        borderColor: '#36A2EB',
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        }
                    }
                }
            });

            // Export functions
            function exportToCSV() {
                window.location.href = '?export=csv';
            }

            function exportToPDF() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.setFontSize(16);
                doc.text('Dashboard Report', 10, 10);

                doc.setFontSize(12);
                doc.text('Total Users: <?php echo $analytics['total_users']; ?>', 10, 20);

                doc.text('Export Date: ' + new Date().toLocaleDateString(), 10, 30);

                doc.save('dashboard_report.pdf');
            }
        </script>
        <?php
        return ob_get_clean();
    }

    private function renderUsersTable() {
        $users = $this->getAllUsers();
        $html = '<div class="table-responsive">
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
                <tbody>';
        foreach ($users as $user) {
            $html .= sprintf('
                <tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>
                        <button onclick="editUser(%s)" class="btn btn-primary btn-sm">Edit</button>
                        <button onclick="deleteUser(%s)" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>',
                htmlspecialchars($user['user_id']),
                htmlspecialchars($user['fullname']),
                htmlspecialchars($user['email']),
                htmlspecialchars($user['username']),
                htmlspecialchars($this->getGenderText($user['gender_id'])),
                htmlspecialchars($this->getRoleText($user['role_id'])),
                $user['user_id'],
                $user['user_id']
            );
        }
        $html .= '</tbody></table>
            <button onclick="showAddUserModal()" class="btn btn-success">Add New User</button>
        </div>';
        
        // Add User Modal
        $html .= '
        <div class="modal fade" id="userModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add/Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
        </div>';
        
        return $html;
    }
}

// Usage example (database connection $conn should be defined earlier)
$analytics = new UserAnalytics($conn);

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    $analytics->exportToCSV();
    exit;
}

// Handle PDF export
if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    $analytics->exportToPDF();
    exit;
}

?>
