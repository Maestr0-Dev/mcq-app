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
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .profile-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        .profile-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .profile-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .profile-container input, .profile-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .profile-container button {
            background: linear-gradient(to left, purple, blue);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h1>My Profile</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <img src="teach_profil_imgs/<?= $teacher['profile_picture'] ?>" alt="Profile Picture">
            <input type="text" name="fullname" value="<?= $teacher['full_name'] ?>" required placeholder="Full Name">
            <input type="email" name="email" value="<?= $teacher['email'] ?>" required placeholder="Email">
            <input type="number" name="phone" value="<?= $teacher['phone'] ?>" required placeholder="Phone">
            <textarea name="subjects" required placeholder="Subjects"><?= $teacher['subjects'] ?></textarea>
            <input type="file" name="profile_picture">
            <button type="submit">Update Profile</button>
        </form>
        <a href="get_verified.php">Get Verified</a>
    </div>
</body>
</html>