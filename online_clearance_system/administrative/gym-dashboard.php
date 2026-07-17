<?php
session_start();
include "../db.php";

if(!isset($_SESSION["administrative_id"])){
    header("Location: administrator-login.php");
}

if($_SESSION["administrative_department"] != "Gym"){
    header("Location: administrator-login.php");
}

$message = "";

if(isset($_POST["update_document"])){
    $document_id = $_POST["document_id"];
    $status = $_POST["status"];
    $comment = $_POST["comment"];

    $update_sql = "UPDATE documents 
                   SET status='$status', comment='$comment' 
                   WHERE document_id='$document_id'";

    if($conn->query($update_sql)){
        $message = "Gym clearance document updated successfully.";
    }
    else{
        $message = "Something went wrong.";
    }
}

$document_sql = "SELECT documents.*, students.name, students.roll, students.department
                 FROM documents
                 INNER JOIN students ON documents.student_id = students.student_id
                 WHERE documents.document_name='Gym Clearance'
                 ORDER BY documents.document_id DESC";

$document_result = $conn->query($document_sql);
if(isset($_POST["update_clearance"])){
    $request_id = $_POST["request_id"];
    $status = $_POST["status"];
    $comment = $_POST["comment"];

    $update_clearance_sql = "UPDATE clearance_requests 
                             SET status='$status', comment='$comment'
                             WHERE request_id='$request_id'";

    if($conn->query($update_clearance_sql)){
        $message = "Gym clearance request updated successfully.";
    }
    else{
        $message = "Something went wrong while updating clearance request.";
    }
}

$clearance_sql = "SELECT clearance_requests.*, students.name, students.roll, students.department AS student_department
                  FROM clearance_requests
                  INNER JOIN students ON clearance_requests.student_id = students.student_id
                  WHERE clearance_requests.department='Gym'
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
    <title>Gym Dashboard</title>
    <link rel="stylesheet" type="text/css" href="./administrative.css">
</head>
<body>

<div class="page">
    <div class="left">
        <div class="profile">
            <div class="profile-icon"></div>
            <div>
                <div>Gym Officer⌄</div>
                <div class="small-name"><?php echo $_SESSION["administrative_name"]; ?></div>
            </div>
        </div>

        <div class="nav">
            <a class="nav-item active" href="gym-dashboard.php">Gym Clearance</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Gym Dashboard</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Gym Clearance Requests</div>

            <div class="frontend-card">
                <div class="section-title">Gym Documents</div>

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
                            <th>Department</th>
                            <th>Document</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>

                        <?php
                        if($document_result->num_rows > 0){
                            while($row = $document_result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$row["name"]."</td>";
                                echo "<td>".$row["roll"]."</td>";
                                echo "<td>".$row["department"]."</td>";
                                echo "<td>".$row["document_name"]."</td>";
                                echo "<td><span class='uploaded-badge'>Uploaded</span></td>";

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
                                        <input type='hidden' name='document_id' value='".$row["document_id"]."'>

                                        <select name='status'>
                                            <option value='Pending'>Pending</option>
                                            <option value='Approved'>Approved</option>
                                            <option value='Rejected'>Rejected</option>
                                        </select>

                                        <textarea name='comment' placeholder='Write comment'>".$row["comment"]."</textarea>

                                        <button type='submit' name='update_document' onclick=\"return confirm('Are you sure you want to update this document?')\">
                                            Update
                                        </button>
                                    </form>
                                </td>";

                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='8'>No Gym Clearance documents found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="frontend-card">
    <div class="section-title">Gym Clearance Applications</div>

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

                            <button type='submit' name='update_clearance' onclick=\"return confirm('Update this Gym clearance request?')\">
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

</body>
</html>