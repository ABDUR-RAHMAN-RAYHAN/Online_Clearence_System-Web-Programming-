<?php
session_start();
include "db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: login.php");
}

$message = "";

if(isset($_POST["update_status"])){
    $document_id = $_POST["document_id"];
    $status = $_POST["status"];
    $comment = $_POST["comment"];

    $sql = "UPDATE documents 
            SET status='$status', comment='$comment' 
            WHERE document_id='$document_id'";

    if($conn->query($sql) == TRUE){
        $message = "Document status updated successfully.";
    }
    else{
        $message = "Status update failed.";
    }
}

$sql = "SELECT documents.*, students.name, students.roll, students.department, students.batch
        FROM documents
        INNER JOIN students ON documents.student_id = students.student_id
        ORDER BY documents.document_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Documents</title>
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
        <h1>Review Uploaded Documents</h1>
        <p>Approve or reject student uploaded documents.</p>

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
                    <th>Document</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Comment</th>
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
                        echo "<td>".$row["document_name"]."</td>";
                        echo "<td><span class='uploaded-badge'>Uploaded</span></td>";
                        echo "<td>".$row["status"]."</td>";
                        echo "<td>".$row["comment"]."</td>";
                        echo "<td>
                                <form method='POST' onsubmit='return statusConfirm()'>
                                    <input type='hidden' name='document_id' value='".$row["document_id"]."'>

                                    <select name='status' required>
                                        <option value='Pending'>Pending</option>
                                        <option value='Approved'>Approved</option>
                                        <option value='Rejected'>Rejected</option>
                                    </select>

                                    <textarea name='comment' placeholder='Write comment'>".$row["comment"]."</textarea>

                                    <button type='submit' name='update_status'>Update</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                }
                else{
                    echo "<tr><td colspan='9'>No documents uploaded yet.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>