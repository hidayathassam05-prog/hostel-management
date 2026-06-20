<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Hostel Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management</h1>
                <div>
                    <span style="color: white; margin-right: 1.5rem;">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Student Dashboard</h2>
            <p>Hello <?= htmlspecialchars($_SESSION['name']) ?>! Here's your hostel management portal.</p>

            <div class="grid" style="margin-top: 2rem;">
                <div class="card">
                    <h3>📋 Room Information</h3>
                    <p>View your room allocation and hostel details.</p>
                    <a href="room-info.php" class="btn" style="display: inline-block; margin-top: 1rem;">View Details</a>
                </div>

                <div class="card">
                    <h3>🔧 Submit Complaint</h3>
                    <p>Report maintenance issues or other concerns.</p>
                    <a href="complaints.php" class="btn" style="display: inline-block; margin-top: 1rem;">Submit Complaint</a>
                </div>

                <div class="card">
                    <h3>📢 View Notices</h3>
                    <p>Check important announcements and hostel notices.</p>
                    <a href="notices.php" class="btn" style="display: inline-block; margin-top: 1rem;">View Notices</a>
                </div>

                <div class="card">
                    <h3>� Payment History</h3>
                    <p>View your payment records and billing information.</p>
                    <a href="payments.php" class="btn" style="display: inline-block; margin-top: 1rem;">View Payments</a>
                </div>

                <div class="card">
                    <h3>�👤 My Profile</h3>
                    <p>View and edit your profile information.</p>
                    <a href="profile.php" class="btn" style="display: inline-block; margin-top: 1rem;">Go to Profile</a>
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
