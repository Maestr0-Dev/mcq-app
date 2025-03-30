<?php
session_start();
include 'classes.php';
//display all existing communities with a button to join
$db = new DB();
$communities = $db->getExistingCommunities();
//display communities where user with id is a member
$db2 = new DB();
$CheckCommunities = $db2->CheckCommunities($_SESSION['id']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js"></script>
    <title>Community</title>
</head>
<body>
   <a href="create_com.php">Create a community</a>
<div class='Allcommunit'>
<h1>All Communities</h1>
<?php
foreach($CheckCommunities as $Check){

//     $id=$Check['comm_id'];
//     $db3 = new DB();
// $MyCommunities = $db3->MyCommunities($id);

    if($_SESSION['id'] != $Check['member_id'] ){
foreach ($communities as $community) {
    $path = "comm_profil_imgs/" . $community['img'];
    $_SESSION['com_id']=$community['com_id'];
?>
<div class="community">
    <img style="max-width:50px; height:auto;" src="<?=$path?>" alt="profile">
<h2><?=$community['com_name']?></h2>
<p><?=$community['describtion'] ?></p>
<button id="joinCom" onclick="join('<?=$community['com_id']?>','<?=$_SESSION['id']?>')">Join</button>
</div>
<?php
break;
}
}

}
?>
</div>

<div class='Allcommunit'>
<h1>My communities</h1>

<?php
foreach($CheckCommunities as $Check){

    $id=$Check['comm_id'];
    $db3 = new DB();
$MyCommunities = $db3->MyCommunities($id);

foreach($MyCommunities as $myCom){
$path = "comm_profil_imgs/" . $myCom['img'];

?>

<div class="community">
<img style="max-width:50px; height:auto;" src="<?=$path?>" alt="profile">

<h2><?=$myCom['com_name']?></h2>
<p><?=$myCom['describtion'] ?></p>
</div>
<?php
}}
?>
</div>
<script src="jquery-3.1.0.min.js"></script>
</body>
</html>