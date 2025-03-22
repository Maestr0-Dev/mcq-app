<?php
session_start();
include 'classes.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $creator_id = $_SESSION['id'];
    $pass=$_POST['pass'];
    if(!empty($_FILES['profile_picture']['name'])){
    
        $fileName= $_FILES["profile_picture"]["name"];
        $fileSize= $_FILES["profile_picture"]["size"];
        $tmpName= $_FILES["profile_picture"]["tmp_name"];
        $validImageExtension=['jpeg','jpg','png'];
        $imageExtension=explode('.',$fileName);
        $imageExtension=strtolower(end($imageExtension));
        if(!in_array($imageExtension, $validImageExtension)){
            echo"
            <p>Invalid image type</p>
            ";
        }else if($fileSize>500000){
            echo"
            <p>Image too big</p>
            ";
        }else{
            $newImageName=uniqid();
            $newImageName .= '.' . $imageExtension;
    
            move_uploaded_file($tmpName, 'comm_profil_imgs/'. $newImageName);
    
    }
}
if($_SESSION['level']=='A' || $_SESSION['level']=='O'){
    $data = [$creator_id,0,$name, $description,$pass, $newImageName];
    $db = new DB();
    $db->newCommunity($data);
    header("location:communities.php");
}   else{
    $data = [0,$creator_id,$name, $description,$pass, $newImageName];
    $db = new DB();
    $db->newCommunity($data);
    header("location:communities.php");
}
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create community</title>
</head>
<body>
    <a href="communities.php">Discard</a>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Name">
        <input type="password" name="pass" placeholder="Password">
        <input type="text" name="description" placeholder="Description">
        <input type="file" id="input" name="profile_picture">
        <div class="preview">
            <img id="preview" src="" alt="">
        </div>
        <button type="submit">Create</button>
    </form>
    <script>

//to view uploaded image
    const imageInput=document.getElementById('input');
    const imagePreview=document.getElementById('preview');
    imageInput.addEventListener('change',function(){
        const file=this.files[0];
        if(file){
            const reader=new FileReader();
            reader.onload=function(e){ 
                imagePreview.src=e.target.result;
                imagePreview.style.display='block';
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>