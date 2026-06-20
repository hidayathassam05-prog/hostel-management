<?php
include 'php/config.php';

echo "<h2>Database Schema Check</h2>";

$tables = ['students', 'complaints', 'notices', 'rooms', 'payments'];

foreach($tables as $table){
    $result = $conn->query("DESCRIBE $table");
    
    if($result){
        echo "<h3>Table: $table</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        
        while($row = $result->fetch_assoc()){
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
        echo "<p style='color: red;'>❌ Table '$table' not found: " . $conn->error . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='php/login.php'>Go to Login</a></p>";
?>
