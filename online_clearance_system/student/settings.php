<?php
session_start();
include "../db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}

$student_id = $_SESSION["student_id"];
$message = "";
$password_message = "";

$student_sql = "SELECT * FROM students WHERE student_id='$student_id'";
$student_result = $conn->query($student_sql);
$student = $student_result->fetch_assoc();

if(isset($_POST["update_profile"])){
    $name = $_POST["name"];
    $roll = $_POST["roll"];
    $department = $_POST["department"];
    $batch = $_POST["batch"];
    $email = $_POST["email"];

    $update_sql = "UPDATE students 
                   SET name='$name', roll='$roll', department='$department', batch='$batch', email='$email'
                   WHERE student_id='$student_id'";

    if($conn->query($update_sql)){
        $_SESSION["student_name"] = $name;
        $message = "Profile updated successfully.";

        $student_sql = "SELECT * FROM students WHERE student_id='$student_id'";
        $student_result = $conn->query($student_sql);
        $student = $student_result->fetch_assoc();
    }
    else{
        $message = "Something went wrong while updating profile.";
    }
}

if(isset($_POST["update_password"])){
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if($old_password != $student["password"]){
        $password_message = "Old password is incorrect.";
    }
    else if($new_password != $confirm_password){
        $password_message = "New password and confirm password do not match.";
    }
    else{
        $password_sql = "UPDATE students 
                         SET password='$new_password'
                         WHERE student_id='$student_id'";

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
    <title>Settings</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>

<div class="page">
    <div class="left">
        <div class="profile">
            <div class="profile-icon"></div>
            <div>
                <div>Student⌄</div>
                <div class="small-name"><?php echo $_SESSION["student_name"]; ?></div>
            </div>
        </div>

        <div class="nav">
            <a class="nav-item" href="student-dashboard.php">Dashboard</a>
            <a class="nav-item" href="apply-clearance.php">Apply</a>
            <a class="nav-item" href="upload-documents.php">Upload Documents</a>
            
            <a class="nav-item" href="track-approval.php">Track Approval</a>
            <a class="nav-item" href="moi-certificate.php">MOI Certificate</a>
            <a class="nav-item" href="emergency-request.php">Emergency Request</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="help-faq.php">Help & FAQ</a>
            <a class="nav-item active" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Settings</div>
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
                        <input type="text" name="name" value="<?php echo $student["name"]; ?>" required>

                        <label>Roll</label>
                        <input type="text" name="roll" value="<?php echo $student["roll"]; ?>" required>

                        <label>Department</label>
                        <input type="text" name="department" value="<?php echo $student["department"]; ?>" required>

                        <label>Batch</label>
                        <input type="text" name="batch" value="<?php echo $student["batch"]; ?>" required>

                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $student["email"]; ?>" required>

                        <button type="submit" name="update_profile" onclick="return confirm('Update your profile information?')">
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

                        <button type="submit" name="update_password" onclick="return confirm('Change your password?')">
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