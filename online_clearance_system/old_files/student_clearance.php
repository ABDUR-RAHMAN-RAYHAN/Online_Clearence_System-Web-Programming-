<?php
session_start();
include "db.php";

if(!isset($_SESSION["student_id"])){
    header("Location: login.php");
}

$student_id = $_SESSION["student_id"];
$message = "";

if(isset($_POST["request_clearance"])){
    $check = "SELECT * FROM clearance_requests WHERE student_id='$student_id'";
    $check_result = $conn->query($check);

    if($check_result->num_rows > 0){
        $message = "You have already submitted a clearance request.";
    }
    else{
        $sql = "INSERT INTO clearance_requests (student_id, status, comment)
                VALUES ('$student_id', 'Pending', 'Waiting for admin review')";

        if($conn->query($sql) == TRUE){
            $message = "Clearance request submitted successfully.";
        }
        else{
            $message = "Request failed.";
        }
    }
}

$request_sql = "SELECT * FROM clearance_requests WHERE student_id='$student_id'";
$request_result = $conn->query($request_sql);

$doc_sql = "SELECT * FROM documents WHERE student_id='$student_id'";
$doc_result = $conn->query($doc_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clearance Status</title>
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
        <h1>Clearance Status</h1>
        <p>Submit and check your final clearance request.</p>

        <?php
        if($message != ""){
            echo "<p class='message'>$message</p>";
        }
        ?>

        <div class="form-card">
            <h3>Request Final Clearance</h3>
            <p>After uploading your required documents, submit your final clearance request.</p>

            <form method="POST" onsubmit="return clearanceConfirm()">
                <button type="submit" name="request_clearance">Submit Clearance Request</button>
            </form>
        </div>

        <h2 class="section-title">My Document Status</h2>

        <div class="table-box">
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

        <h2 class="section-title">Final Clearance Request</h2>

        <div class="table-box">
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

</body>
</html>