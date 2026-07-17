<?php
session_start();

if(!isset($_SESSION["administrative_id"])){
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrative Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <h2>Administrative Panel</h2>
        <a href="administrative_dashboard.php">Dashboard</a>
        <a href="admin_documents.php">Documents</a>
        <a href="admin_clearance.php">Clearance Requests</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Welcome, <?php echo $_SESSION["administrative_name"]; ?></h1>
        <p>This is the administrative dashboard.</p>

        <div class="cards">
            <div class="card">
                <h3>Review Documents</h3>
                <p>Check uploaded student documents.</p>
                <a href="admin_documents.php" class="small-btn">Review</a>
            </div>

            <div class="card">
                <h3>Clearance Requests</h3>
                <p>Check student clearance requests.</p>
                <a href="admin_clearance.php" class="small-btn">Manage</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>