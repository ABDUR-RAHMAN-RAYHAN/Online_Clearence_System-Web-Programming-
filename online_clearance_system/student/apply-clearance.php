<?php
session_start();
include "../db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}

$student_id = $_SESSION["student_id"];
$message = "";

if(isset($_POST["submit_department_clearance"])){
    $department = $_POST["department"];
    $reason = $_POST["reason"];

    $check_sql = "SELECT * FROM clearance_requests 
                  WHERE student_id='$student_id' 
                  AND clearance_type='Department Clearance'
                  AND department='$department'
                  AND status='Pending'";
    $check_result = $conn->query($check_sql);

    if($check_result->num_rows > 0){
        $message = "You already have a pending request for this department.";
    }
    else{
        $insert_sql = "INSERT INTO clearance_requests 
                       (student_id, clearance_type, department, reason, is_emergency, status, comment)
                       VALUES 
                       ('$student_id', 'Department Clearance', '$department', '$reason', 'No', 'Pending', 'Waiting for review')";

        if($conn->query($insert_sql)){
            $message = "Department clearance request submitted successfully.";
        }
        else{
            $message = "Something went wrong. Please try again.";
        }
    }
}

if(isset($_POST["submit_final_clearance"])){
    $reason = $_POST["final_reason"];

    $library_sql = "SELECT * FROM clearance_requests 
                    WHERE student_id='$student_id' 
                    AND clearance_type='Department Clearance'
                    AND department='Library'
                    AND status='Approved'";
    $library_result = $conn->query($library_sql);

    $accounts_sql = "SELECT * FROM clearance_requests 
                     WHERE student_id='$student_id' 
                     AND clearance_type='Department Clearance'
                     AND department='Accounts'
                     AND status='Approved'";
    $accounts_result = $conn->query($accounts_sql);

    $hostel_sql = "SELECT * FROM clearance_requests 
                   WHERE student_id='$student_id' 
                   AND clearance_type='Department Clearance'
                   AND department='Hall/Hostel'
                   AND status='Approved'";
    $hostel_result = $conn->query($hostel_sql);

    $gym_sql = "SELECT * FROM clearance_requests 
                WHERE student_id='$student_id' 
                AND clearance_type='Department Clearance'
                AND department='Gym'
                AND status='Approved'";
    $gym_result = $conn->query($gym_sql);

    if($library_result->num_rows == 0 || $accounts_result->num_rows == 0 || $hostel_result->num_rows == 0 || $gym_result->num_rows == 0){
        $message = "You cannot apply for final clearance yet. Library, Accounts, Hostel, and Gym clearance must be approved first.";
    }
    else{
        $check_sql = "SELECT * FROM clearance_requests 
                      WHERE student_id='$student_id' 
                      AND clearance_type='Final Clearance'
                      AND department='All Departments'
                      AND status='Pending'";
        $check_result = $conn->query($check_sql);

        if($check_result->num_rows > 0){
            $message = "You already have a pending final clearance request.";
        }
        else{
            $insert_sql = "INSERT INTO clearance_requests 
                           (student_id, clearance_type, department, reason, is_emergency, status, comment)
                           VALUES 
                           ('$student_id', 'Final Clearance', 'All Departments', '$reason', 'No', 'Pending', 'Waiting for review')";

            if($conn->query($insert_sql)){
                $message = "Final clearance request submitted successfully.";
            }
            else{
                $message = "Something went wrong. Please try again.";
            }
        }
    }
}

$request_sql = "SELECT * FROM clearance_requests 
                WHERE student_id='$student_id' 
                ORDER BY request_id DESC";
$request_result = $conn->query($request_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply Clearance</title>
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
            <a class="nav-item active" href="apply-clearance.php">Apply</a>
            <a class="nav-item" href="upload-documents.php">Upload Documents</a>
            
            <a class="nav-item" href="track-approval.php">Track Approval</a>
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
            <div class="top-title">Apply Clearance</div>
            <div class="top-icons">♧ ✉ <a href="settings.php">⚙</a></div>
        </div>

        <div class="content">
            <div class="welcome">Submit Clearance Request</div>

            <?php
            if($message != ""){
                echo "<div class='success-message'>".$message."</div>";
            }
            ?>

            <div class="settings-grid">
                <div class="frontend-card">
                    <div class="section-title">Department Clearance Application</div>

                    <p class="track-note">
                        Select one department. The respective department officer or main admin can review this request.
                    </p>

                    <form method="POST" class="frontend-form">
                        <label>Department</label>
                        <select name="department" required>
                            <option value="">Select Department</option>
                            <option value="Library">Library</option>
                            <option value="Accounts">Accounts</option>
                            <option value="Hall/Hostel">Hall/Hostel</option>
                            <option value="Gym">Gym</option>
                        </select>

                        <label>Reason / Comment</label>
                        <textarea name="reason" placeholder="Write your reason here..." required></textarea>

                        <button type="submit" name="submit_department_clearance" onclick="return confirm('Submit department clearance request?')">
                            Submit Department Clearance
                        </button>
                    </form>
                </div>

                <div class="frontend-card">
                    <div class="section-title">Final Clearance Application</div>

                    <p class="track-note">
                        Submit this after your department clearances are completed. Final clearance is reviewed by the main admin.
                    </p>

                    <form method="POST" class="frontend-form">
                        <label>Reason / Comment</label>
                        <textarea name="final_reason" placeholder="Example: I have completed all department clearance steps." required></textarea>

                        <button type="submit" name="submit_final_clearance" onclick="return confirm('Submit final clearance request?')">
                            Submit Final Clearance
                        </button>
                    </form>
                </div>
            </div>

            <div class="frontend-card">
                <div class="section-title">My Clearance Requests</div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Department</th>
                            <th>Reason</th>
                            <th>Emergency</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>

                        <?php
                        if($request_result->num_rows > 0){
                            while($row = $request_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td><b>".$row["clearance_type"]."</b></td>";
                                echo "<td>".$row["department"]."</td>";
                                echo "<td>".$row["reason"]."</td>";
                                echo "<td>".$row["is_emergency"]."</td>";

                                if($row["status"] == "Approved"){
                                    echo "<td><span class='status green'>Approved</span></td>";
                                }
                                else if($row["status"] == "Rejected"){
                                    echo "<td><span class='status red'>Rejected</span></td>";
                                }
                                else{
                                    echo "<td><span class='status yellow'>Pending</span></td>";
                                }

                                echo "<td>".$row["comment"]."</td>";
                                echo "<td>".$row["request_date"]."</td>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='7'>No clearance request submitted yet.</td></tr>";
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