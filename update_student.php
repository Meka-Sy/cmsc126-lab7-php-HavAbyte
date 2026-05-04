<?php
$conn = new mysqli("localhost", "root", "", "univ_sys");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'] ?? '';

if (empty($query)) {
    die("No student selected.");
}

if (is_numeric($query)) {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE id=?");
    $stmt->bind_param("i", $query);
} else {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE name=?");
    $stmt->bind_param("s", $query);
}

$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Student not found.");
}

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $year = $_POST['year_level'];
    $grad = isset($_POST['graduate']) ? 1 : 0;

    $stmt = $conn->prepare("
        UPDATE Users 
        SET name=?, age=?, email=?, course=?, year_level=?, graduate=? 
        WHERE id=?
    ");

    $stmt->bind_param("sissiii", $name, $age, $email, $course, $year, $grad, $id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Student updated successfully!</p>";
        echo "<a href='index.html'>Go back</a>";
    } else {
        echo "Update failed.";
    }

    exit;
}
?>

<h2>Update Student</h2>

<form method="POST" action="update_student.php">

    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>"><br><br>

    <label>Age:</label>
    <input type="number" name="age" value="<?= $data['age'] ?>"><br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>"><br><br>

    <label>Course:</label>
    <input type="text" name="course" value="<?= htmlspecialchars($data['course']) ?>"><br><br>

    <label>Year Level:</label>
    <select name="year_level">
        <option value="1" <?= $data['year_level']==1?'selected':'' ?>>1</option>
        <option value="2" <?= $data['year_level']==2?'selected':'' ?>>2</option>
        <option value="3" <?= $data['year_level']==3?'selected':'' ?>>3</option>
        <option value="4" <?= $data['year_level']==4?'selected':'' ?>>4</option>
    </select><br><br>

    <label>
        <input type="checkbox" name="graduate" value="1" <?= $data['graduate'] ? 'checked' : '' ?>>
        Graduated
    </label><br><br>

    <button type="submit" name="update">Save Changes</button>

</form>

