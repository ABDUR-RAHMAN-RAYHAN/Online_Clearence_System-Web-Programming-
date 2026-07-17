<?php
session_start();
include "../db.php";

$message = "";

if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM students WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();

        $_SESSION["student_id"] = $row["student_id"];
        $_SESSION["student_name"] = $row["name"];

        header("Location: student-dashboard.php");
    }
    else{
        $message = "Invalid student email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-main">
        <div class="book-icon">▤</div>
        <div class="login-system">Online Clearance System</div>
        <div class="login-title">Student Portal Login</div>
        <div class="login-subtitle">Access your clearance dashboard</div>

        <div class="login-card">

            <?php
            if($message != ""){
                echo "<p class='login-message'>$message</p>";
            }
            ?>

            <form method="POST">
                <div class="form-row">
                    <label>Email Address</label>
                    <input type="text" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-row">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <button class="login-btn" type="submit" name="login">Log In</button>
            </form>

            <div class="forgot">Forgot Password?</div>
            <div class="forgot">
    <a href="student-register.php">Create Student Account</a>
</div>
            
        </div>

        <a class="back-link" href="../index.php">← Back to role selection</a>
    </div>
</body>
</html>