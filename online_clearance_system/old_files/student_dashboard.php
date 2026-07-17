<?php
session_start();
include "db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: login.php");
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

if($clearance_result->num_rows > 0){
    $clearance_row = $clearance_result->fetch_assoc();
    $clearance_status = $clearance_row["status"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <h2>Student Panel</h2>
        <a href="student_dashboard.php">Dashboard</a>
        <a href="student_upload.php">Upload Documents</a>
        <a href="student_clearance.php">Clearance Status</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Welcome, <?php echo $_SESSION["student_name"]; ?></h1>
        <p>This is your student dashboard.</p>

        <div class="cards">
            <div class="card">
                <h3>Total Documents</h3>
                <p class="count-number"><?php echo $total_documents; ?></p>
                <a href="student_upload.php" class="small-btn">View</a>
            </div>

            <div class="card">
                <h3>Pending Documents</h3>
                <p class="count-number"><?php echo $total_pending; ?></p>
                <a href="student_upload.php" class="small-btn">Check</a>
            </div>

            <div class="card">
                <h3>Approved Documents</h3>
                <p class="count-number"><?php echo $total_approved; ?></p>
                <a href="student_upload.php" class="small-btn">View</a>
            </div>

            <div class="card">
                <h3>Rejected Documents</h3>
                <p class="count-number"><?php echo $total_rejected; ?></p>
                <a href="student_upload.php" class="small-btn">View</a>
            </div>
        </div>

        <div class="status-box">
            <h2>Final Clearance Status</h2>
            <p><?php echo $clearance_status; ?></p>
            <a href="student_clearance.php" class="small-btn">Go to Clearance Page</a>
        </div>
    </div>
</div>

</body>
</html>