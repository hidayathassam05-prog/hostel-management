<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Handle status update
if(isset($_POST['update_status'])){
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];
    
    $sql = "UPDATE complaints SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $complaint_id);
    
    if($stmt->execute()){
        $success = "Complaint status updated!";
    } else {
        $error = "Error updating status";
    }
}

// Handle complaint deletion
if(isset($_GET['delete']) && isset($_GET['id'])){
    $complaint_id = $_GET['id'];
    $sql = "DELETE FROM complaints WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $complaint_id);
    
    if($stmt->execute()){
        $success = "Complaint deleted!";
    }
}

// Get all complaints
$complaints_query = "SELECT c.*, s.name FROM complaints c 
                     JOIN students s ON c.student_id = s.id 
                     ORDER BY c.created_at DESC";
$complaints = $conn->query($complaints_query);

// Check if query failed
if(!$complaints){
    $complaints = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints - Admin</title>
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
            <h2>🔧 Manage Complaints</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if($complaints && $complaints->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($complaint = $complaints->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($complaint['name']) ?></td>
                                    <td><?= htmlspecialchars($complaint['title']) ?></td>
                                    <td><?= ucfirst($complaint['category']) ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="complaint_id" value="<?= $complaint['id'] ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="pending" <?= $complaint['status']=='pending'?'selected':'' ?>>Pending</option>
                                                <option value="in_progress" <?= $complaint['status']=='in_progress'?'selected':'' ?>>In Progress</option>
                                                <option value="resolved" <?= $complaint['status']=='resolved'?'selected':'' ?>>Resolved</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($complaint['created_at'])) ?></td>
                                    <td>
                                        <a href="?delete=1&id=<?= $complaint['id'] ?>" onclick="return confirm('Delete this complaint?')" style="color: red;">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card">
                    <p style="color: #999; text-align: center; padding: 2rem;">No complaints submitted yet.</p>
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
