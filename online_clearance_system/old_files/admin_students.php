<?php
session_start();
include "db.php";

if(!isset($_SESSION["admin_id"])){
    header("Location: login.php");
}

$sql = "SELECT * FROM students ORDER BY student_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Students</title>
    <link rel="stylesheet" href="style.css">
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
        <h1>Registered Students</h1>
        <p>All students registered in the Online Clearance System.</p>

        <div class="table-box">
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Roll</th>
                    <th>Department</th>
                    <th>Batch</th>
                    <th>Email</th>
                    <th>Registered At</th>
                </tr>

                <?php
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>".$row["student_id"]."</td>";
                        echo "<td>".$row["name"]."</td>";
                        echo "<td>".$row["roll"]."</td>";
                        echo "<td>".$row["department"]."</td>";
                        echo "<td>".$row["batch"]."</td>";
                        echo "<td>".$row["email"]."</td>";
                        echo "<td>".$row["created_at"]."</td>";
                        echo "</tr>";
                    }
                }
                else{
                    echo "<tr><td colspan='7'>No students registered yet.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>