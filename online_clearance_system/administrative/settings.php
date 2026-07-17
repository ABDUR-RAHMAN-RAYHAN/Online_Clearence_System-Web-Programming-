<?php
session_start();
include "../db.php";

if(!isset($_SESSION["administrative_id"])){
    header("Location: administrator-login.php");
}

$administrative_id = $_SESSION["administrative_id"];
$message = "";
$password_message = "";

$user_sql = "SELECT * FROM administrative_users WHERE administrative_id='$administrative_id'";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

$dashboard_page = "administrator-dashboard.php";
$dashboard_title = "Dashboard";

if($_SESSION["administrative_department"] == "Library"){
    $dashboard_page = "library-dashboard.php";
    $dashboard_title = "Library Dashboard";
}
else if($_SESSION["administrative_department"] == "Accounts"){
    $dashboard_page = "accounts-dashboard.php";
    $dashboard_title = "Accounts Dashboard";
}
else if($_SESSION["administrative_department"] == "Hostel"){
    $dashboard_page = "hostel-dashboard.php";
    $dashboard_title = "Hostel Dashboard";
}
else if($_SESSION["administrative_department"] == "Gym"){
    $dashboard_page = "gym-dashboard.php";
    $dashboard_title = "Gym Dashboard";
}

if(isset($_POST["update_profile"])){
    $name = $_POST["name"];
    
    $email = $_POST["email"];

    $update_sql = "UPDATE administrative_users 
               SET name='$name', email='$email'
               WHERE administrative_id='$administrative_id'";

    if($conn->query($update_sql)){
        $_SESSION["administrative_name"] = $name;
        $message = "Administrative profile updated successfully.";

        $user_sql = "SELECT * FROM administrative_users WHERE administrative_id='$administrative_id'";
        $user_result = $conn->query($user_sql);
        $user = $user_result->fetch_assoc();
    }
    else{
        $message = "Something went wrong while updating profile.";
    }
}

if(isset($_POST["update_password"])){
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if($old_password != $user["password"]){
        $password_message = "Old password is incorrect.";
    }
    else if($new_password != $confirm_password){
        $password_message = "New password and confirm password do not match.";
    }
    else{
        $password_sql = "UPDATE administrative_users 
                         SET password='$new_password'
                         WHERE administrative_id='$administrative_id'";

        if($conn->query($password_sql)){
            $password_message = "Password updated successfully.";
        }
        else{
            $password_message = "Something went wrong while updating password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrative Settings</title>
    <link rel="stylesheet" type="text/css" href="./administrative.css">
</head>
<body>

<div class="page">
    <div class="left">
        <div class="profile">
            <div class="profile-icon"></div>
            <div>
                <div>Administrative⌄</div>
                <div class="small-name"><?php echo $_SESSION["administrative_name"]; ?></div>
            </div>
        </div>

        <div class="nav">
    <a class="nav-item" href="<?php echo $dashboard_page; ?>">
        <?php echo $dashboard_title; ?>
    </a>
</div>

        <div class="bottom-nav">
            <a class="nav-item active" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Administrative Settings</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Account Settings</div>

            <div class="settings-grid">
                <div class="frontend-card">
                    <div class="section-title">Update Profile</div>

                    <?php
                    if($message != ""){
                        echo "<div class='success-message'>".$message."</div>";
                    }
                    ?>

                    <form method="POST" class="frontend-form">
                        <label>Name</label>
                        <input type="text" name="name" value="<?php echo $user["name"]; ?>" required>

                        <label>Department</label>
                       <input type="text" value="<?php echo $user["department"]; ?>" readonly>

                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $user["email"]; ?>" required>

                        <button type="submit" name="update_profile" onclick="return confirm('Update administrative profile?')">
                            Update Profile
                        </button>
                    </form>
                </div>

                <div class="frontend-card">
                    <div class="section-title">Change Password</div>

                    <?php
                    if($password_message != ""){
                        echo "<div class='success-message'>".$password_message."</div>";
                    }
                    ?>

                    <form method="POST" class="frontend-form">
                        <label>Old Password</label>
                        <input type="password" name="old_password" required>

                        <label>New Password</label>
                        <input type="password" name="new_password" required>

                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>

                        <button type="submit" name="update_password" onclick="return confirm('Change administrative password?')">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>