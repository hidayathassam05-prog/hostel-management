<?php
include 'php/config.php';

echo "<h2>Student Database Check</h2>";

// Check all students (regardless of role)
$all_students = $conn->query("SELECT * FROM students ORDER BY created_at DESC");

echo "<h3>All Students in Database:</h3>";
if($all_students && $all_students->num_rows > 0){
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>";
    while($row = $all_students->fetch_assoc()){
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . $row['role'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ No students found in database</p>";
}

// Check only students with role='student'
echo "<h3>Students with role='student':</h3>";
$role_students = $conn->query("SELECT * FROM students WHERE role='student' ORDER BY created_at DESC");

if($role_students && $role_students->num_rows > 0){
    echo "<p style='color: green;'>✅ Found " . $role_students->num_rows . " students</p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th></tr>";
    while($row = $role_students->fetch_assoc()){
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ No students with role='student'</p>";
}

// Check if any are admins
echo "<h3>Admins in Database:</h3>";
$admins = $conn->query("SELECT * FROM students WHERE role='admin'");

if($admins && $admins->num_rows > 0){
    echo "<p>Found " . $admins->num_rows . " admin(s):</p>";
    while($row = $admins->fetch_assoc()){
        echo "<p>- " . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['email']) . ")</p>";
    }
} else {
    echo "<p>No admins in database</p>";
}

echo "<p><a href='check-tables.php'>Back to Table Check</a></p>";
?>
