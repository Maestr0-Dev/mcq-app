<?php
include "admin_class.php";


function stud(){
    $db= new DB();
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
    $db= new DB();

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
    <title>Document</title>
    <style>
img{
    width:40px;
    height:40px;
    border-radius:40px;
}

    </style>
</head>
<body>
<h1>All Users</h1>

  <div>
    <h2>Students:<?php countStudents()?></h2>
  </div>
  <div>
    <h2>Teachers:<?php countTeach()?></h2>
  </div>
<div class="tabs">
<button class="tab-btn active"  data-target="stud-sec">Students</button>
<button class="tab-btn" data-target="teach-sec">Teachers</button>
</div>

   <!-- students section -->
<div class="section active" id="stud-sec">
<h2>All Students</h2>

    <table>
    <tr>
        <th>Name</th>
        <th>E-mail</th>
        <th>Phone</th>
        <th>level</th>
    </tr>
    <?php
 stud();
?>
</div>
    </table>
   
<!-- tecahers section -->
    <div class="section" id="teach-sec">
<h2>All Teachers</h2>

    <table>
    <tr>
        <th>Profile</th>
        <th>Name</th>
        <th>E-mail</th>
        <th>Phone</th>
        <th>Subjects</th>

    </tr>
    <?php
 teachers();
?>
</div>
    </table>

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
