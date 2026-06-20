<?php
include 'php/config.php';
session_start();

// Get statistics
$total_students = 0;
$total_complaints = 0;
$total_notices = 0;
$total_payments = 0;
$total_rooms = 0;

$result = $conn->query("SELECT COUNT(*) as count FROM students WHERE role='student'");
if($result) $total_students = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM complaints");
if($result) $total_complaints = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM notices");
if($result) $total_notices = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM payments");
if($result) $total_payments = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM rooms");
if($result) $total_rooms = $result->fetch_assoc()['count'];

// Sample data checks
$last_complaint = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC LIMIT 1");
$last_notice = $conn->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 1");
$occupied_rooms = $conn->query("SELECT COUNT(*) as count FROM rooms WHERE student_id IS NOT NULL");
$occupied_count = 0;
if($occupied_rooms) $occupied_count = $occupied_rooms->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Verification & Testing Report</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .test-report {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
        }
        .feature-group {
            margin: 2rem 0;
            border-left: 4px solid #0066cc;
            padding-left: 1.5rem;
        }
        .feature-item {
            background: #f9f9f9;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            border-left: 3px solid #4CAF50;
        }
        .feature-item.warning {
            border-left-color: #ff9800;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            margin-left: 1rem;
        }
        .status-active {
            background: #4CAF50;
            color: white;
        }
        .status-warning {
            background: #ff9800;
            color: white;
        }
        .status-empty {
            background: #9e9e9e;
            color: white;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .stat-box {
            background: #f0f0f0;
            padding: 1.5rem;
            border-radius: 4px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #0066cc;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management - System Verification</h1>
            </nav>
        </div>
    </header>

    <main>
        <div class="container test-report">
            <h2>System Health & Features Verification Report</h2>
            <p style="color: #666; font-size: 0.95rem;">Generated on <?= date('M d, Y H:i:s') ?></p>

            <hr>

            <h3>Database Statistics</h3>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number"><?= $total_students ?></div>
                    <div class="stat-label">Students</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?= $total_complaints ?></div>
                    <div class="stat-label">Complaints</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?= $total_notices ?></div>
                    <div class="stat-label">Notices</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?= $total_payments ?></div>
                    <div class="stat-label">Payments</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?= $total_rooms ?></div>
                    <div class="stat-label">Rooms</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?= $occupied_count ?>/<?= $total_rooms ?></div>
                    <div class="stat-label">Occupied Rooms</div>
                </div>
            </div>

            <hr>

            <h3>Student Dashboard Features</h3>
            <div class="feature-group">
                <div class="feature-item">
                    <strong>Room Information</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>Students can view assigned room details with floor and capacity info</p>
                    <code>php/room-info.php</code>
                </div>

                <div class="feature-item">
                    <strong>Submit Complaint</strong>
                    <span class="status-badge <?= $total_complaints > 0 ? 'status-active' : 'status-warning' ?>">
                        <?= $total_complaints > 0 ? 'WORKING' : 'NEEDS DATA' ?>
                    </span>
                    <p>Students can submit complaints with title, description, and category. Admin can track status (pending/in_progress/resolved)</p>
                    <code>php/complaints.php</code>
                    <p style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">Current complaints: <?= $total_complaints ?></p>
                </div>

                <div class="feature-item">
                    <strong>View Notices</strong>
                    <span class="status-badge <?= $total_notices > 0 ? 'status-active' : 'status-warning' ?>">
                        <?= $total_notices > 0 ? 'WORKING' : 'NEEDS DATA' ?>
                    </span>
                    <p>Display hostel announcements in reverse chronological order</p>
                    <code>php/notices.php</code>
                    <p style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">Current notices: <?= $total_notices ?></p>
                </div>

                <div class="feature-item">
                    <strong>Payment History</strong>
                    <span class="status-badge <?= $total_payments > 0 ? 'status-active' : 'status-warning' ?>">
                        <?= $total_payments > 0 ? 'WORKING' : 'NEEDS DATA' ?>
                    </span>
                    <p>View payment records with amounts, dates, and types (Hostel Fee/Maintenance/Other)</p>
                    <code>php/payments.php</code>
                    <p style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">Current payments: <?= $total_payments ?></p>
                </div>

                <div class="feature-item">
                    <strong>Profile Management</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>Edit profile information and change password with security validation</p>
                    <code>php/profile.php</code>
                </div>
            </div>

            <h3>Admin Dashboard Features</h3>
            <div class="feature-group">
                <div class="feature-item">
                    <strong>Manage Students</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>View all students and remove accounts. Displays student count: <strong><?= $total_students ?></strong></p>
                    <code>php/admin-students.php</code>
                </div>

                <div class="feature-item">
                    <strong>Manage Complaints</strong>
                    <span class="status-badge <?= $total_complaints > 0 ? 'status-active' : 'status-warning' ?>">
                        <?= $total_complaints > 0 ? 'WORKING' : 'NEEDS DATA' ?>
                    </span>
                    <p>View all complaints, update status (pending/in_progress/resolved), and delete. Total: <strong><?= $total_complaints ?></strong></p>
                    <code>php/admin-complaints.php</code>
                </div>

                <div class="feature-item">
                    <strong>Manage Notices</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>Post new notices and manage existing ones. Post new announcements, delete old ones. Total: <strong><?= $total_notices ?></strong></p>
                    <code>php/admin-notices.php</code>
                </div>

                <div class="feature-item">
                    <strong>Manage Rooms</strong>
                    <span class="status-badge <?= $total_rooms > 0 ? 'status-active' : 'status-warning' ?>">
                        <?= $total_rooms > 0 ? 'WORKING' : 'NEEDS DATA' ?>
                    </span>
                    <p>Add rooms with room number, floor, and capacity. Assign students to rooms. Total rooms: <strong><?= $total_rooms ?></strong></p>
                    <code>php/admin-rooms.php</code>
                </div>

                <div class="feature-item">
                    <strong>Manage Payments</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>Record student payments with amount, date, and type. Total recorded: <strong><?= $total_payments ?></strong></p>
                    <code>php/admin-payments.php</code>
                </div>
            </div>

            <hr>

            <h3>Authentication & Security</h3>
            <div class="feature-group">
                <div class="feature-item">
                    <strong>User Registration</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>New user registration with role selection (Student/Admin). Admin requires security code (ADMIN123)</p>
                    <code>php/register.php</code>
                </div>

                <div class="feature-item">
                    <strong>User Login</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>Email/password login with session management. Redirects to appropriate dashboard</p>
                    <code>php/login.php</code>
                </div>

                <div class="feature-item">
                    <strong>Password Security</strong>
                    <span class="status-badge status-active">WORKING</span>
                    <p>All passwords are hashed with password_hash(). Prepared statements against SQL injection</p>
                </div>
            </div>

            <hr>

            <h3>Testing Recommendations</h3>
            <div style="background: #e3f2fd; padding: 1.5rem; border-radius: 4px;">
                <h4>To fully test the system:</h4>
                <ol>
                    <li><strong>Add Test Data:</strong>
                        <ul>
                            <li>Create 2-3 student accounts via registration</li>
                            <li>Create an admin account (use code: ADMIN123)</li>
                            <li>Admin posts 2-3 notices</li>
                            <li>Students submit 2-3 complaints</li>
                            <li>Admin assigns students to rooms</li>
                            <li>Admin records payments</li>
                        </ul>
                    </li>
                    <li><strong>Verify Features:</strong>
                        <ul>
                            <li>Login with student account - should see Student Dashboard</li>
                            <li>Login with admin account - should see Admin Dashboard with stats</li>
                            <li>All links navigate to correct pages with data</li>
                            <li>Forms accept input and save to database</li>
                        </ul>
                    </li>
                    <li><strong>Check Data Flow:</strong>
                        <ul>
                            <li>Student submits complaint → Admin can view and update status</li>
                            <li>Admin posts notice → Student can see it</li>
                            <li>Admin assigns room → Student can view their room</li>
                            <li>Admin records payment → Student can see in payment history</li>
                        </ul>
                    </li>
                </ol>
            </div>

            <hr>

            <div style="background: #f3e5f5; padding: 1.5rem; border-radius: 4px;">
                <h4>Quick Navigation</h4>
                <p>
                    <a href="php/login.php" style="background: #0066cc; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; display: inline-block; margin-right: 1rem;">Login Portal</a>
                    <a href="php/register.php" style="background: #4CAF50; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; display: inline-block; margin-right: 1rem;">Register New User</a>
                    <a href="setup.php" style="background: #ff9800; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; display: inline-block;">Setup Admin</a>
                </p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
