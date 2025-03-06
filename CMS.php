<?php
include "classes.php";
$year="";
$title="";
$subject="";
$instruction="";
$quest="";
$table="";
$A="";
$B="";
$C="";
$D="";
$ans="";
$err=false;
$subj=['Literature','History','Physics','English language','Mathemathics','Futher mathemathics','ICT','Computer science','Chemistry','Biology','Economics','French','Geography','Religion'];

if($_SERVER['REQUEST_METHOD']=='POST'){
$table=$_POST['exam'];
$year=$_POST['year'];
$title=$_POST['title'];
$subject=$_POST['subject'];
$instruction=$_POST['instruction'];
$quest=$_POST['question'];
$A=$_POST['A'];
$B=$_POST['B'];
$C=$_POST['C'];
$D=$_POST['D'];
//answer selection
$options =[
    'A'=>$A,
    'B'=>$B,
    'C'=>$C,
    'D'=>$D
];
$ans=$options[$_POST['ans']];

//image to database
 
if(!empty($_FILES['diagram']['name'])){
    
    $fileName= $_FILES["diagram"]["name"];
    $fileSize= $_FILES["diagram"]["size"];
    $tmpName= $_FILES["diagram"]["tmp_name"];
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

        move_uploaded_file($tmpName, 'diagrams/'. $newImageName);

   

}
   
$data=[$year,$title,$subject,$instruction,$quest,$A,$B,$C,$D,$newImageName," ",$ans];
$db= new DB();
$result=$db-> questions($table, $data);
 echo "
 <script>
 alert('Added Successfully')
 </script>
 ";
 $data=[];

}
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
    <title>Quiz-master</title>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</head> 
<body>

    <div class="container">

<div class="header">
    <h1 style="color:white;"> CONTENT MANAGMENT SYSTEM</h1>
<p>This section is reserved for administrators and is used to add and edit the questions for the users</p>
</div>
<form action="" method="post"  enctype="multipart/form-data">
    <div class="question_info">
<select name="exam" id="" required>
    <option disable>Select the type</option>
    <option value="o_level_mock" disabled>O-level Mock</option>
    <option value="o_level_gce">O-level GCE</option>
    <option value="a_level_mock" disabled>A-level Mock</option>
    <option value="a_level_gce">A-level GCE</option>
</select>
<select name="year" id="" required>
    <option value="">select the year</option>
    <?php
    $date=date("Y");
    for($i=2015;$i<$date;$i++){
        ?>
        <option value="<?=$i?>"><?=$i?></option>
    <?php
    }
    ?>
</select>

<input type="text" name="title" placeholder="title">
<select name="subject" id="subj" required>
    <option value="" selected disabled>subject</option>
    <?php
    
        for($i=0;$i<count($subj);$i++){
            ?>
            <option value="<?=$subj[$i]?>"><?=$subj[$i]?></option>
     <?php }
    ?>
</select>
<input type="text" name="instruction" placeholder="instructions">
</div>
<br><textarea name="question" id="" required>
enter the question
</textarea>
 
    <div>
<p>A: <input  name="A" type="text" ></p>
<p>B: <input  name="B" type="text" ></p>
<p>C: <input  name="C" type="text" ></p>
<p>D: <input  name="D" type="text" ></p>
<p>
    <p>Select the answer to the question</p>
    <span><input name="ans" value="A" type="radio" >A</span>
    <span><input name="ans" value="B" type="radio" >B</span>
    <span><input name="ans" value="C" type="radio" >C</span>
    <span><input name="ans" value="D" type="radio" >D</span>
    
</p>
</div>
<div>
<label for="">Select an image if any
<input class="btn btn-primary" name="diagram" id="input" type="file" accept="image/*" style="color:white;" placeholder="Select an image">
</label>
<div class="imgDiv">
<img id="preview" src="" alt="your image" style="max-width: 300px; display:none;">
</div>
</div>
<button type="submit" class="save"><b>Save</b></button>
<button class="view"> View questions info</button>

</form>
    </div>
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