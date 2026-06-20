<?php
include 'php/config.php';

echo "<h2 style='color: #2c3e50;'>🔍 Hostel Management System - Diagnostic Report</h2>";
echo "<hr>";

// 1. Check database connection
echo "<h3>✅ Database Connection</h3>";
if ($conn && !$conn->connect_error) {
    echo "<p style='color: green;'><strong>Connected successfully to hostel_management!
    echo "<p style='color: red;'><strong>Connection failed!</strong></p>";
    exit;
}

// 2. Check if students table exists
echo "<h3>📋 Tables in Database</h3>";
$tables_result = $conn->query("SHOW TABLES");
if ($tables_result) {
    echo "<ul>";
    while ($table = $tables_result->fetch_row()) {
        echo "<li>" . htmlspecialchars($table[0]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
}

// 3. Check students table structure
echo "<h3>📊 Students Table Structure</h3>";
$structure = $conn->query("DESCRIBE students");
if ($structure) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 10px;'>";
    echo "<tr style='background-color: #f5f5f5;'>";
    echo "<th style='padding: 10px; text-align: left;'>Field</th>";
    echo "<th style='padding: 10px; text-align: left;'>Type</th>";
    echo "<th style='padding: 10px; text-align: left;'>Null</th>";
    echo "<th style='padding: 10px; text-align: left;'>Key</th>";
    echo "<th style='padding: 10px; text-align: left;'>Default</th>";
    echo "</tr>";
    
    while ($row = $structure->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['Default']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'><strong>Error:</strong> " . $conn->error . "</p>";
    echo "<p>Students table does not exist. Creating it now...</p>";
    
    // Create table
    $create_sql = "CREATE TABLE students (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'student',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_sql)) {
        echo "<p style='color: green;'><strong>✅ Students table created successfully!</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>❌ Error creating table:</strong> " . $conn->error . "</p>";
    }
}

// 4. Check records in table
echo "<h3>👥 Current Records</h3>";
$count = $conn->query("SELECT COUNT(*) as total FROM students");
if ($count) {
    $row = $count->fetch_assoc();
    echo "<p>Total students registered: <strong>" . $row['total'] . "</strong></p>";
    
    if ($row['total'] > 0) {
        $records = $conn->query("SELECT id, name, email, role, created_at FROM students ORDER BY created_at DESC");
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 10px;'>";
        echo "<tr style='background-color: #f5f5f5;'>";
        echo "<th style='padding: 10px;'>ID</th>";
        echo "<th style='padding: 10px;'>Name</th>";
        echo "<th style='padding: 10px;'>Email</th>";
        echo "<th style='padding: 10px;'>Role</th>";
        echo "<th style='padding: 10px;'>Created At</th>";
        echo "</tr>";
        
        while ($r = $records->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='padding: 10px;'>" . $r['id'] . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($r['name']) . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($r['email']) . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($r['role']) . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($r['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// 5. Check file links
echo "<h3>🔗 File Links Check</h3>";
$files_to_check = [
    'index.php',
    'php/config.php',
    'php/login.php',
    'php/register.php',
    'php/student-dashboard.php',
    'php/logout.php',
    'css/style.css',
    'js/script.js'
];

echo "<ul>";
foreach ($files_to_check as $file) {
    $full_path = __DIR__ . '\\' . $file;
    $exists = file_exists($full_path) ? '✅' : '❌';
    echo "<li>$exists " . htmlspecialchars($file) . "</li>";
}
echo "</ul>";

echo "<hr>";
echo "<p style='margin-top: 20px; color: #666;'><a href='http://localhost/hostel-management/'>← Back to Home</a></p>";
?>
