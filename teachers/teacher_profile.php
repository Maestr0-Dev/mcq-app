<?php
session_start();
include 'C:\xampp\htdocs\mcq-app\classes.php';

if (!isset($_SESSION['teacher_id'])) {
    header("location:teacher_login.php");
    exit();
}

$db = new DB();
$teacher_id = $_SESSION['teacher_id'];
$teacher = $db->getTeacherById($teacher_id);

// Check verification status
$is_verified = $db->checkVerificationStatus($teacher_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subjects = $_POST['subjects'];
    $profile_picture = $teacher['profile_picture'];

    // Handle profile picture upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $fileName = $_FILES["profile_picture"]["name"];
        $fileSize = $_FILES["profile_picture"]["size"];
        $tmpName = $_FILES["profile_picture"]["tmp_name"];
        $validImageExtension = ['jpeg', 'jpg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 500000) {
            $newImageName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, 'teach_profil_imgs/' . $newImageName);
            $profile_picture = $newImageName;
        }
    }

    // Update teacher information
    $data = [$fullname, $email, $phone, $subjects, $profile_picture, $teacher_id];
    $db->updateTeacher($data);

    // Update session data
    $_SESSION['full_name'] = $fullname;
    $_SESSION['profile_picture'] = $profile_picture;

    header("location:teacher_profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link type="text/css" rel="stylesheet" href="myCss/profile.css">
    <style>
        .verified-icon {
            color: green;
            font-size: 20px;
            margin-left: 10px;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h1>My Profile</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <img src="teach_profil_imgs/<?= $teacher['profile_picture'] ?>" alt="Profile Picture">
            <?php if ($is_verified): ?>
            <p><i class="verified-icon">✔ Verified</i></p>
        <?php else: ?>
            <a href="get_verified.php">Get Verified</a>
        <?php endif; ?>
            <input type="text" name="fullname" value="<?= $teacher['full_name'] ?>" required placeholder="Full Name">
            <input type="email" name="email" value="<?= $teacher['email'] ?>" required placeholder="Email">
            <input type="number" name="phone" value="<?= $teacher['phone'] ?>" required placeholder="Phone">
            <textarea name="subjects" required placeholder="Subjects"><?= $teacher['subjects'] ?></textarea>
            <div class="file-input-container">
                <label class="file-input-label">
                    <span>Choose Profile Picture</span>
                    <input type="file" name="profile_picture">
                </label>
                <div class="file-name"></div>
            </div>
            <button type="submit">Update Profile</button>
        </form>
            <a href="C:\xampp\htdocs\mcq-app\logout.php">
        <button>Logout</button>

            </a>
    </div>
   
<script>
document.querySelector('input[type="file"]').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
    var fileNameDisplay = document.querySelector('.file-name');
    fileNameDisplay.textContent = fileName;
    fileNameDisplay.style.display = 'block';
});
</script>
</body>
</html>