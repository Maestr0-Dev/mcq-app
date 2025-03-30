<!-- filepath: c:\xampp\htdocs\mcq-app\teachers\teacher_nav.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="myCss/nav.css">
    <style>
        .teacher-nav {
            background: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .teacher-nav .logo {
            font-size: 20px;
            font-weight: bold;
        }
        .teacher-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .teacher-nav ul li {
            margin: 0 15px;
        }
        .teacher-nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        .teacher-nav ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="teacher-nav">
        <div class="logo">Teacher Portal</div>
        <ul>
            <li><?php
      if(isset( $_SESSION['teacher_id'])){
        $path = "teach_profil_imgs/" . $_SESSION['profile_picture'];
        ?>
    <a href="teacher_profile.php">My Profile <i class="fa fa-user"></i>
        <img src="<?=$path?>" alt="Profile Picture" style="width: 30px; height: 30px; border-radius: 50%; margin-left: 10px;">
</a>
    <?php 
      }
    else{?>
      <a href="login.php" style="border: 1px solid white; background: none; color: gray; padding: 10px 20px; text-decoration: none; font-size: 18px; margin: 0 10px; border-radius: 5px;">Login <i class="fa fa-sign-in-alt"></i></a>
    <?php
    }
    ?></li>
            <li><a href="teacher_students.php">My Students</a></li>
            <li><a href="teacher_quiz.php">Quiz</a></li>
            <li><a href="teacher_community.php">Community</a></li>
        </ul>
    </div>
</body>
</html>