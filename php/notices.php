<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get all notices
$notices_query = "SELECT * FROM notices ORDER BY created_at DESC";
$notices = $conn->query($notices_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices - Hostel Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management</h1>
                <div>
                    <a href="student-dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>📢 Hostel Notices</h2>

            <?php if($notices->num_rows > 0): ?>
                <?php while($notice = $notices->fetch_assoc()): ?>
                    <div class="card" style="margin-bottom: 1.5rem;">
                        <h3><?= htmlspecialchars($notice['title']) ?></h3>
                        <p style="color: #666; font-size: 0.9rem;">
                            Posted on <?= date('M d, Y at H:i', strtotime($notice['created_at'])) ?>
                        </p>
                        <p><?= nl2br(htmlspecialchars($notice['content'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card">
                    <p style="color: #999; text-align: center; padding: 2rem;">No notices at this time.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
