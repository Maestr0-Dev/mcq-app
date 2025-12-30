<?php
session_start();
include 'C:\xampp\htdocs\mcq-app\classes.php';

if (!isset($_SESSION['teacher_id'])) {
    header("location:login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_SESSION['teacher_id'];
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $id_card_path = '';
    $certificate_path = '';

    // Handle ID card upload
    if (!empty($_FILES['id_card']['name'])) {
        $id_card_name = basename($_FILES['id_card']['name']);
        $id_card_tmp = $_FILES['id_card']['tmp_name'];
        $id_card_size = $_FILES['id_card']['size'];
        $id_card_ext = strtolower(pathinfo($id_card_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($id_card_ext, $allowed_ext) && $id_card_size < 5000000) {
            $id_card_path = 'verification_docs/' . uniqid('', true) . '.' . $id_card_ext;
            move_uploaded_file($id_card_tmp, $id_card_path);
        } else {
            $message = "Invalid file type or size for ID card.";
        }
    }

    // Handle teacher certificate upload
    if (!empty($_FILES['certificate']['name'])) {
        $certificate_name = basename($_FILES['certificate']['name']);
        $certificate_tmp = $_FILES['certificate']['tmp_name'];
        $certificate_size = $_FILES['certificate']['size'];
        $certificate_ext = strtolower(pathinfo($certificate_name, PATHINFO_EXTENSION));

        if (in_array($certificate_ext, $allowed_ext) && $certificate_size < 5000000) {
            $certificate_path = 'verification_docs/' . uniqid('', true) . '.' . $certificate_ext;
            move_uploaded_file($certificate_tmp, $certificate_path);
        } else {
            $message = "Invalid file type or size for certificate.";
        }
    }

    // Save verification request to the database
    $db = new DB();
    $db->submitVerificationDetails($teacher_id, $full_name, $dob, $id_card_path, $certificate_path);

    $message = "Verification request submitted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Verified - Teacher Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Include the sidebar navigation -->
    
    
    <div class="main-content">
        <div class="verification-card">
            <div class="card-header">
                <i class="fas fa-shield-alt verification-icon"></i>
                <h2>Teacher Verification</h2>
                <p>Enhance your profile credibility and unlock premium features</p>
            </div>
            
            <div class="card-body">
                <?php if (isset($message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <div class="benefits-section">
                    <h3>Benefits of Verification</h3>
                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <i class="fas fa-star"></i>
                            <h4>Priority Listing</h4>
                            <p>Your profile will be pushed forward in search results</p>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-user-check"></i>
                            <h4>Trust Badge</h4>
                            <p>Gain the trust of students and platform founders</p>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-unlock"></i>
                            <h4>Exclusive Access</h4>
                            <p>Unlock special features and opportunities</p>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-comments"></i>
                            <h4>Direct Support</h4>
                            <p>Priority customer service and communication</p>
                        </div>
                    </div>
                </div>
                
                <div class="verification-form-container">
                    <h3>Submit Your Verification</h3>
                    <form action="" method="post" enctype="multipart/form-data" class="verification-form">
                        <div class="form-group">
                            <label for="full_name">Full Legal Name</label>
                            <input type="text" id="full_name" name="full_name" required placeholder="As it appears on your ID">
                        </div>
                        
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                        
                        <div class="form-group file-group">
                            <label for="id_card">Government-Issued ID</label>
                            <div class="file-upload">
                                <input type="file" id="id_card" name="id_card" required class="file-input">
                                <div class="file-upload-label">
                                    <i class="fas fa-id-card"></i>
                                    <span class="file-name">Choose file...</span>
                                </div>
                            </div>
                            <p class="form-help">Upload a clear photo of your ID card, passport, or driver's license</p>
                        </div>
                        
                        <div class="form-group file-group">
                            <label for="certificate">Teaching Certificate</label>
                            <div class="file-upload">
                                <input type="file" id="certificate" name="certificate" required class="file-input">
                                <div class="file-upload-label">
                                    <i class="fas fa-certificate"></i>
                                    <span class="file-name">Choose file...</span>
                                </div>
                            </div>
                            <p class="form-help">Upload your teaching certification or qualification document</p>
                        </div>
                        
                        <div class="privacy-note">
                            <i class="fas fa-lock"></i>
                            <p>Your documents are securely stored and only used for verification purposes.</p>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-check-circle"></i> Submit Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // JavaScript to update file input labels with the selected filename
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('.file-input');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const fileName = this.files[0]?.name || 'Choose file...';
                    const fileNameElement = this.parentElement.querySelector('.file-name');
                    fileNameElement.textContent = fileName;
                });
            });
        });
    </script>
</body>
</html>
