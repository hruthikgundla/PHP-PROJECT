# School Demo Project

This project is a simple PHP application to manage students and classes.

## Setup Instructions

### Prerequisites

- PHP >= 7.0
- MySQL
- Web server (Apache, Nginx, etc.)
- phpMyAdmin (optional, for easier database management)

### Database Setup

1. **Create a Database**:
   - Create a new database named `school_db` using your preferred method (phpMyAdmin, MySQL command line, etc.).

2. **Import Database Schema and Data**:
   - Import the provided SQL file into your newly created database.
   - Using phpMyAdmin:
     - Select the `school_db` database.
     - Click on the "Import" tab.
     - Choose the `school_db.sql` file located in the `database` folder.
     - Click "Go" to import the database schema and data.

### Project Setup

1. **Clone the Repository**:
   - Clone this repository to your local machine:
     ```sh
     git clone <repository_url>
     ```

2. **Configure Database Connection**:
   - Open the `db_connection.php` file.
   - Update the database credentials (`$servername`, `$username`, `$password`, `$dbname`) to match your local database setup.

     ```php
     <?php
     $servername = "localhost";
     $username = "your_db_username";
     $password = "your_db_password";
     $dbname = "school_db";

     // Create connection
     $conn = new mysqli($servername, $username, $password, $dbname);

     // Check connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
     ?>
     ```

3. **Run the Application**:
   - Ensure your web server is running.
   - Navigate to the project directory in your web browser. For example, if using `localhost`, go to:
     ```
     http://localhost/school_demo/index.php
     ```

### Usage

- **Home Page (index.php)**: View the list of students.
- **Add New Student (create.php)**: Add a new student.
- **View Student (view.php)**: View details of a student.
- **Edit Student (edit.php)**: Edit student details.
- **Delete Student (delete.php)**: Delete a student.
- **Manage Classes (classes.php)**: Add, edit, and delete classes.

### Troubleshooting

- Ensure the `uploads` directory is writable by the web server:
  ```sh
  chmod 755 uploads
