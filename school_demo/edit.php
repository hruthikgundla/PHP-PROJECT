<?php
// edit.php

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

// Fetch classes for dropdown
$sql_classes = "SELECT * FROM classes";
$result_classes = $conn->query($sql_classes);

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    
    // Handle image update
    if ($_FILES["image"]["size"] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a valid format
        $valid_extensions = array('jpg', 'png');
        if (!in_array($imageFileType, $valid_extensions)) {
            die("Sorry, only JPG, PNG files are allowed.");
        }
        
        // Move uploaded file to designated directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update student with new image path
            $sql_update = "UPDATE student SET name='$name', email='$email', address='$address', class_id='$class_id', image='$target_file' WHERE id=$student_id";
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        // Update student without changing image
        $sql_update = "UPDATE student SET name='$name', email='$email', address='$address', class_id='$class_id' WHERE id=$student_id";
    }
    
    if ($conn->query($sql_update) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
// Process form submission for editing an existing student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];

    // Handle image update
    if ($_FILES["image"]["size"] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . '_' . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid format
        $valid_extensions = array('jpg', 'png');
        if (!in_array($imageFileType, $valid_extensions)) {
            die("Sorry, only JPG, PNG files are allowed.");
        }

        // Move uploaded file to designated directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update student with new image path
            $sql_update = "UPDATE student SET name='$name', email='$email', address='$address', class_id='$class_id', image='$target_file' WHERE id=$student_id";
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        // Update student without changing image
        $sql_update = "UPDATE student SET name='$name', email='$email', address='$address', class_id='$class_id' WHERE id=$student_id";
    }

    // Execute SQL update query
    if ($conn->query($sql_update) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="styles.css"> <!-- Replace with your CSS file -->
</head>
<body>
    <h1>Edit Student</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"><br><br>
        
        <label>Address:</label><br>
        <textarea name="address"><?php echo htmlspecialchars($row['address']); ?></textarea><br><br>
        
        <label>Class:</label><br>
        <select name="class_id">
            <?php while ($class = $result_classes->fetch_assoc()): ?>
                <option value="<?php echo $class['class_id']; ?>" <?php if ($row['class_id'] == $class['class_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($class['name']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>
        
        <label>Current Image:</label><br>
        <?php if (!empty($row['image'])): ?>
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Current Image" width="200"><br><br>
        <?php endif; ?>
        
        <label>Upload New Image:</label><br>
        <input type="file" name="image"><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
