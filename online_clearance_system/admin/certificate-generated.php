<?php
session_start();
include "../db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: admin-login.php");
}

if(!isset($_GET["request_id"])){
    header("Location: generate-certificate.php");
}

$request_id = $_GET["request_id"];

$certificate_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department AS student_department, students.batch, students.email
                    FROM clearance_requests
                    INNER JOIN students ON clearance_requests.student_id = students.student_id
                    WHERE clearance_requests.request_id='$request_id'
                    AND clearance_requests.status='Approved'";

$certificate_result = $conn->query($certificate_sql);

if($certificate_result->num_rows == 0){
    echo "Certificate request not found or not approved.";
    exit();
}

$certificate = $certificate_result->fetch_assoc();

$certificate_id = "CERT-" . date("Y") . "-00" . $certificate["request_id"];
$issue_date = date("F d, Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clearance Certificate Generated</title>
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
            <a class="nav-item active" href="generate-certificate.php">Generate Certificate</a>
            <a class="nav-item" href="moi-certificate-management.php">MOI Certificate</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Clearance Certificate Generated</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="success-card admin-success">
                <div class="success-icon">✓</div>

                <h1>Certificate Generated Successfully!</h1>
                <p>The clearance certificate has been generated for the student.</p>

                <div class="details-box">
                    <h3>Certificate Details</h3>

                    <div class="detail-row">
                        <span>Student Name:</span>
                        <span><?php echo $certificate["name"]; ?></span>
                    </div>

                    <div class="detail-row">
                        <span>Student ID:</span>
                        <span><?php echo $certificate["roll"]; ?></span>
                    </div>

                    <div class="detail-row">
                        <span>Certificate ID:</span>
                        <span><?php echo $certificate_id; ?></span>
                    </div>

                    <div class="detail-row">
                        <span>Department:</span>
                        <span><?php echo $certificate["student_department"]; ?></span>
                    </div>

                    <div class="detail-row">
                        <span>Batch:</span>
                        <span><?php echo $certificate["batch"]; ?></span>
                    </div>

                    <div class="detail-row">
                        <span>Clearance Type:</span>
                        <span><?php echo $certificate["clearance_type"]; ?></span>
                    </div>

                    <div class="detail-row">
                        <span>Issue Date:</span>
                        <span><?php echo $issue_date; ?></span>
                    </div>

                    <div class="detail-row">
                        <span>Status:</span>
                        <span class="status green">Issued</span>
                    </div>
                </div>

                <div class="certificate-preview-box">
                    <div class="doc-icon">▤</div>

                    <div>
                        <h3>Clearance Certificate</h3>
                        <p>Generated for <?php echo $certificate["name"]; ?></p>
                    </div>

                    <button class="blue-btn full-btn" onclick="window.print()">Print / Save Certificate</button>
                </div>

                <div class="success-info">
                    <h3>Actions Completed:</h3>
                    <ul>
                        <li>Certificate generated with unique certificate ID.</li>
                        <li>Student clearance information verified from approved request.</li>
                        <li>Certificate is ready to print or save as PDF.</li>
                        <li>Request remains stored in system records.</li>
                    </ul>
                </div>

                <a class="dark-btn full-btn" href="admin-dashboard.php">Back to Dashboard</a>
            </div>

            <div class="print-certificate-area">
                <div class="real-certificate">
                    <h1>Clearance Certificate</h1>
                    <h3>Online Clearance System</h3>

                    <p>This is to certify that</p>

                    <h2><?php echo $certificate["name"]; ?></h2>

                    <p>
                        Student ID <b><?php echo $certificate["roll"]; ?></b>,
                        Department of <b><?php echo $certificate["student_department"]; ?></b>,
                        Batch <b><?php echo $certificate["batch"]; ?></b>,
                        has successfully completed the clearance process.
                    </p>

                    <p>
                        Certificate ID: <b><?php echo $certificate_id; ?></b>
                    </p>

                    <p>
                        Issue Date: <b><?php echo $issue_date; ?></b>
                    </p>

                    <div class="certificate-sign-row">
                        <div>
                            <div class="signature-line"></div>
                            <p>Admin Signature</p>
                        </div>

                        <div>
                            <div class="signature-line"></div>
                            <p>University Authority</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>