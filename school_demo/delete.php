<?php
// delete.php

// Database connection (same as index.php)

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

// Fetch student details
$sql_student = "SELECT * FROM student WHERE id = $student_id";
$result_student = $conn->query($sql_student);

if ($result_student->num_rows > 0) {
    $row = $result_student->fetch_assoc();
} else {
    die("Student not found.");
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete student image if exists
    if (!empty($row['image'])) {
        $image_path = $row['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Delete student from database
    $sql_delete = "DELETE FROM student WHERE id = $student_id";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="styles.css"> <!-- Replace with your CSS file -->
</head>
<body>
    <h1>Delete Student</h1>
    <p>Are you sure you want to delete student: <strong><?php echo htmlspecialchars($row['name']); ?></strong>?</p>
    <form method="POST">
        <input type="submit" value="Confirm">
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
