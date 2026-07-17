<?php
session_start();
include "../db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}

$student_id = $_SESSION["student_id"];
$message = "";

if(isset($_POST["submit_emergency"])){
    $department = $_POST["department"];
    $reason = $_POST["reason"];

    $check_sql = "SELECT * FROM clearance_requests 
                  WHERE student_id='$student_id' 
                  AND clearance_type='Emergency Clearance'
                  AND status='Pending'";
    $check_result = $conn->query($check_sql);

    if($check_result->num_rows > 0){
        $message = "You already have a pending emergency clearance request.";
    }
    else{
        $insert_sql = "INSERT INTO clearance_requests 
                       (student_id, clearance_type, department, reason, is_emergency, status, comment)
                       VALUES 
                       ('$student_id', 'Emergency Clearance', '$department', '$reason', 'Yes', 'Pending', 'Waiting for urgent admin review')";

        if($conn->query($insert_sql)){
            $message = "Emergency request submitted successfully.";
        }
        else{
            $message = "Something went wrong. Please try again.";
        }
    }
}

$emergency_sql = "SELECT * FROM clearance_requests 
                  WHERE student_id='$student_id' 
                  AND clearance_type='Emergency Clearance'
                  ORDER BY request_id DESC";
$emergency_result = $conn->query($emergency_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Emergency Request</title>
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
            <a class="nav-item active" href="emergency-request.php">Emergency Request</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="help-faq.php">Help & FAQ</a>
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Emergency Request</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Submit Emergency Clearance Request</div>

            <div class="frontend-card emergency-card">
                <div class="section-title red-heading">Emergency Clearance Form</div>

                <?php
                if($message != ""){
                    echo "<div class='success-message'>".$message."</div>";
                }
                ?>

                <form method="POST" class="frontend-form">
                    <label>Emergency Department</label>
                    <select name="department" required>
                        <option value="">Select Department</option>
                        <option value="All Departments">All Departments</option>
                        <option value="Library">Library</option>
                        <option value="Accounts">Accounts</option>
                        <option value="Hall/Hostel">Hall/Hostel</option>
                        <option value="Administration">Administration</option>
                    </select>

                    <label>Emergency Reason</label>
                    <textarea name="reason" placeholder="Example: Need urgent clearance for job interview, transfer, scholarship, visa, etc." required></textarea>

                    <button type="submit" name="submit_emergency" onclick="return confirm('Are you sure you want to submit this emergency request?')">
                        Submit Emergency Request
                    </button>
                </form>
            </div>

            <div class="frontend-card">
                <div class="section-title">My Emergency Requests</div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Department</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Admin Comment</th>
                            <th>Date</th>
                        </tr>

                        <?php
                        if($emergency_result->num_rows > 0){
                            while($row = $emergency_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$row["department"]."</td>";
                                echo "<td>".$row["reason"]."</td>";

                                if($row["status"] == "Approved"){
                                    echo "<td><span class='status green'>Approved</span></td>";
                                }
                                else if($row["status"] == "Rejected"){
                                    echo "<td><span class='status red'>Rejected</span></td>";
                                }
                                else{
                                    echo "<td><span class='status yellow'>Pending</span></td>";
                                }

                                echo "<td>".$row["comment"]."</td>";
                                echo "<td>".$row["request_date"]."</td>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='5'>No emergency request submitted yet.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>