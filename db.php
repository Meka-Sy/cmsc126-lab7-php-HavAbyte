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

    // TABLE 2 
    $sql = "CREATE TABLE IF NOT EXISTS Accountability (
    accountability_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    title VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'Pending',
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id)
    ON DELETE CASCADE
    )";

    if($conn->query($sql) === TRUE){
        echo 'Accountability table created successfully';
    }else{
        echo 'Error creating Accountability table: ' . $conn->error;
    }

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

    // Redirects to homepage after submission
    if ($stmt->execute()) {
        header("Location:index.html?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    
}

// 4. Add accountability (Insert)
if (isset($_POST['submit'])) {
    $user_id = $_POST['name'];
    $title = $_POST['title'];
    $amount = (decimal) $_POST['amount'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO Accountability (user_id, title, amount, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $title, $amount, $status);
    // Redirects to homepage after submission
    if ($stmt->execute()) {
        header("Location:index.html?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    
}

// 4. Handle Delete
if (isset($_POST['delete'])) {
    $id = $_POST['studentID']; 
    $stmt = $conn->prepare("DELETE FROM Users WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Record deleted successfully";
    }
    $stmt->close();
}

if (isset($_POST['search'])) {
    // Single input field - can be either ID or Name
    $query = trim($_POST['studentID'] ?? ''); // Assuming your single field is named 'studentID'

    if (empty($query)) {
        echo "<p style='color:orange;'>Please enter a Student ID or Name to search.</p>";
    } else {
        $stmt = null;
        $result = null;

        // Detect what was entered: if it's numeric, search by ID; otherwise search by name
        if (is_numeric($query)) {
            $query = (int)$query;
            $stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $query);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                echo "<p style='color:red;'>Database error: " . $conn->error . "</p>";
            }
        } else {
            $nameParam = "%$query%";
            $stmt = $conn->prepare("SELECT * FROM Users WHERE name LIKE ?");
            if ($stmt) {
                $stmt->bind_param("s", $nameParam);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                echo "<p style='color:red;'>Database error: " . $conn->error . "</p>";
            }
        }

        // Display results
        if ($result && $result->num_rows > 0) {
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
                        <td>" . htmlspecialchars($row["id"]) . "</td>
                        <td>" . htmlspecialchars($row["name"]) . "</td>
                        <td>" . htmlspecialchars($row["email"]) . "</td>
                        <td>" . htmlspecialchars($row["course"]) . "</td>
                        <td>" . htmlspecialchars($row["year_level"]) . "</td>
                        <td>" . $gradStatus . "</td>
                      </tr>";
            }
            echo "</table>";
        } else if ($result) {
            echo "<p style='color:red;'>No record found matching: " . htmlspecialchars($query) . "</p>";
        }

        if ($stmt) {
            $stmt->close();
        }
    }
}

$conn->close();
?>