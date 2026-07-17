<?php
session_start();
include "../db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: admin-login.php");
}

$admin_id = $_SESSION["admin_id"];
$message = "";
$password_message = "";

$admin_sql = "SELECT * FROM admins WHERE admin_id='$admin_id'";
$admin_result = $conn->query($admin_sql);
$admin = $admin_result->fetch_assoc();

if(isset($_POST["update_profile"])){
    $name = $_POST["name"];
    $email = $_POST["email"];

    $update_sql = "UPDATE admins 
                   SET name='$name', email='$email'
                   WHERE admin_id='$admin_id'";

    if($conn->query($update_sql)){
        $_SESSION["admin_name"] = $name;
        $message = "Admin profile updated successfully.";

        $admin_sql = "SELECT * FROM admins WHERE admin_id='$admin_id'";
        $admin_result = $conn->query($admin_sql);
        $admin = $admin_result->fetch_assoc();
    }
    else{
        $message = "Something went wrong while updating profile.";
    }
}

if(isset($_POST["update_password"])){
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if($old_password != $admin["password"]){
        $password_message = "Old password is incorrect.";
    }
    else if($new_password != $confirm_password){
        $password_message = "New password and confirm password do not match.";
    }
    else{
        $password_sql = "UPDATE admins 
                         SET password='$new_password'
                         WHERE admin_id='$admin_id'";

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
    <title>Admin Settings</title>
    <link rel="stylesheet" type="text/css" href="./admin.css">
</head>
<body>

<div class="page">
    <div class="left">
        <div class="profile">
            <div class="profile-icon"></div>
            <div>
                <div>Admin⌄</div>
                <div class="small-name"><?php echo $_SESSION["admin_name"]; ?></div>
            </div>
        </div>

        <div class="nav">
            <a class="nav-item" href="admin-dashboard.php">Dashboard</a>
            <a class="nav-item" href="student-clearance-profile.php">Student Profiles</a>
            <a class="nav-item" href="clearance-requests.php">Clearance Requests</a>
            <a class="nav-item" href="reports.php">Reports</a>
            <a class="nav-item" href="generate-certificate.php">Generate Certificate</a>
            <a class="nav-item" href="moi-certificate-management.php">MOI Certificate</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item active" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Admin Settings</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Account Settings</div>

            <div class="settings-grid">
                <div class="frontend-card">
                    <div class="section-title">Update Admin Profile</div>

                    <?php
                    if($message != ""){
                        echo "<div class='success-message'>".$message."</div>";
                    }
                    ?>

                    <form method="POST" class="frontend-form">
                        <label>Name</label>
                        <input type="text" name="name" value="<?php echo $admin["name"]; ?>" required>

                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $admin["email"]; ?>" required>

                        <button type="submit" name="update_profile" onclick="return confirm('Update admin profile?')">
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

                        <button type="submit" name="update_password" onclick="return confirm('Change admin password?')">
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