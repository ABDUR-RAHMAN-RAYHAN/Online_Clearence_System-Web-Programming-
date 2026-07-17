<?php
session_start();
include "../db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}

$student_id = $_SESSION["student_id"];
$message = "";

if(isset($_POST["upload"])){
    $document_name = $_POST["document_name"];
    $file = $_FILES["document_file"]["name"];
    $tmp_name = $_FILES["document_file"]["tmp_name"];

    $check = "SELECT * FROM documents 
              WHERE student_id='$student_id' AND document_name='$document_name'";
    $check_result = $conn->query($check);

    if($check_result->num_rows > 0){
        $message = "This document is already uploaded. Please use update option.";
    }
    else{
        if($file != ""){
            $new_file_name = time() . "_" . $file;
            $upload_path = "../uploads/" . $new_file_name;

            if(move_uploaded_file($tmp_name, $upload_path)){
                $sql = "INSERT INTO documents (student_id, document_name, file_name, status, comment)
                        VALUES ('$student_id', '$document_name', '$new_file_name', 'Pending', 'Waiting for admin review')";

                if($conn->query($sql) == TRUE){
                    $message = "Document uploaded successfully.";
                }
                else{
                    $message = "Database error.";
                }
            }
            else{
                $message = "File upload failed.";
            }
        }
        else{
            $message = "Please select a file.";
        }
    }
}

if(isset($_POST["update_file"])){
    $document_id = $_POST["document_id"];
    $file = $_FILES["new_document_file"]["name"];
    $tmp_name = $_FILES["new_document_file"]["tmp_name"];

    if($file != ""){
        $new_file_name = time() . "_" . $file;
        $upload_path = "../uploads/" . $new_file_name;

        if(move_uploaded_file($tmp_name, $upload_path)){
            $sql = "UPDATE documents 
                    SET file_name='$new_file_name', status='Pending', comment='Updated file. Waiting for admin review'
                    WHERE document_id='$document_id' AND student_id='$student_id'";

            if($conn->query($sql) == TRUE){
                $message = "Document updated successfully.";
            }
            else{
                $message = "Update failed.";
            }
        }
        else{
            $message = "File update failed.";
        }
    }
    else{
        $message = "Please select a file to update.";
    }
}

$documents = "SELECT * FROM documents WHERE student_id='$student_id' ORDER BY document_id DESC";
$result = $conn->query($documents);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Documents</title>
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
            <a class="nav-item active" href="upload-documents.php">Upload Documents</a>
            <a class="nav-item" href="track-approval.php">Track Approval</a>
            <a class="nav-item" href="moi-certificate.php">MOI Certificate</a>
            <a class="nav-item" href="emergency-request.php">Emergency Request</a>
            
        </div>

        <div class="bottom-nav">
            <a class="nav-item" href="help-faq.php">Help & FAQ</a>
            <a class="nav-item" href="#">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Upload Documents</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Upload Required Clearance Documents</div>

            <?php
            if($message != ""){
                echo "<p class='success-message'>$message</p>";
            }
            ?>

            <div class="frontend-card">
                <div class="section-title">Submit Document</div>

                <form method="POST" enctype="multipart/form-data" onsubmit="return uploadConfirm()" class="frontend-form">
                    <label>Document Name</label>
                    <select name="document_name" required>
                        <option value="">Select Document</option>
                        <option value="Library Clearance">Library Clearance</option>
                        <option value="Accounts Clearance">Accounts Clearance</option>
                        <option value="Department Clearance">Department Clearance</option>
                        <option value="Hall Clearance">Hall Clearance</option>
                        <option value="Gym Clearance">Gym Clearance</option>
                        <option value="ID Card Copy">ID Card Copy</option>
                    </select>

                    <label>Choose File</label>
                    <input type="file" name="document_file" required>

                    <button type="submit" name="upload">Upload</button>
                </form>
            </div>

            <div class="frontend-card">
                <div class="section-title">My Uploaded Documents</div>

                <div class="table-box no-shadow">
                    <table>
                        <tr>
                            <th>Document Name</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Uploaded At</th>
                            <th>Update</th>
                        </tr>

                        <?php
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>".$row["document_name"]."</td>";
                                echo "<td><span class='uploaded-badge'>Uploaded</span></td>";
                                echo "<td>".$row["status"]."</td>";
                                echo "<td>".$row["comment"]."</td>";
                                echo "<td>".$row["uploaded_at"]."</td>";
                                echo "<td>
                                        <form method='POST' enctype='multipart/form-data' onsubmit='return updateFileConfirm()'>
                                            <input type='hidden' name='document_id' value='".$row["document_id"]."'>
                                            <input type='file' name='new_document_file' required>
                                            <button type='submit' name='update_file'>Update</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr><td colspan='6'>No documents uploaded yet.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function uploadConfirm(){
    return confirm("Are you sure you want to upload this document?");
}

function updateFileConfirm(){
    return confirm("Are you sure you want to update this document?");
}
</script>

</body>
</html>