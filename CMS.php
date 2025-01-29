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
$imageString="";
 $imageFileType="";
$err=false;
$O_subj=['Mathemathics','Chemistry','Biology','Computer science','Geography','Economics','Literature','History','English language','Religion','French'];
$A_subj=['Literature','History','English language','Mathemathics','Futher mathemathics','ICT','Computer science','Chemistry','Biology','Economics','French'];

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
    $target_dir = "./diagrams"; 
    $poster = $target_dir . basename($_FILES["diagram"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($poster,PATHINFO_EXTENSION); 
    $check = getimagesize($_FILES["diagram"]["tmp_name"]);

    if($check === false) {
    $uploadOk = 0;
    $pictureErr = "The file is not a picture or it is too large";
    }
    if ($_FILES["diagram"]["size"] > 8000000) {
        $pictureErr = "The file is too large. It must be at most 8 megabytes";
        $uploadOk = 0;
    }
    if($imageFileType != "GIF" && $imageFileType != "PNG" && $imageFileType != "JPG" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $pictureErr = "Only JPG, JPEG, PNG and GIF file are allowed.";
        $uploadOk = 0;
    } 
    $imageContent = file_get_contents($_FILES["diagram"]["tmp_name"]); 
    $imageString = base64_encode($imageContent); 



}
    $data=[$year,$title,$subject,$instruction,$quest,$A,$B,$C,$D,$imageString, $imageFileType,$ans];
    $db= new DB();
    $result=$db-> questions($table, $data);
    echo $result;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="style.css">
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
    <title>brave quiz</title>
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
    <option value="o_level_mock o_level">O-level Mock</option>
    <option value="o_level_gce o_level">O-level GCE</option>
    <option value="a_level_mock a_level">A-level Mock</option>
    <option value="a_level_gce a_level">A-level GCE</option>
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

<input type="text" name="title" required placeholder="title">
<select name="subject" id="">

<?php
    $lvl=$_POST['exam'];
    if($lvl=='o_level'){
        for($i=0;$i<11;$i++){
            ?>
            <option value="<?$O_subj[i]?>"><?$O_subj[i]?></option>
      <?  }
    }else{
        for($i=0;$i<11;$i++){
            ?>
            <option value="<?$A_subj[i]?>"><?$A_subj[i]?></option>
      <?  }
    }
    ?>
</select>
<input type="text" name="instruction" placeholder="instructions" required>
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