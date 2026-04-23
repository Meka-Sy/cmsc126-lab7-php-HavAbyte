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

    // Close the connection
    $conn->close();

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
        email VARCHAR(40), NOT NULL UNIQUE CHECK (email LIKE '%@%.%'),
        course VARCHAR(40) NOT NULL,
        year_level INT(1) CHECK (year_level >= 1 AND year_level <= 4),
        avatar VARCHAR(255) NOT NULL
        graduate BOOLEAN,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )"; 

    // Checking table
    if ($conn->query($sql) === TRUE) { 
        echo "Database table created successfully"; 
    } else { 
        echo "Error creating table: " . $conn->error; 
    } 

    // Insert
    $sql .= "INSERT INTO Users (name, age, email, course, year_level, graduate) 
            VALUES ();"

    // Select
?>