<?php
// Connect to database
include 'DBConnector.php';

    // Insert
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $name           = $_POST['name'];
        $age            = (int)$_POST['age'];
        $email          = $_POST['email'];
        $course         = $_POST['course'];
        $year_level     = (int)$_POST['year_level'];
        $graduate       = isset($_POST['graduate']) ? 1 : 0;
        
        // File upload for avatar
        if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
        $avatar = $_FILES['profile_photo']['name'];
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], "uploads/" . $avatar);

        $stmt = $conn->prepare("INSERT INTO users (name, age, email, course, year_level, avatar, graduate) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissisi", $name, $age, $email, $course, $year_level, $avatar, $graduate);

        // Redirects to homepage after submission
        if ($stmt->execute()) {
            header("Location:index.html?success=1");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();

        // Close the connection
        $conn->close();
    }
    
?>