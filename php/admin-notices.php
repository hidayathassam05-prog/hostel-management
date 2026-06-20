<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Handle notice creation
if(isset($_POST['add_notice'])){
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if(empty($title) || empty($content)){
        $error = "Please fill in all fields";
    } else {
        $sql = "INSERT INTO notices (title, content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        
        if($stmt === false){
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $title, $content);
            
            if($stmt->execute()){
                $success = "Notice posted successfully!";
            } else {
                $error = "Error posting notice: " . $stmt->error;
            }
        }
    }
}

// Handle notice deletion
if(isset($_GET['delete']) && isset($_GET['id'])){
    $notice_id = $_GET['id'];
    $sql = "DELETE FROM notices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt === false){
        $error = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("i", $notice_id);
        
        if($stmt->execute()){
            $success = "Notice deleted!";
        } else {
            $error = "Error deleting notice: " . $stmt->error;
        }
    }
}

// Get all notices
$notices_query = "SELECT * FROM notices ORDER BY created_at DESC";
$notices = $conn->query($notices_query);

// Check if query failed
if(!$notices){
    $notices = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notices - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management - Admin</h1>
                <div>
                    <a href="admin-dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>📢 Manage Notices</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" style="max-width: 600px; margin-bottom: 2rem;">
                <div class="card">
                    <h3>Post New Notice</h3>
                    
                    <div class="form-group">
                        <label for="title">Notice Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" rows="5" required></textarea>
                    </div>

                    <button type="submit" name="add_notice">Post Notice</button>
                </div>
            </form>

            <hr style="margin: 2rem 0;">

            <h3>Recent Notices</h3>

            <?php if($notices && $notices->num_rows > 0): ?>
                <?php while($notice = $notices->fetch_assoc()): ?>
                    <div class="card" style="margin-bottom: 1rem;">
                        <h4><?= htmlspecialchars($notice['title']) ?></h4>
                        <p style="color: #666; font-size: 0.9rem;">
                            Posted on <?= date('M d, Y at H:i', strtotime($notice['created_at'])) ?>
                        </p>
                        <p><?= nl2br(htmlspecialchars($notice['content'])) ?></p>
                        <a href="?delete=1&id=<?= $notice['id'] ?>" onclick="return confirm('Delete this notice?')" style="color: red;">Delete</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card">
                    <p style="color: #999; text-align: center; padding: 2rem;">No notices posted yet.</p>
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
