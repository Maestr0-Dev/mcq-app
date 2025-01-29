<?php
$year="";
$O_subj=['Mathemathics','Chemistry','Biology','Computer science','Geography','Economics','Literature','History','English language','Religion','French'];
$A_subj=['Literature','History','English language','Mathemathics','Futher mathemathics','ICT','Computer science','Chemistry','Biology','Economics','French'];
$level="";

?>
<form action="" method="post">
<select name="year" id="">
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
<select name="level" id="">
    <option value="o_level">O-level</option>
    <option value="a_level">A-level</option>
</select>
<select name="lev" id="">
    <?php
    $lvl=$_POST['level'];
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

</form>