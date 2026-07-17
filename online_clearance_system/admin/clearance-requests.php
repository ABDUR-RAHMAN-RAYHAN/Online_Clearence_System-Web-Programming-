<?php
session_start();
include "../db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: admin-login.php");
}

$message = "";

if(isset($_POST["update_request"])){
    $request_id = $_POST["request_id"];
    $status = $_POST["status"];
    $comment = $_POST["comment"];

    $update_sql = "UPDATE clearance_requests 
                   SET status='$status', comment='$comment' 
                   WHERE request_id='$request_id'";

    if($conn->query($update_sql)){
        $message = "Clearance request updated successfully.";
    }
    else{
        $message = "Something went wrong.";
    }
}

$request_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department AS student_department
                FROM clearance_requests
                INNER JOIN students ON clearance_requests.student_id = students.student_id
                ORDER BY clearance_requests.request_id DESC";
$request_result = $conn->query($request_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clearance Requests</title>
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
            <a class="nav-item active" href="clearance-requests.php">Clearance Requests</a>
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
            <div class="top-title">Clearance Requests</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Manage Student Clearance Requests</div>

            <div class="frontend-card">
                <div class="section-title">All Clearance Requests</div>

                <?php
                if($message != ""){
                    echo "<div class='success-message'>".$message."</div>";
                }
                ?>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Student Dept.</th>
                            <th>Request Type</th>
                            <th>Request Dept.</th>
                            <th>Reason</th>
                            <th>Emergency</th>
                            <th>Status</th>
                            <th>Admin Action</th>
                        </tr>

                        <?php
                        if($request_result->num_rows > 0){
                            while($row = $request_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$row["name"]."</td>";
                                echo "<td>".$row["roll"]."</td>";
                                echo "<td>".$row["student_department"]."</td>";
                                echo "<td><b>".$row["clearance_type"]."</b></td>";
                                echo "<td>".$row["department"]."</td>";
                                echo "<td>".$row["reason"]."</td>";

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

                                echo "<td>
                                    <form method='POST' class='table-form'>
                                        <input type='hidden' name='request_id' value='".$row["request_id"]."'>

                                        <select name='status'>
                                            <option value='Pending'>Pending</option>
                                            <option value='Approved'>Approved</option>
                                            <option value='Rejected'>Rejected</option>
                                        </select>

                                        <textarea name='comment' placeholder='Write admin comment'>".$row["comment"]."</textarea>

                                        <button type='submit' name='update_request' onclick=\"return confirm('Are you sure you want to update this request?')\">
                                            Update
                                        </button>
                                    </form>
                                </td>";

                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='9'>No clearance requests found.</td></tr>";
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