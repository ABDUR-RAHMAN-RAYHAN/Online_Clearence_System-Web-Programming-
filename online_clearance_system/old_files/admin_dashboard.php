<?php
session_start();
include "db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: login.php");
}

$student_sql = "SELECT * FROM students";
$student_result = $conn->query($student_sql);
$total_students = $student_result->num_rows;

$document_sql = "SELECT * FROM documents";
$document_result = $conn->query($document_sql);
$total_documents = $document_result->num_rows;

$clearance_sql = "SELECT * FROM clearance_requests";
$clearance_result = $conn->query($clearance_sql);
$total_clearance = $clearance_result->num_rows;

$pending_sql = "SELECT * FROM documents WHERE status='Pending'";
$pending_result = $conn->query($pending_sql);
$total_pending = $pending_result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_students.php">Students</a>
        <a href="admin_documents.php">Documents</a>
        <a href="admin_clearance.php">Clearance Requests</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Welcome, <?php echo $_SESSION["admin_name"]; ?></h1>
        <p>This is your admin dashboard.</p>

        <div class="cards">
            <div class="card">
                <h3>Total Students</h3>
                <p class="count-number"><?php echo $total_students; ?></p>
                <a href="admin_students.php" class="small-btn">View Students</a>
            </div>

            <div class="card">
                <h3>Total Documents</h3>
                <p class="count-number"><?php echo $total_documents; ?></p>
                <a href="admin_documents.php" class="small-btn">View Documents</a>
            </div>

            <div class="card">
                <h3>Pending Documents</h3>
                <p class="count-number"><?php echo $total_pending; ?></p>
                <a href="admin_documents.php" class="small-btn">Review</a>
            </div>

            <div class="card">
                <h3>Clearance Requests</h3>
                <p class="count-number"><?php echo $total_clearance; ?></p>
                <a href="admin_clearance.php" class="small-btn">Manage</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>