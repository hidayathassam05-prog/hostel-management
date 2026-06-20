<?php
include 'php/config.php';

echo "<h2>Database Table Check</h2>";

// Check if notices table exists
$result = $conn->query("SHOW TABLES LIKE 'notices'");

if($result && $result->num_rows > 0){
    echo "<p style='color: green;'>✅ 'notices' table EXISTS</p>";
    
    // Show table structure
    $structure = $conn->query("DESCRIBE notices");
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while($row = $structure->fetch_assoc()){
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
    echo "<p style='color: red;'>❌ 'notices' table DOES NOT EXIST</p>";
    echo "<p><strong>Solution:</strong> Run <a href='db-init.php'>db-init.php</a> to create the table</p>";
}

// Check all tables
echo "<h3>All Tables:</h3>";
$all_tables = $conn->query("SHOW TABLES");
if($all_tables->num_rows > 0){
    echo "<ul>";
    while($row = $all_tables->fetch_row()){
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No tables found. Database is empty.</p>";
}
?>
