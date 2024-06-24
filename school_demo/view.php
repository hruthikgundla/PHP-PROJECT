<?php

<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Leave empty if no password set for MySQL
$db_name = 'school_database'; // Replace with your database name

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>



if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Student ID not provided.");
}

$student_id = $_GET['id'];

// Fetch student details with class name using JOIN
$sql_student = "SELECT s.name, s.email, s.address, s.created_at, s.image, c.name as class_name 
                FROM student s 
                LEFT JOIN classes c ON s.class_id = c.class_id 
                WHERE s.id = $student_id";
$result_student = $conn->query($sql_student);

if ($result_student->num_rows > 0) {
    $row = $result_student->fetch_assoc();
} else {
    die("Student not found.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student</title>
    <link rel="stylesheet" href="styles.css"> <!-- Replace with your CSS file -->
</head>
<body>
    <h1>Student Details</h1>
    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
    <p><strong>Class:</strong> <?php echo htmlspecialchars($row['class_name']); ?></p>
    <p><strong>Created At:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
    <?php if (!empty($row['image'])): ?>
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Student Image" width="200">
    <?php endif; ?>
    <br><br>
    <a href="index.php">Back to Home</a>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
