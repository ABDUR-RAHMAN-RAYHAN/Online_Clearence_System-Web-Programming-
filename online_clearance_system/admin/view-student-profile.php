<?php
session_start();
include "../db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: admin-login.php");
}

if(!isset($_GET["student_id"])){
    header("Location: admin-dashboard.php");
}

$student_id = $_GET["student_id"];

$student_sql = "SELECT * FROM students WHERE student_id='$student_id'";
$student_result = $conn->query($student_sql);

if($student_result->num_rows == 0){
    echo "Student not found.";
    exit();
}

$student = $student_result->fetch_assoc();

$doc_sql = "SELECT * FROM documents WHERE student_id='$student_id'";
$doc_result = $conn->query($doc_sql);

$clearance_sql = "SELECT * FROM clearance_requests WHERE student_id='$student_id'";
$clearance_result = $conn->query($clearance_sql);

$clearance_status = "Not Submitted";
$clearance_comment = "No clearance request submitted yet.";

if($clearance_result->num_rows > 0){
    $clearance = $clearance_result->fetch_assoc();
    $clearance_status = $clearance["status"];
    $clearance_comment = $clearance["comment"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student Profile</title>
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
            <div class="top-title">Student Profile</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Student Clearance Profile</div>

            <div class="frontend-card">
                <div class="section-title">Student Information</div>

                <div class="profile-details">
                    <p><b>Name:</b> <?php echo $student["name"]; ?></p>
                    <p><b>Roll:</b> <?php echo $student["roll"]; ?></p>
                    <p><b>Department:</b> <?php echo $student["department"]; ?></p>
                    <p><b>Batch:</b> <?php echo $student["batch"]; ?></p>
                    <p><b>Email:</b> <?php echo $student["email"]; ?></p>
                </div>
            </div>

            <div class="frontend-card">
                <div class="section-title">Uploaded Documents</div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Document Name</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Uploaded At</th>
                        </tr>

                        <?php
                        if($doc_result->num_rows > 0){
                            while($doc = $doc_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$doc["document_name"]."</td>";
                                echo "<td><span class='uploaded-badge'>Uploaded</span></td>";

                                if($doc["status"] == "Approved"){
                                    echo "<td><span class='status green'>Approved</span></td>";
                                }
                                else if($doc["status"] == "Rejected"){
                                    echo "<td><span class='status red'>Rejected</span></td>";
                                }
                                else{
                                    echo "<td><span class='status yellow'>".$doc["status"]."</span></td>";
                                }

                                echo "<td>".$doc["comment"]."</td>";
                                echo "<td>".$doc["uploaded_at"]."</td>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='5'>No documents uploaded yet.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <div class="frontend-card">
                <div class="section-title">Final Clearance Status</div>

                <p>
                    <b>Status:</b>
                    <?php
                    if($clearance_status == "Approved"){
                        echo "<span class='status green'>Approved</span>";
                    }
                    else if($clearance_status == "Rejected"){
                        echo "<span class='status red'>Rejected</span>";
                    }
                    else{
                        echo "<span class='status yellow'>".$clearance_status."</span>";
                    }
                    ?>
                </p>

                <p><b>Comment:</b> <?php echo $clearance_comment; ?></p>
            </div>

            <a class="small-btn" href="admin-dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>