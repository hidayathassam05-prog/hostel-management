<?php
include 'config.php';
session_start();

$error = '';

// Redirect if already logged in
if(isset($_SESSION['user_id'])){
    $redirect_page = ($_SESSION['role'] == 'admin') ? 'admin-dashboard.php' : 'student-dashboard.php';
    header("Location: $redirect_page");
    exit();
}

if(isset($_POST['submit'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if(empty($email) || empty($password)){
        $error = "Please fill in all fields";
    } else {
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM students WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                
                // Redirect based on role
                $redirect_page = ($user['role'] == 'admin') ? 'admin-dashboard.php' : 'student-dashboard.php';
                header("Location: $redirect_page");
                exit();
            } else {
                $error = "Incorrect password";
            }
        } else {
            $error = "Email not found";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hostel Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management</h1>
                <div>
                    <a href="../index.php">Home</a>
                    <a href="register.php">Register</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <form method="POST" style="max-width: 400px;">
                <h2>Login to Your Account</h2>

                <?php if($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" name="submit">Login</button>

                <p class="text-center" style="margin-top: 1.5rem;">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
