<?php
include 'php/config.php';

// Create students table
$students_table = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Create complaints table
$complaints_table = "CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50),
    status ENUM('pending', 'in_progress', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)";

// Create notices table
$notices_table = "CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Create rooms table
$rooms_table = "CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(50) NOT NULL UNIQUE,
    floor VARCHAR(50),
    capacity INT NOT NULL,
    student_id INT,
    status ENUM('available', 'occupied') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
)";

// Create payments table
$payments_table = "CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    type VARCHAR(50) NOT NULL,
    status ENUM('pending', 'completed') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)";

$tables = [
    'students' => $students_table,
    'complaints' => $complaints_table,
    'notices' => $notices_table,
    'rooms' => $rooms_table,
    'payments' => $payments_table
];

echo "<h2 style='color: #0066cc;'>Database Initialization</h2>";
echo "<p>Checking and creating tables...</p>";
echo "<hr>";

$all_success = true;

foreach($tables as $name => $sql){
    if($conn->query($sql)){
        echo "✅ Table '<strong>$name</strong>' verified/created<br>";
    } else {
        echo "❌ Error with '<strong>$name</strong>' table: " . $conn->error . "<br>";
        $all_success = false;
    }
}

echo "<hr>";

if($all_success){
    echo "<p style='color: green; font-size: 1.1rem;'><strong>✅ All tables created/verified successfully!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ Some tables failed. Check errors above.</strong></p>";
}

echo "<h3>Database Structure</h3>";

// Check table structures
foreach($tables as $name => $sql){
    $result = $conn->query("DESCRIBE $name");
    if($result){
        echo "<p><strong>$name:</strong> ";
        $columns = [];
        while($row = $result->fetch_assoc()){
            $columns[] = $row['Field'] . " (" . $row['Type'] . ")";
        }
        echo implode(", ", $columns) . "</p>";
    } else {
        echo "<p style='color: red;'><strong>Error describing $name:</strong> " . $conn->error . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='student-check.php' style='color: #0066cc;'>Check Students</a> | <a href='php/login.php' style='color: #0066cc;'>Go to Login</a></p>";
?>
