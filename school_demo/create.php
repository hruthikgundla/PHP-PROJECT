<?php
// create.php

// Database connection (same as index.php)

// Fetch classes for dropdown

<?php
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

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    
    // Image upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a valid format
    $valid_extensions = array('jpg', 'png');
    if (!in_array($imageFileType, $valid_extensions)) {
        die("Sorry, only JPG, PNG files are allowed.");
    }

    // Move uploaded file to designated directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert new student into database
        $sql_insert = "INSERT INTO student (name, email, address, class_id, image) VALUES ('$name', '$email', '$address', '$class_id', '$target_file')";
        if ($conn->query($sql_insert) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}
// Process form submission for creating a new student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    
    // Image upload handling
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
            // Insert new student into database with image path
            $sql_insert = "INSERT INTO student (name, email, address, class_id, image) VALUES ('$name', '$email', '$address', '$class_id', '$target_file')";
            if ($conn->query($sql_insert) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        // Handle case where no image is uploaded (optional)
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Student</title>
    <link rel="stylesheet" href="styles.css"> <!-- Replace with your CSS file -->
</head>
<body>
    <h1>Add New Student</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>
        
        <label>Email:</label><br>
        <input type="email" name="email"><br><br>
        
        <label>Address:</label><br>
        <textarea name="address"></textarea><br><br>
        
        <label>Class:</label><br>
        <select name="class_id">
            <?php while ($row = $result_classes->fetch_assoc()): ?>
                <option value="<?php echo $row['class_id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php endwhile; ?>
        </select><br><br>
        
        <label>Upload Image:</label><br>
        <input type="file" name="image"><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
