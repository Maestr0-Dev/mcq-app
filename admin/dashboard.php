<?php
include "admin_class.php";

function stud(){
    $db= new admindb();
    $stud= $db->getAllSTudents();
    foreach($stud as $student){
        echo "<tr>";
        echo "<td>".$student['stud_name']."</td>";
        echo "<td>".$student['email']."</td>";
        echo "<td>".$student['number']."</td>";
        echo "<td>".$student['level']."</td>";
        echo "</tr>";
    }
}

function teachers(){
    $db= new admindb();
    $teachers=$db->getAllTeachers();
    foreach($teachers as $t){
        echo"<tr>";
        echo"<td>".
        "<img src='../teachers/teach_profil_imgs/".$t['profile_picture']."'>".
        "</td>";
        echo"<td>".$t['full_name']."</td>";
        echo"<td>".$t['email']."</td>";
        echo"<td>".$t['phone']."</td>";
        echo"<td>".$t['subjects']."</td>";
        echo"</tr>";
    }
}

function countStudents(){
    $db= new DB();
    $count=$db->countStud();
    echo $count;
}

function countTeach(){
    $db= new DB();
    $count=$db->countTeach();
    echo $count;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .main-content {
            padding: 20px;
        }
        img {
            width:40px;
            height:40px;
            border-radius:40px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        switch ($page) {
            case 'verifications':
                include 'Verifications.php';
                break;
            case 'trusted_teachers':
                include 'trusted_teachers.php';
                break;
            case 'study_page_management':
                include 'study_page_management.php';
                break;
            case 'ai_agent':
                include 'ai_agent.php';
                break;
            case 'cms':
                include 'CMS.php';
                break;
            case 'home':
            default:
                echo '<h1>All Users</h1>';
                echo '<div><h2>Students: <?php countStudents(); ?></h2></div>';
                echo '<div><h2>Teachers: <?php countTeach(); ?></h2></div>';
                echo '<div class="tabs">';
                echo '<button class="tab-btn active" data-target="stud-sec">Students</button>';
                echo '<button class="tab-btn" data-target="teach-sec">Teachers</button>';
                echo '</div>';
                echo '<div class="section active" id="stud-sec">';
                echo '<h2>All Students</h2>';
                echo '<table><tr><th>Name</th><th>E-mail</th><th>Phone</th><th>level</th></tr>';
                stud();
                echo '</table></div>';
                echo '<div class="section" id="teach-sec">';
                echo '<h2>All Teachers</h2>';
                echo '<table><tr><th>Profile</th><th>Name</th><th>E-mail</th><th>Phone</th><th>Subjects</th></tr>';
                teachers();
                echo '</table></div>';
                break;
        }
        ?>
    </div>

    <script>
        const tabButtons = document.querySelectorAll('.tab-btn');
        const sections = document.querySelectorAll('.section');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                sections.forEach(section => section.classList.remove('active'));

                button.classList.add('active');
                const targetSection = document.getElementById(button.dataset.target);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
