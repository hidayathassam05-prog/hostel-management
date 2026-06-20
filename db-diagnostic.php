<?php
include 'php/config.php';

echo "<h1>Database Diagnostic Tool</h1>";
echo "<hr>";

// Check database connection
if($conn->connect_error){
    echo "<p style='color: red;'><strong>❌ Database Connection Error:</strong> " . $conn->connect_error . "</p>";
    exit;
} else {
    echo "<p style='color: green;'><strong>✅ Database Connected</strong></p>";
}

echo "<hr>";

// Check students table
echo "<h2>Students Table Contents</h2>";

$result = $conn->query("SELECT * FROM students");

if(!$result){
    echo "<p style='color: red;'><strong>❌ Error querying students:</strong> " . $conn->error . "</p>";
} else {
    echo "<p><strong>Total Records in students table:</strong> " . $result->num_rows . "</p>";
    
    echo "<table border='1' cellpadding='10' width='100%'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created At</th></tr>";
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td><strong>" . $row['role'] . "</strong></td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5' style='text-align: center;'>No records found</td></tr>";
    }
    echo "</table>";
}

echo "<hr>";

// Check breakdown by role
echo "<h2>Student Breakdown</h2>";

$student_count = $conn->query("SELECT COUNT(*) as count FROM students WHERE role = 'student'");
if($student_count){
    $row = $student_count->fetch_assoc();
    echo "<p><strong>Students (role='student'):</strong> " . $row['count'] . "</p>";
}

$admin_count = $conn->query("SELECT COUNT(*) as count FROM students WHERE role = 'admin'");
if($admin_count){
    $row = $admin_count->fetch_assoc();
    echo "<p><strong>Admins (role='admin'):</strong> " . $row['count'] . "</p>";
}

echo "<hr>";

// Test the admin-students query
echo "<h2>Admin Query Test (What admin-students.php uses)</h2>";
$test_query = "SELECT * FROM students WHERE role = 'student' ORDER BY created_at DESC";
$test_result = $conn->query($test_query);

if(!$test_result){
    echo "<p style='color: red;'><strong>❌ Query Error:</strong> " . $conn->error . "</p>";
} else {
    echo "<p><strong>Results for role='student' query:</strong> " . $test_result->num_rows . " rows</p>";
    if($test_result->num_rows > 0){
        echo "<table border='1' cellpadding='10' width='100%'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
        while($row = $test_result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

echo "<hr>";

echo "<p><a href='php/admin-students.php'>&larr; Back to Manage Students</a></p>";
?>
