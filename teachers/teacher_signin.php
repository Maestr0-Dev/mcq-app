<?php
include 'C:\xampp\htdocs\mcq-app\classes.php';

$name = "";
$pw = "";
$email = "";
$phone = "";
$subjects = "";
$error = false;
$result = "";
$newImageName = ""; // Initialize newImageName

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
            echo "<p style='color:red;'>Invalid image type</p>";
            $error = true;
        } else if ($fileSize > 500000) {
            echo "<p style='color:red;'>Image too big</p>";
            $error = true;
        } else {
            $newImageName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, 'teach_profil_imgs/' . $newImageName);
        }
    } else {
        echo "<p style='color:red;'>Please upload a profile picture.</p>";
        $error = true;
    }

    if (!$error) {
        $data = [$name, $email, $phone, $pw, $subjects, $newImageName, $date];
        $db = new DB();
        $result = $db->newTeacher($data);

        if ($result === true) {
            header("location:login.php");
            exit(); // Make sure to exit after redirection
        } else {
            $result = "<span style='color:red;'>Sign up failed. Please try again.</span>";
        }
    }
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
            font-family: sans-serif; /* Modern sans-serif font */
            background: #e0eafc; /* Light background */
            background: linear-gradient(to right bottom, #e0eafc, #6dd5ed); /* Light blue gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Use min-height for better responsiveness */
            margin: 0;
            padding: 20px; /* Add some padding around the body */
            box-sizing: border-box; /* Ensure padding doesn't add to width */
        }
        .signup-container {
            background: white;
            padding: 40px; /* More padding for a spacious feel */
            border-radius: 12px; /* More rounded corners */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Softer shadow */
            width: 90%; /* Make it responsive */
            max-width: 400px; /* Maximum width */
            text-align: center;
        }
        .signup-container h1 {
            color: #374151; /* Darker, modern text color */
            margin-bottom: 30px;
            font-size: 2.2em; /* Slightly larger heading */
            font-weight: 600; /* Semi-bold */
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left; /* Align labels to the left */
        }
        .form-group label {
            display: block;
            color: #4b5563; /* Slightly lighter text for labels */
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9em;
        }
        .signup-container input[type="text"],
        .signup-container input[type="password"],
        .signup-container input[type="email"],
        .signup-container input[type="number"],
        .signup-container input[type="file"],
        .signup-container select {
            width: calc(100% - 20px); /* Adjust width for padding */
            padding: 12px;
            margin-bottom: 15px; /* Increased margin */
            border: 1px solid #d1d5db; /* Light gray border */
            border-radius: 8px; /* Rounded input fields */
            font-size: 1em;
            box-sizing: border-box; /* Ensure padding doesn't add to width */
        }
        .signup-container button {
            background: linear-gradient(to right, #6a5acd, #8a2be2); /* Blue to purple gradient */
            color: white;
            padding: 14px 24px; /* More padding for the button */
            border: none;
            border-radius: 8px; /* Rounded button */
            cursor: pointer;
            width: 100%;
            font-size: 1.1em;
            font-weight: 500;
            transition: background 0.3s ease; /* Smooth transition for hover effect */
        }
        .signup-container button:hover {
            background: linear-gradient(to right, #5a45bd, #781be2); /* Slightly darker on hover */
        }
        .signup-container a {
            display: block;
            margin-top: 20px;
            color: #6b7280; /* Gray color for the link */
            text-decoration: none;
            font-size: 0.9em;
        }
        .signup-container a:hover {
            text-decoration: underline;
        }
        .signup-container p {
            margin-top: 15px;
            font-size: 0.9em;
            color: #374151;
        }
        .signup-container p span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Teacher Sign Up</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Choose a strong password">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Your email address">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="number" id="phone" name="phone" required placeholder="Your phone number">
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" required>
                <small style="color:#6b7280;">Accepts JPEG, JPG, PNG (Max 500KB)</small>
            </div>
            <div class="form-group">
                <label for="subjects">Subjects Taught</label>
                <input type="text" id="subjects" name="subjects" required placeholder="e.g., Math, Science, English">
            </div>
            <button type="submit">Sign Up</button>
            <p><?= $result ?></p>
            <a href="login.php">Already have an account? Log In</a>
        </form>
    </div>
</body>
</html>