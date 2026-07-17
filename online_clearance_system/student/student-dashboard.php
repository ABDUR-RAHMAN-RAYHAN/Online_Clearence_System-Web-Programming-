<?php
session_start();
include "../db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}

$student_id = $_SESSION["student_id"];

$total_doc_sql = "SELECT * FROM documents WHERE student_id='$student_id'";
$total_doc_result = $conn->query($total_doc_sql);
$total_documents = $total_doc_result->num_rows;

$pending_sql = "SELECT * FROM documents WHERE student_id='$student_id' AND status='Pending'";
$pending_result = $conn->query($pending_sql);
$total_pending = $pending_result->num_rows;

$approved_sql = "SELECT * FROM documents WHERE student_id='$student_id' AND status='Approved'";
$approved_result = $conn->query($approved_sql);
$total_approved = $approved_result->num_rows;

$rejected_sql = "SELECT * FROM documents WHERE student_id='$student_id' AND status='Rejected'";
$rejected_result = $conn->query($rejected_sql);
$total_rejected = $rejected_result->num_rows;

$clearance_sql = "SELECT * FROM clearance_requests WHERE student_id='$student_id'";
$clearance_result = $conn->query($clearance_sql);

$clearance_status = "Not Submitted";
$clearance_comment = "No final clearance request submitted yet.";

if($clearance_result->num_rows > 0){
    $clearance_row = $clearance_result->fetch_assoc();
    $clearance_status = $clearance_row["status"];
    $clearance_comment = $clearance_row["comment"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
            <a class="nav-item active" href="student-dashboard.php">Dashboard</a>
            <a class="nav-item" href="apply-clearance.php">Apply</a>
            <a class="nav-item" href="upload-documents.php">Upload Documents</a>
            <a class="nav-item" href="track-approval.php">Track Approval</a>
            <a class="nav-item" href="moi-certificate.php">MOI Certificate</a>
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
            <div class="top-title">Student Dashboard</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Welcome back, <?php echo $_SESSION["student_name"]; ?>!</div>

            <div class="dash-grid">
                <a class="dash-card blue-left" href="apply-clearance.php">
                    <div class="card-icon blue-box"></div>
                    <div>
                        <div class="dash-title">Apply for Clearance</div>
                        <div class="dash-text">Submit your final clearance request</div>
                    </div>
                </a>

                <a class="dash-card green-left" href="upload-documents.php">
                    <div class="card-icon green-box"></div>
                    <div>
                        <div class="dash-title">Upload Documents</div>
                        <div class="dash-text"><?php echo $total_documents; ?> documents uploaded</div>
                    </div>
                </a>

                <a class="dash-card purple-left" href="track-approval.php">
                    <div class="card-icon purple-box"></div>
                    <div>
                        <div class="dash-title">Track Approval</div>
                        <div class="dash-text">Final status: <?php echo $clearance_status; ?></div>
                    </div>
                </a>
                <a class="dash-card red-left" href="emergency-request.php">
    <div class="card-icon red-box"></div>
    <div>
        <div class="dash-title">Emergency Request</div>
        <div class="dash-text">Submit urgent clearance request</div>
    </div>
</a>

                <div class="dash-card orange-left">
                    <div class="card-icon orange-box"></div>
                    <div>
                        <div class="dash-title">Pending Documents</div>
                        <div class="dash-text"><?php echo $total_pending; ?> documents pending</div>
                    </div>
                </div>

                <div class="dash-card teal-left">
                    <div class="card-icon teal-box"></div>
                    <div>
                        <div class="dash-title">Approved Documents</div>
                        <div class="dash-text"><?php echo $total_approved; ?> documents approved</div>
                    </div>
                </div>

                <div class="dash-card red-left">
                    <div class="card-icon red-box"></div>
                    <div>
                        <div class="dash-title">Rejected Documents</div>
                        <div class="dash-text"><?php echo $total_rejected; ?> documents rejected</div>
                    </div>
                </div>
            </div>

            <div class="activity">
                <div class="section-title">Recent Activity</div>

                <div class="activity-row">
                    <div>
                        <div>Final Clearance Request</div>
                        <div class="small-text"><?php echo $clearance_comment; ?></div>
                    </div>

                    <?php
                    if($clearance_status == "Approved"){
                        echo "<div class='status green'>Approved</div>";
                    }
                    else if($clearance_status == "Rejected"){
                        echo "<div class='status red'>Rejected</div>";
                    }
                    else{
                        echo "<div class='status yellow'>".$clearance_status."</div>";
                    }
                    ?>
                </div>

                <div class="activity-row">
                    <div>
                        <div>Uploaded Documents</div>
                        <div class="small-text">Total documents submitted by student</div>
                    </div>
                    <div class="status green"><?php echo $total_documents; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>