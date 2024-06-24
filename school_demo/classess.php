<?php
// classes.php

// Database connection (same as index.php)

// Fetch all classes

$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Leave empty if no password set for MySQL
$db_name = 'school_database'; // Replace with your database name

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




$sql_classes = "SELECT * FROM classes";
$result_classes = $conn->query($sql_classes);

// Form submission handling (Add new class)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_class'])) {
    $class_name = $_POST['class_name'];
    
    // Insert new class into database
    $sql_insert = "INSERT INTO classes (name) VALUES ('$class_name')";
    if ($conn->query($sql_insert) === TRUE) {
        header("Location: classes.php");
        exit();
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

// Form submission handling (Delete class)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_class'])) {
    $class_id = $_POST['class_id'];
    
    // Check if any students are associated with the class
    $sql_check_students = "SELECT * FROM student WHERE class_id = $class_id";
    $result_check_students = $conn->query($sql_check_students);
    
    if ($result_check_students->num_rows > 0) {
        echo "Cannot delete class because it is associated with one or more students.";
    } else {
        // Delete class from database
        $sql_delete = "DELETE FROM classes WHERE class_id = $class_id";
        if ($conn->query($sql_delete) === TRUE) {
            header("Location: classes.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="styles.css"> <!-- Replace with your CSS file -->
</head>
<body>
    <h1>Manage Classes</h1>
    
    <!-- Add New Class Form -->
    <h2>Add New Class</h2>
    <form method="POST">
        <label>Class Name:</label>
        <input type="text" name="class_name" required>
        <input type="submit" name="add_class" value="Add Class">
    </form>
    
    <hr>
    
    <!-- List of Classes -->
    <h2>List of Classes</h2>
    <ul>
        <?php while ($class = $result_classes->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($class['name']); ?>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
                    <input type="submit" name="delete_class" value="Delete">
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
    
    <a href="index.php">Back to Home</a>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
