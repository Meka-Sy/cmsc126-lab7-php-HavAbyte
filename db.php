<?php
    $servername = "localhost";      // Server name
    $username = "username";         // username
    $password = "password";         // password

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully <br/>";

    // Create database
    $sql = "CREATE DATABASE univ_sys";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: " . $conn->error;
    }

    // Create table
    $sql = "CREATE TABLE Users ( 
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(40) NOT NULL, 
        age INT(2) CHECK (age >= 0 AND age <= 99),
        email VARCHAR(40) NOT NULL UNIQUE CHECK (email LIKE '%@%.%'),
        course VARCHAR(40) NOT NULL,
        year_level INT(1) CHECK (year_level >= 1 AND year_level <= 4),
        avatar VARCHAR(255) NOT NULL,
        graduate BOOLEAN,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )"; 

    // Checking table
    if ($conn->query($sql) === TRUE) { 
        echo "Database table created successfully"; 
    } else { 
        echo "Error creating table: " . $conn->error; 
    } 

    $conn->select_db("univ_sys");

    // Insert
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $name           = $_POST['name'];
        $age            = (int)$_POST['age'];
        $email          = $_POST['email'];
        $course         = $_POST['course'];
        $year_level     = (int)$_POST['year_level'];
        $avatar         = $_POST['avatar'];  
        $graduate       = isset($_POST['graduate']) ? 1 : 0;

        $stmt = $conn->prepare("INSERT INTO users (name, age, email, course, year_level, avatar, graduate) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissisi", $name, $age, $email, $course, $year_level, $avatar, $graduate);

        if ($stmt->execute()) {
            echo "New user registered successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    
    // Select
    
    // Update
    if (isset($_POST['update'])) {
        $name       = $_POST['name'];
        $age        = (int)$_POST['age'];
        $email      = $_POST['email'];
        $course     = $_POST['course'];
        $year_level = (int)$_POST['year_level'];
        $avatar     = $_POST['avatar'];
        $graduate   = isset($_POST['graduate']) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE users SET age=?, email=?, course=?, year_level=?, avatar=?, graduate=? WHERE name=?");
        $stmt->bind_param("issisis", $age, $email, $course, $year_level, $avatar, $graduate, $name);

        if ($stmt->execute()) {
            echo "User updated successfully<br>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    // Delete
    if (isset($_POST['delete'])) {
        $name = $_POST['name'];

        $stmt = $conn->prepare("DELETE FROM users WHERE name=?");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            echo "User deleted successfully<br>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    // Close the connection
    $conn->close();
?>