<?php
include 'php/config.php';

echo "<h1>🔧 Complete Database Fixer</h1>";
echo "<p>This will rebuild the rooms table with all required columns and proper schema.</p>";
echo "<hr>";

$message = "";

// Handle manual fix request
if(isset($_POST['fix_database'])){
    echo "<h2>Fixing Database...</h2>";
    
    // Step 1: Check if rooms table exists
    $check = $conn->query("SHOW TABLES LIKE 'rooms'");
    
    if($check && $check->num_rows > 0){
        echo "Step 1: Rooms table exists - checking structure<br>";
        
        // Step 2: Get current structure
        $describe = $conn->query("DESCRIBE rooms");
        $columns = [];
        while($row = $describe->fetch_assoc()){
            $columns[] = $row['Field'];
        }
        echo "Current columns: " . implode(", ", $columns) . "<br><br>";
        
        // Step 3: Check for PRIMARY KEY (id column)
        if(!in_array('id', $columns)){
            echo "⚠️  Missing PRIMARY KEY 'id' - rebuilding table...<br>";
            
            // Drop old table and create new one with proper schema
            $conn->query("DROP TABLE IF EXISTS rooms");
            echo "✅ Old rooms table dropped<br>";
            
            $new_rooms_sql = "CREATE TABLE rooms (
                id INT AUTO_INCREMENT PRIMARY KEY,
                room_number VARCHAR(50) NOT NULL UNIQUE,
                floor VARCHAR(50),
                capacity INT NOT NULL,
                student_id INT,
                status ENUM('available', 'occupied') DEFAULT 'available',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
            )";
            
            if($conn->query($new_rooms_sql)){
                echo "✅ Rooms table recreated with proper schema<br>";
                $columns = ['id', 'room_number', 'floor', 'capacity', 'student_id', 'status', 'created_at'];
            } else {
                echo "❌ Error recreating rooms table: " . $conn->error . "<br>";
            }
        } else {
            // Check and add missing columns
            $required_columns = ['room_number', 'floor', 'capacity', 'student_id', 'status'];
            
            foreach($required_columns as $col){
                if(!in_array($col, $columns)){
                    echo "⚠️  Adding missing column '$col'...<br>";
                    
                    // Determine the correct data type and position
                    $add_column_sql = "";
                    switch($col){
                        case 'room_number':
                            $add_column_sql = "ALTER TABLE rooms ADD COLUMN room_number VARCHAR(50) NOT NULL UNIQUE AFTER id";
                            break;
                        case 'floor':
                            $add_column_sql = "ALTER TABLE rooms ADD COLUMN floor VARCHAR(50) AFTER room_number";
                            break;
                        case 'capacity':
                            $add_column_sql = "ALTER TABLE rooms ADD COLUMN capacity INT NOT NULL AFTER floor";
                            break;
                        case 'student_id':
                            $add_column_sql = "ALTER TABLE rooms ADD COLUMN student_id INT AFTER capacity";
                            break;
                        case 'status':
                        $add_column_sql = "ALTER TABLE rooms ADD COLUMN status ENUM('available', 'occupied') DEFAULT 'available' AFTER student_id";
                        break;
                }
                
                if($add_column_sql && $conn->query($add_column_sql)){
                    echo "✅ Column '$col' added successfully<br>";
                } else {
                    echo "❌ Error adding column '$col': " . $conn->error . "<br>";
                }
            } else {
                echo "✅ Column '$col' already exists<br>";
            }
        }
        
        // Step 4: Add foreign key constraint
        echo "Adding foreign key constraint...<br>";
        $fk_sql = "ALTER TABLE rooms ADD CONSTRAINT fk_room_student 
                   FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL";
        if($conn->query($fk_sql)){
            echo "✅ Foreign key constraint added<br>";
        } else {
            // It might already exist, that's okay
            if(strpos($conn->error, "Duplicate") !== false){
                echo "✅ Foreign key already exists<br>";
            } else {
                echo "⚠️  " . $conn->error . "<br>";
            }
        }
        
    } else {
        // Table doesn't exist, create it
        echo "Step 1: Creating rooms table...<br>";
        
        $create_sql = "CREATE TABLE rooms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            room_number VARCHAR(50) NOT NULL UNIQUE,
            floor VARCHAR(50),
            capacity INT NOT NULL,
            student_id INT,
            status ENUM('available', 'occupied') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if($conn->query($create_sql)){
            echo "✅ Rooms table created successfully<br>";
        } else {
            echo "❌ Error creating table: " . $conn->error . "<br>";
        }
    }
    
    echo "<br><hr>";
    echo "<p style='color: green; font-size: 1.1rem;'><strong>✅ Database fix completed!</strong></p>";
}

echo "<h2>Current Database Structure</h2>";

// Show all tables
$tables = $conn->query("SHOW TABLES");
$table_list = [];
while($table = $tables->fetch_row()){
    $table_list[] = $table[0];
}
echo "<p><strong>Tables in database:</strong> " . implode(", ", $table_list) . "</p>";

// Show rooms table details if it exists
if(in_array('rooms', $table_list)){
    echo "<p><strong>Rooms table structure:</strong></p>";
    $describe = $conn->query("DESCRIBE rooms");
    echo "<table border='1' cellpadding='10' style='width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = $describe->fetch_assoc()){
        $bg = ($row['Field'] == 'student_id') ? "background: #c8e6c9;" : "";
        echo "<tr style='$bg'>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'><strong>❌ Rooms table does not exist!</strong></p>";
}

echo "<hr>";

// Show form to run fix
if(!isset($_POST['fix_database'])){
    echo "<form method='POST' style='background: #e3f2fd; padding: 20px; border-radius: 4px;'>";
    echo "<button type='submit' name='fix_database' style='background: #0066cc; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;'>";
    echo "🔧 Run Database Fix Now";
    echo "</button>";
    echo "<p style='color: #666; font-size: 0.9rem; margin-top: 10px;'>This will add the missing student_id column and create the proper foreign key relationship.</p>";
    echo "</form>";
}

echo "<hr>";
echo "<p>";
echo "<a href='php/admin-rooms.php' style='background: #4CAF50; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; display: inline-block; margin-right: 10px;'>Try Manage Rooms</a>";
echo "<a href='index.php' style='background: #666; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; display: inline-block;'>Home</a>";
echo "</p>";
?>
