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
?>