<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Handle room creation
if(isset($_POST['add_room'])){
    $room_number = trim($_POST['room_number']);
    $floor = $_POST['floor'];
    $capacity = $_POST['capacity'];
    
    if(empty($room_number) || empty($capacity)){
        $error = "Please fill in all fields";
    } else {
        $sql = "INSERT INTO rooms (room_number, floor, capacity, status) VALUES (?, ?, ?, 'available')";
        $stmt = $conn->prepare($sql);
        
        if(!$stmt){
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ssi", $room_number, $floor, $capacity);
            
            if($stmt->execute()){
                $success = "Room added successfully!";
            } else {
                $error = "Error adding room: " . $stmt->error;
            }
        }
    }
}

// Handle room assignment
if(isset($_POST['assign_room'])){
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    
    // Remove old assignment if exists
    $conn->query("UPDATE rooms SET student_id = NULL WHERE student_id = $student_id");
    
    // Update with new room
    $sql = "UPDATE rooms SET student_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if(!$stmt){
        $error = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("ii", $student_id, $room_id);
        
        if($stmt->execute()){
            $success = "Room assigned successfully!";
        } else {
            $error = "Error assigning room: " . $stmt->error;
        }
    }
}

// Handle room deletion
if(isset($_GET['delete']) && isset($_GET['id'])){
    $room_id = $_GET['id'];
    $sql = "DELETE FROM rooms WHERE id = ? AND student_id IS NULL";
    $stmt = $conn->prepare($sql);
    
    if(!$stmt){
        $error = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("i", $room_id);
        
        if($stmt->execute()){
            $success = "Room deleted!";
        } else {
            $error = "Cannot delete room assigned to a student";
        }
    }
}

// Get all rooms
$rooms_query = "SELECT r.*, s.name FROM rooms r LEFT JOIN students s ON r.student_id = s.id ORDER BY r.room_number";
$rooms = $conn->query($rooms_query);

if(!$rooms){
    $error = "Database error: " . $conn->error . " <br><a href='../fix-database.php' style='color: #0066cc; text-decoration: underline;'>Click here to fix the database</a>";
    $rooms = false;
}

// Get unassigned students
$students_query = "SELECT id, name FROM students WHERE role = 'student' AND id NOT IN (SELECT student_id FROM rooms WHERE student_id IS NOT NULL) ORDER BY name";
$students = $conn->query($students_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - Admin</title>
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
            <h2>🚪 Manage Rooms</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 1fr; margin-bottom: 2rem;">
                <!-- Add Room Form -->
                <form method="POST" class="card">
                    <h3>Add New Room</h3>
                    
                    <div class="form-group">
                        <label for="room_number">Room Number</label>
                        <input type="text" id="room_number" name="room_number" placeholder="e.g., 101, 102" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="floor">Floor</label>
                            <input type="text" id="floor" name="floor" placeholder="e.g., 1st, 2nd">
                        </div>

                        <div class="form-group">
                            <label for="capacity">Capacity (beds)</label>
                            <input type="number" id="capacity" name="capacity" min="1" max="10" required>
                        </div>
                    </div>

                    <button type="submit" name="add_room">Add Room</button>
                </form>

                <!-- Assign Room Form -->
                <?php if($students && $students->num_rows > 0): ?>
                    <form method="POST" class="card">
                        <h3>Assign Room to Student</h3>
                        
                        <div class="form-group">
                            <label for="student_id">Student</label>
                            <select id="student_id" name="student_id" required>
                                <option value="">Select Student</option>
                                <?php 
                                $students->data_seek(0);
                                while($student = $students->fetch_assoc()): 
                                ?>
                                    <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="room_id">Room</label>
                            <select id="room_id" name="room_id" required>
                                <option value="">Select Room</option>
                                <?php 
                                $rooms->data_seek(0);
                                while($room = $rooms->fetch_assoc()): 
                                    if($room['student_id'] === null):
                                ?>
                                    <option value="<?= $room['id'] ?>">Room <?= htmlspecialchars($room['room_number']) ?> (Cap: <?= $room['capacity'] ?>)</option>
                                <?php 
                                    endif;
                                endwhile; 
                                ?>
                            </select>
                        </div>

                        <button type="submit" name="assign_room">Assign Room</button>
                    </form>
                <?php endif; ?>
            </div>

            <hr style="margin: 2rem 0;">

            <h3>All Rooms</h3>

            <?php if($rooms && $rooms->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Room Number</th>
                                <th>Floor</th>
                                <th>Capacity</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rooms->data_seek(0);
                            while($room = $rooms->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($room['room_number']) ?></td>
                                    <td><?= htmlspecialchars($room['floor']) ?></td>
                                    <td><?= $room['capacity'] ?> beds</td>
                                    <td><?= $room['name'] ? htmlspecialchars($room['name']) : '<span style="color: #999;">Unassigned</span>' ?></td>
                                    <td><span style="color: <?= $room['student_id'] ? 'green' : 'orange' ?>; font-weight: bold;">
                                        <?= $room['student_id'] ? 'Occupied' : 'Available' ?>
                                    </span></td>
                                    <td>
                                        <?php if(!$room['student_id']): ?>
                                            <a href="?delete=1&id=<?= $room['id'] ?>" onclick="return confirm('Delete this room?')" style="color: red;">Delete</a>
                                        <?php else: ?>
                                            <span style="color: #999;">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card">
                    <p style="color: #999; text-align: center; padding: 2rem;">No rooms yet. Add rooms from the form above.</p>
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
