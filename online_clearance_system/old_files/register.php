<?php
include "db.php";

$message = "";

if(isset($_POST["register"])){
    $name = $_POST["name"];
    $roll = $_POST["roll"];
    $department = $_POST["department"];
    $batch = $_POST["batch"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $check = "SELECT * FROM students WHERE email='$email'";
    $result = $conn->query($check);

    if($result->num_rows > 0){
        $message = "Email already registered!";
    }
    else{
        $sql = "INSERT INTO students (name, roll, department, batch, email, password)
                VALUES ('$name', '$roll', '$department', '$batch', '$email', '$password')";

        if($conn->query($sql) == TRUE){
            $message = "Registration successful. You can login now.";
        }
        else{
            $message = "Registration failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <div class="form-box">
        <h2>Student Registration</h2>

        <?php
        if($message != ""){
            echo "<p class='message'>$message</p>";
        }
        ?>

        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" required>

            <label>Roll</label>
            <input type="text" name="roll" required>

            <label>Department</label>
            <input type="text" name="department" required>

            <label>Batch</label>
            <input type="text" name="batch" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="register">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</div>

</body>
</html>