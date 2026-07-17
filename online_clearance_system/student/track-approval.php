<?php
session_start();
include "../db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}

$student_id = $_SESSION["student_id"];

$doc_sql = "SELECT * FROM documents WHERE student_id='$student_id' ORDER BY document_id DESC";
$doc_result = $conn->query($doc_sql);

$request_sql = "SELECT * FROM clearance_requests WHERE student_id='$student_id'";
$request_result = $conn->query($request_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Approval</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>

<div class="page">
    <div class="left">
        <div class="profile">
            <div class="profile-icon"></div>
            <div>
                <div>Student⌄</div>
                <div class="small-name"><?php echo $_SESSION["student_name"]; ?></div>
            </div>
        </div>

        <div class="nav">
            <a class="nav-item" href="student-dashboard.php">Dashboard</a>
            <a class="nav-item" href="apply-clearance.php">Apply</a>
            <a class="nav-item" href="upload-documents.php">Upload Documents</a>
            <a class="nav-item active" href="track-approval.php">Track Approval</a>
            
            <a class="nav-item" href="moi-certificate.php">MOI Certificate</a>
            <a class="nav-item" href="emergency-request.php">Emergency Request</a>
            
        </div>

        <div class="bottom-nav">
              <a class="nav-item" href="help-faq.php">Help & FAQ</a> 
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Track Approval</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Track Document and Clearance Approval</div>

            <div class="frontend-card">
                <div class="section-title">Document Approval Status</div>

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
                                echo "<td>".$doc["status"]."</td>";
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

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Request Date</th>
                        </tr>

                        <?php
                        if($request_result->num_rows > 0){
                            while($req = $request_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$req["status"]."</td>";
                                echo "<td>".$req["comment"]."</td>";
                                echo "<td>".$req["request_date"]."</td>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='3'>No clearance request submitted yet.</td></tr>";
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