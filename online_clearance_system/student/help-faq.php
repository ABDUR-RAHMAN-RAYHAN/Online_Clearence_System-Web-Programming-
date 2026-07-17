<?php
session_start();

if(!isset($_SESSION["student_id"])){
    header("Location: student-login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help & FAQ</title>
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
            <a class="nav-item" href="upload-documents.php">Upload Documents</a>
            
            <a class="nav-item" href="track-approval.php">Track Approval</a>
            <a class="nav-item" href="moi-certificate.php">MOI Certificate</a>
            <a class="nav-item" href="emergency-request.php">Emergency Request</a>
        </div>

        <div class="bottom-nav">
            <a class="nav-item active" href="help-faq.php">Help & FAQ</a>
            <a class="nav-item" href="settings.php">Settings</a>
            <a class="nav-item" href="../logout.php">Log Out</a>
        </div>
    </div>

    <div class="right">
        <div class="top">
            <div class="top-title">Help & FAQ</div>
            <div class="top-icons">♧ ✉ ⚙</div>
        </div>

        <div class="content">
            <div class="welcome">Help & Frequently Asked Questions</div>

            <div class="frontend-card">
                <div class="section-title">General Questions</div>

                <div class="faq-box">
                    <div class="faq-item">
                        <h3>1. How can I apply for clearance?</h3>
                        <p>Go to Apply Clearance, select clearance type, department, write your reason, and submit the request.</p>
                    </div>

                    <div class="faq-item">
                        <h3>2. How can I upload documents?</h3>
                        <p>Go to Upload Documents, choose the document type, select your file, and click upload.</p>
                    </div>

                    <div class="faq-item">
                        <h3>3. How can I update an uploaded document?</h3>
                        <p>If a document is already uploaded, use the update option to upload a new version.</p>
                    </div>

                    <div class="faq-item">
                        <h3>4. How can I track approval?</h3>
                        <p>Go to Track Approval to see your document status and final clearance status.</p>
                    </div>

                    <div class="faq-item">
                        <h3>5. What should I do if my document is rejected?</h3>
                        <p>Read the admin comment, fix the problem, then upload the document again.</p>
                    </div>

                    <div class="faq-item">
                        <h3>6. How can I request emergency clearance?</h3>
                        <p>Go to Emergency Request, select department, write your emergency reason, and submit.</p>
                    </div>

                    <div class="faq-item">
                        <h3>7. How can I request an MOI Certificate?</h3>
                        <p>Go to MOI Certificate, write your reason, and submit your request.</p>
                    </div>
                </div>
            </div>

            <div class="frontend-card">
                <div class="section-title">Need More Help?</div>

                <p class="help-text">
                    If you face any problem, contact the university admin office.
                </p>

                <div class="help-contact">
                    <p><b>Email:</b> admin@university.edu</p>
                    <p><b>Phone:</b> +880 1234 567890</p>
                    <p><b>Office Hour:</b> Sunday to Thursday, 9:00 AM - 5:00 PM</p>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>