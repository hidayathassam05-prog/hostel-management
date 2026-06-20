<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get student's room information
$sql = "SELECT s.id, s.name, s.email, r.room_number, r.floor, r.capacity, r.status 
        FROM students s 
        LEFT JOIN rooms r ON s.id = r.student_id 
        WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Info - Hostel Management</title>
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
            <h2>🚪 Your Room Information</h2>

            <div class="grid" style="grid-template-columns: 1fr;">
                <div class="card">
                    <h3>Student Details</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
                </div>

                <?php if($student['room_number']): ?>
                    <div class="card">
                        <h3>Room Allocation</h3>
                        <p><strong>Room Number:</strong> <?= htmlspecialchars($student['room_number']) ?></p>
                        <p><strong>Floor:</strong> <?= htmlspecialchars($student['floor']) ?></p>
                        <p><strong>Capacity:</strong> <?= htmlspecialchars($student['capacity']) ?> beds</p>
                        <p>
                            <strong>Status:</strong> 
                            <span style="color: <?= $student['status'] == 'active' ? 'green' : 'red' ?>; font-weight: bold;">
                                <?= ucfirst($student['status']) ?>
                            </span>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <h3>Room Allocation</h3>
                        <p style="color: #999;">Room not yet allocated. Please contact the hostel administration.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
