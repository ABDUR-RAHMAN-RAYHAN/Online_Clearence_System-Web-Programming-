<?php
session_start();
include "../db.php";

if($_SESSION["administrative_department"] != "Accounts"){
    header("Location: administrator-login.php");
}

if(!isset($_SESSION["administrative_id"])){
    header("Location: administrator-login.php");
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
        $message = "Accounts document status updated successfully.";
    }
    else{
        $message = "Status update failed.";
    }
}

$sql = "SELECT documents.*, students.name, students.roll, students.department, students.batch
        FROM documents
        INNER JOIN students ON documents.student_id = students.student_id
        WHERE documents.document_name='Accounts Clearance'
        ORDER BY documents.document_id DESC";

$result = $conn->query($sql);

if(isset($_POST["update_clearance"])){
    $request_id = $_POST["request_id"];
    $status = $_POST["status"];
    $comment = $_POST["comment"];

    $update_clearance_sql = "UPDATE clearance_requests 
                             SET status='$status', comment='$comment'
                             WHERE request_id='$request_id'";

    if($conn->query($update_clearance_sql)){
        $message = "Accounts clearance request updated successfully.";
    }
    else{
        $message = "Something went wrong while updating clearance request.";
    }
}

$clearance_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department AS student_department
                  FROM clearance_requests
                  INNER JOIN students ON clearance_requests.student_id = students.student_id
                  WHERE clearance_requests.department='Accounts'
                  AND clearance_requests.clearance_type!='MOI Certificate'
                  AND clearance_requests.clearance_type!='Emergency Clearance'
                  AND clearance_requests.is_emergency='No'
                  ORDER BY clearance_requests.request_id DESC";

$clearance_result = $conn->query($clearance_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accounts Clearance Review</title>
    <link rel="stylesheet" type="text/css" href="./administrative.css">
</head>
<body>

<div class="page">
    <div class="left">
        <div class="profile">
            <div class="profile-icon"></div>
            <div>
                <div>Administrative⌄</div>
                <div class="small-name"><?php echo $_SESSION["administrative_name"]; ?></div>
            </div>
        </div>

        <div class="nav">
    <a class="nav-item active" href="accounts-dashboard.php">Accounts Clearance</a>
</div>

        <div class="bottom-nav">
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Accounts Clearance</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Accounts Clearance Review</div>

            <?php
            if($message != ""){
                echo "<p class='success-message'>$message</p>";
            }
            ?>

            <div class="frontend-card">
                <div class="section-title">Accounts Documents</div>

                <div class="table-box no-shadow">
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

                                if($row["status"] == "Approved"){
                                    echo "<td><span class='status green'>Approved</span></td>";
                                }
                                else if($row["status"] == "Rejected"){
                                    echo "<td><span class='status red'>Rejected</span></td>";
                                }
                                else{
                                    echo "<td><span class='status yellow'>".$row["status"]."</span></td>";
                                }

                                echo "<td>".$row["comment"]."</td>";

                                echo "<td>
                                        <form method='POST' onsubmit='return statusConfirm()' class='table-form'>
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
                            echo "<tr><td colspan='9'>No accounts clearance documents found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="frontend-card">
    <div class="section-title">Accounts Clearance Applications</div>

    <div class="table-box no-shadow">
        <table>
            <tr>
                <th>Student Name</th>
                <th>Student ID</th>
                <th>Student Dept.</th>
                <th>Request Type</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Admin/Officer Comment</th>
                <th>Action</th>
            </tr>

            <?php
            if($clearance_result->num_rows > 0){
                while($row = $clearance_result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".$row["name"]."</td>";
                    echo "<td>".$row["roll"]."</td>";
                    echo "<td>".$row["student_department"]."</td>";
                    echo "<td><b>".$row["clearance_type"]."</b></td>";
                    echo "<td>".$row["reason"]."</td>";

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

                    echo "<td>
                        <form method='POST' class='table-form'>
                            <input type='hidden' name='request_id' value='".$row["request_id"]."'>

                            <select name='status'>
                                <option value='Pending'>Pending</option>
                                <option value='Approved'>Approved</option>
                                <option value='Rejected'>Rejected</option>
                            </select>

                            <textarea name='comment' placeholder='Write comment'>".$row["comment"]."</textarea>

                            <button type='submit' name='update_clearance' onclick=\"return confirm('Update this Accounts clearance request?')\">
                                Update
                            </button>
                        </form>
                    </td>";

                    echo "</tr>";
                }
            }
            else{
                echo "<tr><td colspan='8'>No Library clearance application found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
        </div>
    </div>
</div>

<script>
function statusConfirm(){
    return confirm("Are you sure you want to update this document status?");
}
</script>

</body>
</html>