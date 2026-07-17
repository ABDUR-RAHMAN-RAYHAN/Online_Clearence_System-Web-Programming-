<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Clearance System</title>
    <link rel="stylesheet" href="student/style.css">
</head>
<body class="index-body">
    <div class="index-main">
        <div class="index-title">Online Clearance System</div>
        <div class="index-subtitle">University clearance management portal</div>

        <div class="portal-grid">
            <a class="portal-card" href="student/student-login.php">
                <div class="portal-icon student-icon">🎓</div>
                <div class="portal-title">Student</div>
                <div class="portal-text">Apply for clearance, upload documents and track approval.</div>
            </a>

            <a class="portal-card" href="administrative/administrator-login.php">
                <div class="portal-icon administrator-icon">🏢</div>
                <div class="portal-title">Administration</div>
                <div class="portal-text">Department officer login for reviewing requests.</div>
            </a>

            <a class="portal-card" href="admin/admin-login.php">
                <div class="portal-icon admin-icon">🛡</div>
                <div class="portal-title">Admin</div>
                <div class="portal-text">Manage clearance records and generate certificates.</div>
            </a>
        </div>
    </div>
</body>
</html>