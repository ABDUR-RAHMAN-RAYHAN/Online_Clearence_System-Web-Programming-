<?php
include "../db.php";

$message = "";

if(isset($_POST["register"])){
    $name = $_POST["name"];
    $roll = $_POST["roll"];
    $department = $_POST["department"];
    $batch = $_POST["batch"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $check_sql = "SELECT * FROM students WHERE email='$email' OR roll='$roll'";
    $check_result = $conn->query($check_sql);

    if($check_result->num_rows > 0){
        $message = "This email or student ID is already registered.";
    }
    else{
        $insert_sql = "INSERT INTO students (name, roll, department, batch, email, password)
                       VALUES ('$name', '$roll', '$department', '$batch', '$email', '$password')";

        if($conn->query($insert_sql)){
            $message = "Registration successful. You can login now.";
        }
        else{
            $message = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>

<div class="login-body">
    <div class="login-main">
        <div class="login-card register-card">
            <h2>Student Registration</h2>
            <p>Create your student account</p>

            <?php
            if($message != ""){
                echo "<div class='login-message'>".$message."</div>";
            }
            ?>

            <form method="POST">
                <input type="text" name="name" placeholder="Full Name" required>

                <input type="text" name="roll" placeholder="Student ID / Roll" required>

                <input type="text" name="department" placeholder="Department" required>

                <input type="text" name="batch" placeholder="Batch" required>

                <input type="email" name="email" placeholder="Email Address" required>

                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="register">Register</button>
            </form>

            <div class="forgot">
                <a href="student-login.php">Already have an account? Login</a>
            </div>

            <div class="forgot">
                <a href="../index.php">Back to Home</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>