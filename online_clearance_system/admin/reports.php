<?php
session_start();
include "../db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: admin-login.php");
}

$student_sql = "SELECT * FROM students";
$student_result = $conn->query($student_sql);
$total_students = $student_result->num_rows;

$document_sql = "SELECT * FROM documents";
$document_result = $conn->query($document_sql);
$total_documents = $document_result->num_rows;

$request_sql = "SELECT * FROM clearance_requests";
$request_result = $conn->query($request_sql);
$total_requests = $request_result->num_rows;

$approved_sql = "SELECT * FROM clearance_requests WHERE status='Approved'";
$approved_result = $conn->query($approved_sql);
$total_approved = $approved_result->num_rows;

$pending_sql = "SELECT * FROM clearance_requests WHERE status='Pending'";
$pending_result = $conn->query($pending_sql);
$total_pending = $pending_result->num_rows;

$rejected_sql = "SELECT * FROM clearance_requests WHERE status='Rejected'";
$rejected_result = $conn->query($rejected_sql);
$total_rejected = $rejected_result->num_rows;

$moi_sql = "SELECT * FROM clearance_requests WHERE clearance_type='MOI Certificate'";
$moi_result = $conn->query($moi_sql);
$total_moi = $moi_result->num_rows;

$emergency_sql = "SELECT * FROM clearance_requests WHERE is_emergency='Yes'";
$emergency_result = $conn->query($emergency_sql);
$total_emergency = $emergency_result->num_rows;

$recent_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department AS student_department
               FROM clearance_requests
               INNER JOIN students ON clearance_requests.student_id = students.student_id
               ORDER BY clearance_requests.request_id DESC";

$recent_result = $conn->query($recent_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
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
            <a class="nav-item active" href="reports.php">Reports</a>
            <a class="nav-item" href="generate-certificate.php">Generate Certificate</a>
            <a class="nav-item" href="moi-certificate-management.php">MOI Certificate</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Reports</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">System Reports</div>

            <div class="reports-grid">
                <div class="report-card blue-left">
                    <p>Total Students</p>
                    <h2><?php echo $total_students; ?></h2>
                </div>

                <div class="report-card purple-left">
                    <p>Total Documents</p>
                    <h2><?php echo $total_documents; ?></h2>
                </div>

                <div class="report-card teal-left">
                    <p>Total Requests</p>
                    <h2><?php echo $total_requests; ?></h2>
                </div>

                <div class="report-card green-left">
                    <p>Approved</p>
                    <h2><?php echo $total_approved; ?></h2>
                </div>

                <div class="report-card orange-left">
                    <p>Pending</p>
                    <h2><?php echo $total_pending; ?></h2>
                </div>

                <div class="report-card red-left">
                    <p>Rejected</p>
                    <h2><?php echo $total_rejected; ?></h2>
                </div>

                <div class="report-card purple-left">
                    <p>MOI Requests</p>
                    <h2><?php echo $total_moi; ?></h2>
                </div>

                <div class="report-card red-left">
                    <p>Emergency Requests</p>
                    <h2><?php echo $total_emergency; ?></h2>
                </div>
            </div>

            <div class="frontend-card">
                <div class="section-title">Detailed Clearance Report</div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Department</th>
                            <th>Request Type</th>
                            <th>Request Dept.</th>
                            <th>Emergency</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>

                        <?php
                        if($recent_result->num_rows > 0){
                            while($row = $recent_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$row["name"]."</td>";
                                echo "<td>".$row["roll"]."</td>";
                                echo "<td>".$row["student_department"]."</td>";
                                echo "<td><b>".$row["clearance_type"]."</b></td>";
                                echo "<td>".$row["department"]."</td>";

                                if($row["is_emergency"] == "Yes"){
                                    echo "<td><span class='status red'>Yes</span></td>";
                                }
                                else{
                                    echo "<td><span class='status green'>No</span></td>";
                                }

                                if($row["status"] == "Approved"){
                                    echo "<td><span class='status green'>Approved</span></td>";
                                }
                                else if($row["status"] == "Rejected"){
                                    echo "<td><span class='status red'>Rejected</span></td>";
                                }
                                else{
                                    echo "<td><span class='status yellow'>Pending</span></td>";
                                }

                                echo "<td>".$row["request_date"]."</td>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='8'>No report data found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <button class="print-btn" onclick="window.print()">Print Report</button>

        </div>
    </div>
</div>

</body>
</html>