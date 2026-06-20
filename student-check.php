<?php
include 'php/config.php';

$message = '';

// Test: Insert a test student
if(isset($_POST['add_test_student'])){
    $sql = "INSERT INTO students (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if(!$stmt){
        $message = "<p style='color: red;'><strong>❌ Prepare Error:</strong> " . $conn->error . "</p>";
    } else {
        $name = "Test Student";
        $email = "test@example.com";
        $password = password_hash("test123", PASSWORD_DEFAULT);
        $role = "student";
        
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        
        if($stmt->execute()){
            $message = "<p style='color: green;'><strong>✅ Test student added successfully!</strong></p>";
        } else {
            $message = "<p style='color: red;'><strong>❌ Execute Error:</strong> " . $stmt->error . "</p>";
        }
    }
}

// Display all students
$all_students = $conn->query("SELECT * FROM students");
$student_count = 0;
$students_by_role = [];

if($all_students){
    $student_count = $all_students->num_rows;
    while($row = $all_students->fetch_assoc()){
        $role = $row['role'];
        if(!isset($students_by_role[$role])){
            $students_by_role[$role] = [];
        }
        $students_by_role[$role][] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration Check</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
        .student-row { background: #e8f5e9; }
        .admin-row { background: #fce4ec; }
        form { background: #f9f9f9; padding: 15px; border-radius: 4px; margin: 20px 0; }
        button { background: #0066cc; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0052a3; }
    </style>
</head>
<body>
    <h1>Student Registration Verification</h1>
    
    <?php if($message) echo $message; ?>
    
    <h2>Database Summary</h2>
    <p><strong>Total Users in Database:</strong> <?= $student_count; ?></p>
    
    <?php foreach($students_by_role as $role => $students): ?>
        <p><strong><?= ucfirst($role); ?>s (<?= count($students); ?>):</strong></p>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered</th>
            </tr>
            <?php foreach($students as $student): ?>
                <tr class="<?= $role ?>-row">
                    <td><?= $student['id'] ?></td>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><strong><?= $student['role'] ?></strong></td>
                    <td><?= $student['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
    
    <?php if($student_count == 0): ?>
        <p style="color: red;"><strong>⚠️ No users found in database!</strong></p>
    <?php endif; ?>
    
    <h2>Test: Add a Student</h2>
    <form method="POST">
        <button type="submit" name="add_test_student">Click to Add Test Student</button>
        <p style="font-size: 0.9rem; color: #666;">This will insert a test student record to verify the database is working.</p>
    </form>
    
    <hr>
    <p>
        <a href="php/register.php" style="color: #0066cc; text-decoration: none;">← Back to Registration</a> |
        <a href="php/admin-students.php" style="color: #0066cc; text-decoration: none;">Manage Students →</a>
    </p>
</body>
</html>
