<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "univ_sys";

// 1. Create connection
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Initialize Database and Table
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$tableSql = "CREATE TABLE IF NOT EXISTS Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    age INT(2),
    email VARCHAR(40) NOT NULL UNIQUE,
    course VARCHAR(40) NOT NULL,
    year_level INT(1),
    avatar VARCHAR(255) NOT NULL,
    graduate BOOLEAN,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($tableSql);

// 3. Handle Registration (Insert)
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $age = (int)$_POST['age'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $year_level = (int)$_POST['year_level'];
    $graduate = isset($_POST['graduate']) ? 1 : 0;

    // Handle File Upload
    if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
    $avatar = $_FILES['profile_photo']['name'];
    move_uploaded_file($_FILES['profile_photo']['tmp_name'], "uploads/" . $avatar);

    $stmt = $conn->prepare("INSERT INTO Users (name, age, email, course, year_level, avatar, graduate) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissisi", $name, $age, $email, $course, $year_level, $avatar, $graduate);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>User registered successfully!</p>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// 4. Handle Delete
if (isset($_POST['delete'])) {
    // Note: In a real app, you'd use ID. Here we use Name as per your structure.
    $id = $_POST['studentID']; 
    $stmt = $conn->prepare("DELETE FROM Users WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Record deleted successfully";
    }
    $stmt->close();
}

// 5. Display Table
if (isset($_POST['search'])) {
    $studentID = $_POST['studentID'] ?? '';
    $studentName = $_POST['studentName'] ?? '';
    // Create the base query
    if (!empty($studentID)) {
        // Search by ID
        $stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
        $stmt->bind_param("i", $studentID);
    } elseif (!empty($studentName)) {
        // Search by Name (using LIKE for partial matches)
        $nameParam = "%$studentName%";
        $stmt = $conn->prepare("SELECT * FROM Users WHERE name LIKE ?");
        $stmt->bind_param("s", $nameParam);
    } else {
        echo "<p style='color:orange;'>Please enter an ID or Name to search.</p>";
        return; // Exit if both are empty
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<h2>Record Found</h2>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Status</th>
                </tr>";
        
        while($row = $result->fetch_assoc()) {
            $gradStatus = $row["graduate"] ? "Graduated" : "Undergraduate";
            echo "<tr>
                    <td>".$row["id"]."</td>
                    <td>".$row["name"]."</td>
                    <td>".$row["email"]."</td>
                    <td>".$row["course"]."</td>
                    <td>".$row["year_level"]."</td>
                    <td>".$gradStatus."</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red;'>No record found matching those criteria.</p>";
    }
    $stmt->close();
}
$conn->close();
?>
