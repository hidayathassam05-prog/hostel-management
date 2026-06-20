<?php
include 'php/config.php';

echo "<h2>Database Table Fixer</h2>";
echo "<p>This tool will check and fix missing columns in your database tables.</p>";
echo "<hr>";

// Check if rooms table exists
$check_rooms = $conn->query("SHOW TABLES LIKE 'rooms'");
if($check_rooms && $check_rooms->num_rows > 0){
    echo "<p>✅ Rooms table exists</p>";
    
    // Check if student_id column exists in rooms table
    $check_column = $conn->query("SHOW COLUMNS FROM rooms LIKE 'student_id'");
    
    if(!$check_column || $check_column->num_rows == 0){
        echo "<p style='color: orange;'><strong>⚠️  Missing column:</strong> 'student_id' not found in rooms table</p>";
        echo "<p>Adding column...</p>";
        
        $alter_sql = "ALTER TABLE rooms ADD COLUMN student_id INT AFTER capacity";
        if($conn->query($alter_sql)){
            echo "<p style='color: green;'><strong>✅ Column 'student_id' added successfully</strong></p>";
        } else {
            echo "<p style='color: red;'><strong>❌ Error adding column:</strong> " . $conn->error . "</p>";
        }
        
        // Add foreign key if needed
        $fk_check = $conn->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'rooms' AND REFERENCED_TABLE_NAME = 'students'");
        if(!$fk_check || $fk_check->num_rows == 0){
            echo "<p>Adding foreign key constraint...</p>";
            $fk_sql = "ALTER TABLE rooms ADD CONSTRAINT fk_room_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL";
            if($conn->query($fk_sql)){
                echo "<p style='color: green;'><strong>✅ Foreign key added</strong></p>";
            } else {
                // Might already exist, so don't worry if it fails
                echo "<p style='color: blue;'>Foreign key constraint note: " . $conn->error . "</p>";
            }
        }
    } else {
        echo "<p style='color: green;'><strong>✅ Column 'student_id' exists</strong></p>";
    }
    
    // Show rooms table structure
    $describe = $conn->query("DESCRIBE rooms");
    echo "<p><strong>Rooms table structure:</strong></p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while($row = $describe->fetch_assoc()){
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} else {
    echo "<p style='color: red;'><strong>❌ Rooms table does not exist!</strong></p>";
    echo "<p>Creating rooms table...</p>";
    
    $create_table = "CREATE TABLE rooms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        room_number VARCHAR(50) NOT NULL UNIQUE,
        floor VARCHAR(50),
        capacity INT NOT NULL,
        student_id INT,
        status ENUM('available', 'occupied') DEFAULT 'available',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
    )";
    
    if($conn->query($create_table)){
        echo "<p style='color: green;'><strong>✅ Rooms table created successfully</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>❌ Error creating table:</strong> " . $conn->error . "</p>";
    }
}

echo "<hr>";
echo "<p><strong>Related tables check:</strong></p>";

// Check students table
$check_students = $conn->query("SHOW TABLES LIKE 'students'");
echo ($check_students && $check_students->num_rows > 0) ? "✅ Students table exists<br>" : "❌ Students table missing<br>";

// Check complaints table
$check_complaints = $conn->query("SHOW TABLES LIKE 'complaints'");
echo ($check_complaints && $check_complaints->num_rows > 0) ? "✅ Complaints table exists<br>" : "❌ Complaints table missing<br>";

// Check notices table
$check_notices = $conn->query("SHOW TABLES LIKE 'notices'");
echo ($check_notices && $check_notices->num_rows > 0) ? "✅ Notices table exists<br>" : "❌ Notices table missing<br>";

// Check payments table
$check_payments = $conn->query("SHOW TABLES LIKE 'payments'");
echo ($check_payments && $check_payments->num_rows > 0) ? "✅ Payments table exists<br>" : "❌ Payments table missing<br>";

echo "<hr>";
echo "<p>
    <a href='php/admin-rooms.php' style='color: #0066cc;'>Try Manage Rooms Again →</a>
</p>";
?>
