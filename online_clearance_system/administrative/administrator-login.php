<?php
session_start();
include "../db.php";

$message = "";

if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM administrative_users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        $_SESSION["administrative_id"] = $row["administrative_id"];
        $_SESSION["administrative_name"] = $row["name"];
        $_SESSION["administrative_department"] = $row["department"];

        if($row["department"] == "Library"){
            header("Location: library-dashboard.php");
            exit();
        }
        else if($row["department"] == "Accounts"){
            header("Location: accounts-dashboard.php");
            exit();
        }
        else if($row["department"] == "Hostel"){
            header("Location: hostel-dashboard.php");
            exit();
        }
        else if($row["department"] == "Gym"){
            header("Location: gym-dashboard.php");
            exit();
        }
       else{
           $message = "Your department account is not properly assigned. Please contact admin.";
        }
    }
    else{
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrative Login</title>
    <link rel="stylesheet" type="text/css" href="./administrative.css">
</head>
<body class="login-body">

<div class="login-main">
    <div class="admin-login-icon">✓</div>

    <div class="login-system">Online Clearance System</div>
    <div class="admin-login-title">Department Administrator<br>Login</div>
    <div class="login-subtitle">Review and approve clearance requests</div>

    <div class="login-card">
        <?php
        if($message != ""){
            echo "<div class='login-message'>".$message."</div>";
        }
        ?>

        <form method="POST">
            <div class="form-row">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-row">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="login" class="admin-login-btn">
                Log In
            </button>
        </form>

        <div class="admin-forgot">Forgot Password?</div>
    </div>

    <a class="admin-back-link" href="../index.php">← Back to role selection</a>
</div>

</body>
</html>