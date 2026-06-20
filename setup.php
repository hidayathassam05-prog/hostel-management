<?php
include 'php/config.php';

// Check if admin already exists
$check = $conn->query("SELECT * FROM students WHERE role='admin'");
$admin_exists = $check->num_rows > 0;

if($admin_exists){
    echo "<h2 style='color: green;'>✅ Admin account already exists!</h2>";
    echo "<p><strong>Email:</strong> Check your database</p>";
    echo "<p><a href='php/login.php'>Go to Login</a></p>";
    exit;
}

// Create admin account
$name = 'Admin User';
$email = 'admin@example.com';
$password = password_hash('password123', PASSWORD_DEFAULT);
$role = 'admin';

$sql = "INSERT INTO students (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $password, $role);

if($stmt->execute()){
    echo "<h2 style='color: green;'>✅ Admin account created successfully!</h2>";
    echo "<div style='background: #f0f0f0; padding: 1rem; border-radius: 4px; max-width: 400px;'>";
    echo "<p><strong>Email:</strong> admin@example.com</p>";
    echo "<p><strong>Password:</strong> password123</p>";
    echo "<p style='color: #666; font-size: 0.9rem;'>You can change this password after login</p>";
    echo "</div>";
    echo "<p style='margin-top: 1.5rem;'><a href='php/login.php' style='background: #0066cc; color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; display: inline-block;'>Go to Login</a></p>";
} else {
    echo "<h2 style='color: red;'>❌ Error creating admin account</h2>";
    echo "<p>" . $conn->error . "</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup - Hostel Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 style="margin: 0; color: white;">Hostel Management System</h1>
        </div>
    </header>
    <main>
        <div class="container">
