<?php
session_start();
include "../db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: admin-login.php");
}

$sql = "SELECT * FROM students ORDER BY student_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Clearance Profiles</title>
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
            <a class="nav-item active" href="student-clearance-profile.php">Student Profiles</a>
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
            <div class="top-title">Student Clearance Profiles</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Registered Student Profiles</div>

            <div class="frontend-card">
                <div class="section-title">Student List</div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Email</th>
                            <th>Documents</th>
                            <th>Clearance Status</th>
                        </tr>

                        <?php
                        if($result->num_rows > 0){
                            while($student = $result->fetch_assoc()){
                                $student_id = $student["student_id"];

                                $doc_sql = "SELECT * FROM documents WHERE student_id='$student_id'";
                                $doc_result = $conn->query($doc_sql);
                                $total_documents = $doc_result->num_rows;

                                $clearance_sql = "SELECT * FROM clearance_requests WHERE student_id='$student_id'";
                                $clearance_result = $conn->query($clearance_sql);

                                $clearance_status = "Not Submitted";

                                if($clearance_result->num_rows > 0){
                                    $clearance_row = $clearance_result->fetch_assoc();
                                    $clearance_status = $clearance_row["status"];
                                }

                                echo "<tr>";
                                echo "<td>".$student["student_id"]."</td>";
                                echo "<td>".$student["name"]."</td>";
                                echo "<td>".$student["roll"]."</td>";
                                echo "<td>".$student["department"]."</td>";
                                echo "<td>".$student["batch"]."</td>";
                                echo "<td>".$student["email"]."</td>";
                                echo "<td>".$total_documents." Uploaded</td>";

                                if($clearance_status == "Approved"){
                                    echo "<td><span class='status green'>Approved</span></td>";
                                }
                                else if($clearance_status == "Rejected"){
                                    echo "<td><span class='status red'>Rejected</span></td>";
                                }
                                else{
                                    echo "<td><span class='status yellow'>".$clearance_status."</span></td>";
                                }

                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='8'>No students found.</td></tr>";
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