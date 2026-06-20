<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: login.php");
    exit();
}

// Get student's payment history
$payments_query = "SELECT * FROM payments WHERE student_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($payments_query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$payments = $stmt->get_result();

// Calculate totals
$total_paid = 0;
$result = $conn->query("SELECT SUM(amount) as total FROM payments WHERE student_id = {$_SESSION['user_id']} AND status = 'completed'");
if($result){
    $row = $result->fetch_assoc();
    $total_paid = $row['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - Hostel Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .payment-summary {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .payment-summary h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .summary-item {
            display: inline-block;
            margin-right: 2rem;
            margin-bottom: 1rem;
        }
        .summary-label {
            color: #666;
            font-size: 0.9rem;
        }
        .summary-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0066cc;
        }
    </style>
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
            <h2>💰 Payment History</h2>

            <!-- Payment Summary -->
            <div class="payment-summary">
                <h3>Summary</h3>
                <div class="summary-item">
                    <div class="summary-label">Total Paid</div>
                    <div class="summary-value">₹<?= number_format($total_paid, 2) ?></div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Payments</div>
                    <div class="summary-value"><?= ($payments && $payments->num_rows > 0) ? $payments->num_rows : '0' ?></div>
                </div>
            </div>

            <!-- Payment History Table -->
            <?php if($payments && $payments->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
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
                                <td><strong>₹<?= number_format($payment['amount'], 2) ?></strong></td>
                                <td><?= ucfirst(str_replace('_', ' ', $payment['type'])) ?></td>
                                <td><?= date('M d, Y', strtotime($payment['date'])) ?></td>
                                <td>
                                    <span style="color: <?= $payment['status']=='completed'?'green':'orange' ?>; font-weight: bold;">
                                        <?= ucfirst($payment['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="card">
                    <p style="color: #999; text-align: center; padding: 2rem;">No payment history yet.</p>
                </div>
            <?php endif; ?>
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
