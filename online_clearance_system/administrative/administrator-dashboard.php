<?php
session_start();
include "../db.php";

if(!isset($_SESSION["administrative_id"])){
    header("Location: administrator-login.php");
}

$document_sql = "SELECT * FROM documents";
$document_result = $conn->query($document_sql);
$total_documents = $document_result->num_rows;

$pending_sql = "SELECT * FROM documents WHERE status='Pending'";
$pending_result = $conn->query($pending_sql);
$total_pending = $pending_result->num_rows;

$library_sql = "SELECT * FROM documents WHERE document_name='Library Clearance'";
$library_result = $conn->query($library_sql);
$total_library = $library_result->num_rows;

$accounts_sql = "SELECT * FROM documents WHERE document_name='Accounts Clearance'";
$accounts_result = $conn->query($accounts_sql);
$total_accounts = $accounts_result->num_rows;

$hostel_sql = "SELECT * FROM documents WHERE document_name='Hall Clearance'";
$hostel_result = $conn->query($hostel_sql);
$total_hostel = $hostel_result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrative Dashboard</title>
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
            <a class="nav-item active" href="administrator-dashboard.php">Dashboard</a>
            <a class="nav-item" href="library-dashboard.php">Library</a>
            <a class="nav-item" href="accounts-dashboard.php">Accounts</a>
            <a class="nav-item" href="hostel-dashboard.php">Hostel</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Administrative Dashboard</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Welcome back, <?php echo $_SESSION["administrative_name"]; ?>!</div>

            <div class="dash-grid">
                <a class="dash-card blue-left" href="library-dashboard.php">
                    <div class="card-icon blue-box"></div>
                    <div>
                        <div class="dash-title">Library Clearance</div>
                        <div class="dash-text"><?php echo $total_library; ?> library documents</div>
                    </div>
                </a>

                <a class="dash-card green-left" href="accounts-dashboard.php">
                    <div class="card-icon green-box"></div>
                    <div>
                        <div class="dash-title">Accounts Clearance</div>
                        <div class="dash-text"><?php echo $total_accounts; ?> accounts documents</div>
                    </div>
                </a>

                <a class="dash-card purple-left" href="hostel-dashboard.php">
                    <div class="card-icon purple-box"></div>
                    <div>
                        <div class="dash-title">Hostel/Hall Clearance</div>
                        <div class="dash-text"><?php echo $total_hostel; ?> hall documents</div>
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
                        <div class="dash-title">Total Documents</div>
                        <div class="dash-text"><?php echo $total_documents; ?> uploaded documents</div>
                    </div>
                </div>
            </div>

            <div class="activity">
                <div class="section-title">Administrative Overview</div>

                <div class="activity-row">
                    <div>
                        <div>Department Document Review</div>
                        <div class="small-text">Review library, accounts and hostel clearance documents</div>
                    </div>
                    <div class="status green">Active</div>
                </div>

                <div class="activity-row">
                    <div>
                        <div>Pending Review</div>
                        <div class="small-text">Documents waiting for administrative approval</div>
                    </div>
                    <div class="status yellow"><?php echo $total_pending; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>