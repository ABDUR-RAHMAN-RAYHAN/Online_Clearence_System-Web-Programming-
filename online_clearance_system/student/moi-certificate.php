<?php
session_start();
include "../db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}

$student_id = $_SESSION["student_id"];
$message = "";

if(isset($_POST["submit_moi"])){
    $reason = $_POST["reason"];

    $check_sql = "SELECT * FROM clearance_requests 
                  WHERE student_id='$student_id' 
                  AND clearance_type='MOI Certificate'
                  AND status='Pending'";
    $check_result = $conn->query($check_sql);

    if($check_result->num_rows > 0){
        $message = "You already have a pending MOI Certificate request.";
    }
    else{
        $insert_sql = "INSERT INTO clearance_requests 
                       (student_id, clearance_type, department, reason, is_emergency, status, comment)
                       VALUES 
                       ('$student_id', 'MOI Certificate', 'Administration', '$reason', 'No', 'Pending', 'Waiting for admin review')";

        if($conn->query($insert_sql)){
            $message = "MOI Certificate request submitted successfully.";
        }
        else{
            $message = "Something went wrong. Please try again.";
        }
    }
}

$moi_sql = "SELECT * FROM clearance_requests 
            WHERE student_id='$student_id' 
            AND clearance_type='MOI Certificate'
            ORDER BY request_id DESC";
$moi_result = $conn->query($moi_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MOI Certificate</title>
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
            <a class="nav-item active" href="moi-certificate.php">MOI Certificate</a>
            <a class="nav-item" href="emergency-request.php">Emergency Request</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="help-faq.php">Help & FAQ</a>
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">MOI Certificate</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Request MOI Certificate</div>

            <div class="frontend-card">
                <div class="section-title">MOI Certificate Application</div>

                <?php
                if($message != ""){
                    echo "<div class='success-message'>".$message."</div>";
                }
                ?>

                <form method="POST" class="frontend-form">
                    <label>Reason for MOI Certificate</label>
                    <textarea name="reason" placeholder="Example: Need MOI certificate for higher studies / job / embassy purpose..." required></textarea>

                    <button type="submit" name="submit_moi" onclick="return confirm('Submit MOI Certificate request?')">
                        Submit MOI Request
                    </button>
                </form>
            </div>

            <div class="frontend-card">
                <div class="section-title">My MOI Requests</div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Admin Comment</th>
                            <th>Date</th>
                        </tr>

                        <?php
                        if($moi_result->num_rows > 0){
                            while($row = $moi_result->fetch_assoc()){
                                echo "<tr>";
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
                            echo "<tr><td colspan='4'>No MOI request submitted yet.</td></tr>";
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