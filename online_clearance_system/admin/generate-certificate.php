<?php
session_start();
include "../db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: admin-login.php");
}

$approved_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department AS student_department, students.batch, students.email
                 FROM clearance_requests
                 INNER JOIN students ON clearance_requests.student_id = students.student_id
                 WHERE clearance_requests.status='Approved'
                 AND clearance_requests.clearance_type!='MOI Certificate'
                 ORDER BY clearance_requests.request_id DESC";

$approved_result = $conn->query($approved_sql);
$total_ready = $approved_result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Certificate</title>
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
            <div class="top-title">Generate Clearance Certificate</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="main-card">
                <div class="request-head">
                    <div>
                        <div class="page-title">Approved Clearance Requests</div>
                        <div class="page-subtitle">Students who have completed final approval</div>
                    </div>

                    <div class="status green"><?php echo $total_ready; ?> Ready for Certificate</div>
                </div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Clearance Type</th>
                            <th>Completion Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                        <?php
                        if($approved_result->num_rows > 0){
                            while($row = $approved_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$row["name"]."</td>";
                                echo "<td>".$row["roll"]."</td>";
                                echo "<td>".$row["student_department"]."</td>";
                                echo "<td>".$row["batch"]."</td>";
                                echo "<td>".$row["clearance_type"]."</td>";
                                echo "<td>".$row["request_date"]."</td>";
                                echo "<td><span class='status green'>All Approved</span></td>";
                                echo "<td>
                                        <a class='blue-btn small-btn' href='certificate-generated.php?request_id=".$row["request_id"]."'>
                                            Generate Certificate
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='8'>No approved clearance request found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <div class="info-box">
                <div class="section-title">Certificate Generation Information</div>
                <ul>
                    <li>Only approved clearance requests can generate certificates.</li>
                    <li>MOI Certificate requests are managed separately from the MOI section.</li>
                    <li>Generated certificates can be printed or saved as PDF from the browser.</li>
                    <li>A copy of the request remains stored in the system records.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>