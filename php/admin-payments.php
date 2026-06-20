<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Handle payment recording
if(isset($_POST['record_payment'])){
    $student_id = $_POST['student_id'];
    $amount = (float)$_POST['amount'];
    $date = $_POST['payment_date'];
    $payment_type = $_POST['payment_type'];
    
    if(empty($student_id) || empty($amount) || empty($date)){
        $error = "Please fill in all fields";
    } else {
        $sql = "INSERT INTO payments (student_id, amount, date, type, status) VALUES (?, ?, ?, ?, 'completed')";
        $stmt = $conn->prepare($sql);
        
        if(!$stmt){
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("idss", $student_id, $amount, $date, $payment_type);
            
            if($stmt->execute()){
                $success = "Payment recorded successfully!";
            } else {
                $error = "Error recording payment: " . $stmt->error;
            }
        }
    }
}

// Get all payments
$payments_query = "SELECT p.*, s.name FROM payments p 
                   JOIN students s ON p.student_id = s.id 
                   ORDER BY p.date DESC";
$payments = $conn->query($payments_query);

// Get all students
$students_query = "SELECT id, name FROM students WHERE role = 'student' ORDER BY name";
$students = $conn->query($students_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments - Admin</title>
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
            <h2>💰 Manage Payments</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Record Payment Form -->
            <form method="POST" style="max-width: 600px; margin-bottom: 2rem;">
                <div class="card">
                    <h3>Record Payment</h3>
                    
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            <?php 
                            if($students && $students->num_rows > 0):
                                while($student = $students->fetch_assoc()): 
                            ?>
                                <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?></option>
                            <?php 
                                endwhile;
                            endif;
                            ?>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" name="amount" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="payment_date">Payment Date</label>
                            <input type="date" id="payment_date" name="payment_date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_type">Payment Type</label>
                        <select id="payment_type" name="payment_type" required>
                            <option value="hostel_fee">Hostel Fee</option>
                            <option value="maintenance">Maintenance Charge</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <button type="submit" name="record_payment">Record Payment</button>
                </div>
            </form>

            <hr style="margin: 2rem 0;">

            <h3>Payment History</h3>

            <h3>Payment History</h3>

            <?php if($payments && $payments->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while($payment = $payments->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($payment['name']) ?></td>
                                    <td>₹<?= number_format($payment['amount'], 2) ?></td>
                                    <td><?= ucfirst(str_replace('_', ' ', $payment['type'])) ?></td>
                                    <td><?= date('M d, Y', strtotime($payment['date'])) ?></td>
                                    <td><span style="color: green; font-weight: bold;"><?= ucfirst($payment['status']) ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card">
                    <p style="color: #999; text-align: center; padding: 2rem;">No payments recorded yet.</p>
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
