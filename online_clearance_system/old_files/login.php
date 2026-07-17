<?php
session_start();
include "db.php";

$message = "";

if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if($role == "student"){
        $sql = "SELECT * FROM students WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if($result->num_rows == 1){
            $row = $result->fetch_assoc();

            $_SESSION["student_id"] = $row["student_id"];
            $_SESSION["student_name"] = $row["name"];

            header("Location: student_dashboard.php");
        }
        else{
            $message = "Invalid student email or password!";
        }
    }
    else if($role == "administrative"){
        $sql = "SELECT * FROM administrative_users WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if($result->num_rows == 1){
            $row = $result->fetch_assoc();

            $_SESSION["administrative_id"] = $row["administrative_id"];
            $_SESSION["administrative_name"] = $row["name"];

            header("Location: administrative_dashboard.php");
        }
        else{
            $message = "Invalid administrative email or password!";
        }
    }
    else if($role == "admin"){
        $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if($result->num_rows == 1){
            $row = $result->fetch_assoc();

            $_SESSION["admin_id"] = $row["admin_id"];
            $_SESSION["admin_name"] = $row["name"];

            header("Location: admin_dashboard.php");
        }
        else{
            $message = "Invalid admin email or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <div class="form-box">
        <h2>Login</h2>

        <?php
        if($message != ""){
            echo "<p class='message error'>$message</p>";
        }
        ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Login As</label>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="administrative">Administrative</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" name="login">Login</button>
        </form>

        <p>New student? <a href="register.php">Register here</a></p>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</div>

</body>
</html>