<?php
session_start();
include 'classes.php';
//display all existing communities with a button to join
$db = new DB();
$communities = $db->getExistingCommunities();
//display communities where user with id is a member
$db2 = new DB();
$myCommunities = $db2->comm_members($_SESSION['id']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community</title>
</head>
<body>
   <a href="create_com.php">Create a community</a>
<div class='Allcommunit'>
<h1>All Communities</h1>
<?php
foreach ($communities as $community) {
    $path = "comm_profile/" . $community['img'];
?>
<div class="community">
    <img src="<?=$path?>" alt="profile">
<h2><?=$community['name']?></h2>
<p><?=$community['description'] ?></p>
<button>Join</button>
</div>
<?php
}
?>
</div>

<div class='Allcommunit'>
<h1>My communities</h1>

<?php
foreach ($myCommunities as $myCom) {
$path = "comm_profile/" . $myCom['img'];

?>

<div class="community">
<img src="<?=$path?>" alt="profile">
<h2><?=$myCom['name']?></h2>";
<p><?=$myCom['description'] ?></p>";
</div>
<?php
}
?>
</div>

</body>
</html>