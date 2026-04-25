<?php
    $servername = "localhost";
    $username = "root";            
    $password = "";              

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully <br/>";

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS univ_sys";

    if ($conn->query($sql) === TRUE){
        echo "Database created successfully <br>";
    } else {
        echo "Error creating database: " . $conn->error;
    }

    // Select database before making tables
    $conn->select_db("univ_sys");


    // TABLE 1 
    $sql = "CREATE TABLE IF NOT EXISTS Users(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    age INT(2),
    email VARCHAR(40) UNIQUE,
    course VARCHAR(40) NOT NULL,
    year_level INT(1), 
    photo VARCHAR(255),
    graduate BOOLEAN,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if($conn->query($sql) === TRUE){
        echo 'Users table created successfully <br>';
    }else{
        echo 'Error creating Users table: ' . $conn->error;
    }


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
        echo 'Error creating Enrollment table: ' . $conn->error;
    }

    // Close the connection
    $conn->close();
?>