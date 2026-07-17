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

$clearance_sql = "SELECT * FROM clearance_requests";
$clearance_result = $conn->query($clearance_sql);
$total_clearance = $clearance_result->num_rows;

$pending_sql = "SELECT * FROM documents WHERE status='Pending'";
$pending_result = $conn->query($pending_sql);
$total_pending = $pending_result->num_rows;

$approved_clearance_sql = "SELECT * FROM clearance_requests WHERE status='Approved'";
$approved_clearance_result = $conn->query($approved_clearance_sql);
$total_approved_clearance = $approved_clearance_result->num_rows;

$approved_sql = "SELECT * FROM clearance_requests WHERE status='Approved'";
$approved_result = $conn->query($approved_sql);
$total_approved = $approved_result->num_rows;

$rejected_sql = "SELECT * FROM clearance_requests WHERE status='Rejected'";
$rejected_result = $conn->query($rejected_sql);
$total_rejected = $rejected_result->num_rows;

$pending_clearance_sql = "SELECT * FROM clearance_requests WHERE status='Pending'";
$pending_clearance_result = $conn->query($pending_clearance_sql);
$total_pending_clearance = $pending_clearance_result->num_rows;

$request_table_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department
                      FROM clearance_requests
                      INNER JOIN students ON clearance_requests.student_id = students.student_id
                      ORDER BY clearance_requests.request_id DESC";
$request_table_result = $conn->query($request_table_sql);

$emergency_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department
                  FROM clearance_requests
                  INNER JOIN students ON clearance_requests.student_id = students.student_id
                  WHERE clearance_requests.is_emergency='Yes'
                  ORDER BY clearance_requests.request_id DESC";
$emergency_result = $conn->query($emergency_sql);

$emergency_count_sql = "SELECT * FROM clearance_requests WHERE is_emergency='Yes'";
$emergency_count_result = $conn->query($emergency_count_sql);
$total_emergency = $emergency_count_result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
            <a class="nav-item active" href="admin-dashboard.php">Dashboard</a>
            <a class="nav-item" href="student-clearance-profile.php">Student Profiles</a>
            <a class="nav-item" href="clearance-requests.php">Clearance Requests</a>
            <a class="nav-item" href="reports.php">Reports</a>
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
            <div class="top-title">Admin Dashboard</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Welcome back, <?php echo $_SESSION["admin_name"]; ?>!</div>

            <div class="dash-grid admin-summary-grid">
    <div class="dash-card blue-left">
        <div class="dash-title">Total Requests</div>
        <div class="dash-text"><?php echo $total_clearance; ?></div>
    </div>

    <div class="dash-card green-left">
        <div class="dash-title">Approved</div>
        <div class="dash-text"><?php echo $total_approved; ?></div>
    </div>

    <div class="dash-card orange-left">
        <div class="dash-title">Pending</div>
        <div class="dash-text"><?php echo $total_pending_clearance; ?></div>
    </div>

    <div class="dash-card red-left">
        <div class="dash-title">Rejected</div>
        <div class="dash-text"><?php echo $total_rejected; ?></div>
    </div>

    <div class="dash-card orange-left">
    <div class="dash-title">Emergency</div>
    <div class="dash-text"><?php echo $total_emergency; ?></div>
</div>
</div>
                 <div class="frontend-card">
    <div class="section-title">All Student Clearance Requests</div>

    <div class="table-box no-shadow">
        <table>
            <tr>
                  <th>Student Name</th>
                  <th>Student ID</th>
                  <th>Department</th>
                  <th>Request Type</th>
                   <th>Status</th>
                  <th>Progress</th>
                  <th>Action</th>
            </tr>

            <?php
            if($request_table_result->num_rows > 0){
                while($row = $request_table_result->fetch_assoc()){
                    $student_id = $row["student_id"];

                    $doc_sql = "SELECT * FROM documents WHERE student_id='$student_id'";
                    $doc_result = $conn->query($doc_sql);
                    $total_doc = $doc_result->num_rows;

                    $approved_doc_sql = "SELECT * FROM documents WHERE student_id='$student_id' AND status='Approved'";
                    $approved_doc_result = $conn->query($approved_doc_sql);
                    $approved_doc = $approved_doc_result->num_rows;

                    echo "<tr>";
                    echo "<td>".$row["name"]."</td>";
                    echo "<td>".$row["roll"]."</td>";
                    echo "<td>".$row["department"]."</td>";
                    echo "<td><b>".$row["clearance_type"]."</b></td>";

                    if($row["status"] == "Approved"){
                        echo "<td><span class='status green'>Approved</span></td>";
                    }
                    else if($row["status"] == "Rejected"){
                        echo "<td><span class='status red'>Rejected</span></td>";
                    }
                    else{
                        echo "<td><span class='status yellow'>Pending</span></td>";
                    }

                    echo "<td>".$approved_doc."/".$total_doc."</td>";
                    echo "<td>
        <a class='small-btn' href='view-student-profile.php?student_id=".$student_id."'>View Profile</a>
      </td>";
                    echo "</tr>";
                }
            }
            else{
                echo "<tr><td colspan='7'>No clearance requests found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<div class="frontend-card emergency-card">
    <div class="section-title red-heading">! Emergency Requests</div>

    <div class="table-box no-shadow">
        <table>
            <tr>
                <th>Student</th>
                <th>Student ID</th>
                <th>Department</th>
                <th>Clearance Type</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            if($emergency_result->num_rows > 0){
                while($emergency = $emergency_result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".$emergency["name"]."</td>";
                    echo "<td>".$emergency["roll"]."</td>";
                    echo "<td>".$emergency["department"]."</td>";
                    echo "<td>".$emergency["clearance_type"]."</td>";
                    echo "<td>".$emergency["reason"]."</td>";

                    if($emergency["status"] == "Approved"){
                        echo "<td><span class='status green'>Approved</span></td>";
                    }
                    else if($emergency["status"] == "Rejected"){
                        echo "<td><span class='status red'>Rejected</span></td>";
                    }
                    else{
                        echo "<td><span class='status yellow'>Pending</span></td>";
                    }

                    echo "<td>
                            <a class='small-btn' href='view-student-profile.php?student_id=".$emergency["student_id"]."'>Review</a>
                            <a class='green-btn' href='clearance-requests.php'>Approve</a>
                          </td>";
                    echo "</tr>";
                }
            }
            else{
                echo "<tr><td colspan='7'>No emergency requests found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<div class="admin-bottom-buttons">
    <a href="generate-certificate.php">Generate Clearance Certificates</a>
    <a href="moi-certificate-management.php">MOI Certificate Management</a>
    <a href="reports.php">View Reports</a>
</div>
            
        </div>
    </div>
</div>

</body>
</html>