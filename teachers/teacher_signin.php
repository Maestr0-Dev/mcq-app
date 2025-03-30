<?php 
include 'C:\xampp\htdocs\mcq-app\classes.php';


$name = ""; $pw = ""; $email = ""; $phone = ""; $subjects = "";
$error = false; $result = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['fullname'];
    $pw = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subjects = $_POST['subjects'];
    $date = date("Y-m-d h:i:s");
    $table = "teachers";

    if (!empty($_FILES['profile_picture']['name'])) {
        $fileName = $_FILES["profile_picture"]["name"];
        $fileSize = $_FILES["profile_picture"]["size"];
        $tmpName = $_FILES["profile_picture"]["tmp_name"];
        $validImageExtension = ['jpeg', 'jpg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));

        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<p>Invalid image type</p>";
        } else if ($fileSize > 500000) {
            echo "<p>Image too big</p>";
        } else {
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            move_uploaded_file($tmpName, 'teach_profil_imgs/' . $newImageName);
        }
    }

    $data = [$name, $email, $phone, $pw, $subjects, $newImageName,$date];
    $db = new DB();
    $result = $db->newTeacher($data);

    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Sign Up</title>
    <link type="text/css" rel="stylesheet" href="myCss/signin.css">
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
        .signup-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .signup-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .signup-container input, .signup-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .signup-container button {
            background: linear-gradient(to left, purple, blue);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin: 10px 0;
        }
        .signup-container a {
            display: block;
            margin-top: 10px;
            color: #666;
            text-decoration: none;
        }
        .signup-container a:hover {
            text-decoration: underline;
        }
        .signup-container p {
            margin: 10px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Teacher Sign Up</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="fullname" required placeholder="Full Name">
            <input type="password" name="password" required placeholder="Password">
            <input type="email" name="email" required placeholder="Email">
            <input type="number" name="phone" required placeholder="Phone">
            <input type="file" name="profile_picture" required>
            <input type="text" name="subjects" required placeholder="Subjects (e.g., Math, Science)">
            <button type="submit">Sign up</button>
            <a href="teacher_login.php">Already have an account? Login</a>
            <p><span style="color:green"><?= $result ?></span></p>
        </form>
    </div>
</body>
</html>