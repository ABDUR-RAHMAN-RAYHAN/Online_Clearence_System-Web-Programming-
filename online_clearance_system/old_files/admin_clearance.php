<?php
session_start();
include "db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: login.php");
}

$message = "";

if(isset($_POST["update_clearance"])){
    $request_id = $_POST["request_id"];
    $status = $_POST["status"];
    $comment = $_POST["comment"];

    $sql = "UPDATE clearance_requests
            SET status='$status', comment='$comment'
            WHERE request_id='$request_id'";

    if($conn->query($sql) == TRUE){
        $message = "Clearance request updated successfully.";
    }
    else{
        $message = "Update failed.";
    }
}

$sql = "SELECT clearance_requests.*, students.name, students.roll, students.department, students.batch
        FROM clearance_requests
        INNER JOIN students ON clearance_requests.student_id = students.student_id
        ORDER BY clearance_requests.request_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Clearance Requests</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_students.php">Students</a>
        <a href="admin_documents.php">Documents</a>
        <a href="admin_clearance.php">Clearance Requests</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Clearance Requests</h1>
        <p>Approve or reject student final clearance requests.</p>

        <?php
        if($message != ""){
            echo "<p class='message'>$message</p>";
        }
        ?>

        <div class="table-box">
            <table>
                <tr>
                    <th>Student</th>
                    <th>Roll</th>
                    <th>Department</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>

                <?php
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>".$row["name"]."</td>";
                        echo "<td>".$row["roll"]."</td>";
                        echo "<td>".$row["department"]."</td>";
                        echo "<td>".$row["batch"]."</td>";
                        echo "<td>".$row["status"]."</td>";
                        echo "<td>".$row["comment"]."</td>";
                        echo "<td>".$row["request_date"]."</td>";
                        echo "<td>
                                <form method='POST' onsubmit='return clearanceUpdateConfirm()'>
                                    <input type='hidden' name='request_id' value='".$row["request_id"]."'>

                                    <select name='status' required>
                                        <option value='Pending'>Pending</option>
                                        <option value='Approved'>Approved</option>
                                        <option value='Rejected'>Rejected</option>
                                    </select>

                                    <textarea name='comment' placeholder='Write comment'>".$row["comment"]."</textarea>

                                    <button type='submit' name='update_clearance'>Update</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                }
                else{
                    echo "<tr><td colspan='8'>No clearance requests found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>