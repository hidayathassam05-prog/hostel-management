<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Handle student deletion
if(isset($_GET['delete']) && isset($_GET['id'])){
    $student_id = $_GET['id'];
    $sql = "DELETE FROM students WHERE id = ? AND role = 'student'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    
    if($stmt->execute()){
        $success = "Student deleted!";
    } else {
        $error = "Error deleting student";
    }
}

// Get all students
$students_query = "SELECT * FROM students WHERE role = 'student' ORDER BY created_at DESC";
$students = $conn->query($students_query);

// Check if query failed
if(!$students){
    $students = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Admin</title>
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
            <h2>👥 Manage Students</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if($students && $students->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($student = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $student['id'] ?></td>
                                    <td><?= htmlspecialchars($student['name']) ?></td>
                                    <td><?= htmlspecialchars($student['email']) ?></td>
                                    <td><?= date('M d, Y', strtotime($student['created_at'])) ?></td>
                                    <td>
                                        <a href="?delete=1&id=<?= $student['id'] ?>" onclick="return confirm('Delete this student?')" style="color: red;">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: #666;">No students registered yet.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
