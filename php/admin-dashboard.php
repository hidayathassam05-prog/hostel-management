<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// Get statistics with error checking
$total_students = 0;
$total_complaints = 0;
$pending_complaints = 0;
$total_notices = 0;

$result = $conn->query("SELECT COUNT(*) as count FROM students WHERE role='student'");
if($result) $total_students = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM complaints");
if($result) $total_complaints = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE status='pending'");
if($result) $pending_complaints = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM notices");
if($result) $total_notices = $result->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hostel Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0066cc;
            margin: 1rem 0;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management - Admin</h1>
                <div>
                    <span style="color: white; margin-right: 1.5rem;">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>👨‍💼 Admin Dashboard</h2>

            <!-- Statistics -->
            <div class="grid" style="margin-top: 2rem; margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-label">Total Students</div>
                    <div class="stat-number"><?= $total_students ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Complaints</div>
                    <div class="stat-number"><?= $total_complaints ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Pending Complaints</div>
                    <div class="stat-number" style="color: #ff9800;"><?= $pending_complaints ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Notices</div>
                    <div class="stat-number"><?= $total_notices ?></div>
                </div>
            </div>

            <!-- Quick Links -->
            <h3 style="margin-top: 2rem;">Management Options</h3>
            <div class="grid">
                <div class="card">
                    <h4>👥 Manage Students</h4>
                    <p>View, add, or remove students from the system.</p>
                    <a href="admin-students.php" class="btn" style="display: inline-block; margin-top: 1rem;">Manage Students</a>
                </div>

                <div class="card">
                    <h4>🔧 Manage Complaints</h4>
                    <p>View and resolve student complaints.</p>
                    <a href="admin-complaints.php" class="btn" style="display: inline-block; margin-top: 1rem;">Manage Complaints</a>
                </div>

                <div class="card">
                    <h4>📢 Manage Notices</h4>
                    <p>Post and manage hostel announcements.</p>
                    <a href="admin-notices.php" class="btn" style="display: inline-block; margin-top: 1rem;">Manage Notices</a>
                </div>

                <div class="card">
                    <h4>🚪 Manage Rooms</h4>
                    <p>Add rooms, assign students, and manage allocations.</p>
                    <a href="admin-rooms.php" class="btn" style="display: inline-block; margin-top: 1rem;">Manage Rooms</a>
                </div>

                <div class="card">
                    <h4>💰 Manage Payments</h4>
                    <p>Record and track student payments.</p>
                    <a href="admin-payments.php" class="btn" style="display: inline-block; margin-top: 1rem;">Manage Payments</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
