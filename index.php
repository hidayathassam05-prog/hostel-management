<?php
session_start();

// Only include config if needed (for database operations)
// For now, just check session without DB

// Redirect authenticated students to dashboard
if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'student'){
    header("Location: php/student-dashboard.php");
    exit();
}

// Redirect authenticated admins to admin panel
if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'){
    header("Location: php/admin-dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hostel Management System - Student Portal">
    <title>Hostel Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management System</h1>
                <div>
                    <a href="php/login.php">Login</a>
                    <a href="php/register.php">Register</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <section class="text-center" style="padding: 3rem 0;">
                <h2>Welcome to Hostel Management System</h2>
                <p style="font-size: 1.1rem; color: #666; margin: 1rem 0;">
                    Manage your hostel accommodation and submit complaints with ease
                </p>

                <div style="margin-top: 2rem;">
                    <a href="php/login.php" class="btn" style="display: inline-block; margin-right: 1rem; background-color: #0066cc; color: white; padding: 0.75rem 2rem; border-radius: 4px;">Login</a>
                    <a href="php/register.php" class="btn" style="display: inline-block; background-color: #28a745; color: white; padding: 0.75rem 2rem; border-radius: 4px;">Create Account</a>
                </div>
            </section>

            <!-- Features -->
            <section class="grid" style="margin-top: 3rem;">
                <div class="card">
                    <h3>📋 Easy Management</h3>
                    <p>View your room allocation, maintenance status, and hostel details in one place.</p>
                </div>
                <div class="card">
                    <h3>🔔 Submit Complaints</h3>
                    <p>Report issues with a simple form and track their resolution status.</p>
                </div>
                <div class="card">
                    <h3>📢 Get Notices</h3>
                    <p>Stay updated with important announcements and hostel information.</p>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
