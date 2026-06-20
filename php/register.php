<?php
include 'config.php';

$error = '';
$success = '';
$admin_code = 'ADMIN123'; // Admin registration code

if(isset($_POST['submit'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $admin_code_input = $_POST['admin_code'] ?? '';
    
    // Validation
    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)){
        $error = "Please fill in all fields";
    } elseif(strlen($password) < 6){
        $error = "Password must be at least 6 characters";
    } elseif($password !== $confirm_password){
        $error = "Passwords do not match";
    } elseif($role == 'admin' && (empty($admin_code_input) || $admin_code_input !== $admin_code)){
        $error = "Invalid admin code. Admin registration failed.";
    } else {
        // Check if email already exists
        $check_email = "SELECT id FROM students WHERE email=?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $error = "This email is already registered";
        } else {
            // Insert new student/admin
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO students (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
            
            if($stmt->execute()){
                $role_text = ($role == 'admin') ? 'Administrator' : 'Student';
                // Automatically redirect to login after 2 seconds
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                </script>";
                $success = "Registration as $role_text successful! Redirecting to login...";
            } else {
                $error = "Error during registration. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hostel Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <h1 style="margin: 0; color: white;">Hostel Management</h1>
                <div>
                    <a href="../index.php">Home</a>
                    <a href="login.php">Login</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <form method="POST" style="max-width: 400px;">
                <h2>Create Your Account</h2>

                <?php if($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <small style="color: #666; display: block; margin-top: 0.25rem;">Minimum 6 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-group">
                    <label for="role">Register as:</label>
                    <select id="role" name="role" required onchange="toggleAdminCode()">
                        <option value="student">Student</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                

                <div class="form-group" id="admin_code_group" style="display: none;">
                    <label for="admin_code">Admin Code (Required for Admin)</label>
                    <input type="password" id="admin_code" name="admin_code" placeholder="Enter admin code">
                    <small style="color: #666; display: block; margin-top: 0.25rem;">Contact admin for code</small>
                </div>

                <button type="submit" name="submit">Register</button>

                <p class="text-center" style="margin-top: 1.5rem;">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Hostel Management System. All rights reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
    <script>
        function toggleAdminCode() {
            const role = document.getElementById('role').value;
            const adminCodeGroup = document.getElementById('admin_code_group');
            const adminCodeInput = document.getElementById('admin_code');
            
            if (role === 'admin') {
                adminCodeGroup.style.display = 'block';
                adminCodeInput.required = true;
            } else {
                adminCodeGroup.style.display = 'none';
                adminCodeInput.required = false;
                adminCodeInput.value = '';
            }
        }
    </script>
</body>
</html>
