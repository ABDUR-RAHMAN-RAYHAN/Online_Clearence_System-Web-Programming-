<?php
session_start();
include "db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: login.php");
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
            $upload_path = "uploads/" . $new_file_name;

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
        $upload_path = "uploads/" . $new_file_name;

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
<html>
<head>
    <title>Upload Documents</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <h2>Student Panel</h2>
        <a href="student_dashboard.php">Dashboard</a>
        <a href="student_upload.php">Upload Documents</a>
        <a href="student_clearance.php">Clearance Status</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Upload Documents</h1>
        <p>Upload or update your required clearance documents here.</p>

        <?php
        if($message != ""){
            echo "<p class='message'>$message</p>";
        }
        ?>

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data">
                <label>Document Name</label>
                <select name="document_name" required>
                    <option value="">Select Document</option>
                    <option value="Library Clearance">Library Clearance</option>
                    <option value="Accounts Clearance">Accounts Clearance</option>
                    <option value="Department Clearance">Department Clearance</option>
                    <option value="Hall Clearance">Hall Clearance</option>
                    <option value="ID Card Copy">ID Card Copy</option>
                </select>

                <label>Choose File</label>
                <input type="file" name="document_file" required>

                <button type="submit" name="upload" onclick="return uploadConfirm()">Upload</button>
            </form>
        </div>

        <h2 class="section-title">My Uploaded Documents</h2>

        <div class="table-box">
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

</body>
</html>