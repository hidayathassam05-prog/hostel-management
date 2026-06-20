<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Handle complaint submission
if(isset($_POST['submit'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    
    if(empty($title) || empty($description)){
        $error = "Please fill in all fields";
    } else {
        $sql = "INSERT INTO complaints (student_id, title, description, category, status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $_SESSION['user_id'], $title, $description, $category);
        
        if($stmt->execute()){
            $success = "Complaint submitted successfully!";
        } else {
            $error = "Error submitting complaint";
        }
    }
}

// Get user's complaints
$complaints_query = "SELECT * FROM complaints WHERE student_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($complaints_query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$complaints = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints - Hostel Management</title>
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
            <h2>📋 Submit a Complaint</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" style="max-width: 600px;">
                <div class="form-group">
                    <label for="title">Complaint Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="maintenance">Maintenance Issue</option>
                        <option value="cleanliness">Cleanliness</option>
                        <option value="noise">Noise Complaint</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" required></textarea>
                </div>

                <button type="submit" name="submit">Submit Complaint</button>
            </form>

            <hr style="margin: 3rem 0;">

            <h3>📝 Your Complaints</h3>

            <?php if($complaints && $complaints->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $complaints->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($row['category'])) ?></td>
                                <td>
                                    <?php
                                    $status = $row['status'];
                                    $color = $status == 'resolved' ? 'green' : ($status == 'pending' ? 'orange' : 'blue');
                                    ?>
                                    <span style="color: <?= $color ?>; font-weight: bold;">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="card">
                    <p style="color: #999; text-align: center; padding: 1.5rem;">No complaints yet.</p>
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
