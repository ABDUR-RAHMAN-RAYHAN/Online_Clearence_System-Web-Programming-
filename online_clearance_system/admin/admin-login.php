<?php
session_start();
include "../db.php";

$message = "";

if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();

        $_SESSION["admin_id"] = $row["admin_id"];
        $_SESSION["admin_name"] = $row["name"];

        header("Location: admin-dashboard.php");
    }
    else{
        $message = "Invalid admin email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="login-body">
    <div class="login-main">
        <div class="admin-icon">⚙</div>

        <div class="login-system">Online Clearance System</div>
        <div class="login-title">Admin Portal Login</div>
        <div class="login-subtitle">Manage system and generate certificates</div>

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
        </div>

        <a class="back-link" href="../index.php">← Back to role selection</a>
    </div>
</body>
</html>