echo "<span id='edu_fill'>";   echo "<h3>Education</h3>";     
echo "<table class='dashed'>";

echo "<tr><td>";

//create the form
if(mysqli_num_rows($edu_find) < 1){                                
echo "<p class='notice center'>You have not made any education entries.</p>";
echo "<div class='panel rad'><table><tr><td width='25%' class='left_side'>College</td><td>";
     //le education fields           
     echo "<input class='largeform flick school_search' value='College entered/currently entering...'>";

echo "</td></tr></table></div>";

echo "<div class='panel rad'><table><tr><td width='25%' class='left_side'>High School</td><td>";
     echo "<input class='largeform flick school_search' value='High school entered/currently entering...'>";
echo "</td></tr></table></div>";

}else{ 
echo '<div class="edu_listing">';
while($edu_list = mysqli_fetch_assoc($edu_find)){

$nt = isset($nt) ? $nt . ", " .$edu_list['school_type'] : $edu_list['school_type'];   

$class_sg_check = mysqli_query($db_main, "SELECT * FROM snowglobes WHERE special_settings='school' AND reference_num='$edu_list[s_id]'");
?>
<div class="contentbox hide" s_id="<?php echo $edu_list['s_id']; ?>">
<h3>Classes on <em><?php echo $edu_list['name']; ?></em> <a href="close-window" class="button_samp rad greened prompt" return_to="#vc2">Close Panel</a></h3> 


<div id="new_snowglobe"> <table>
<tr class="h4_row"><td><?php echo $nx['71']; ?></td><td><?php echo $nx['72']; ?></td></tr>

<tr class="message"><td width="50%" class="notice2<?php if(mysqli_num_rows($class_sg_check) > 0){ echo " top_off"; }?>">


<?php if(mysqli_num_rows($class_sg_check) > 0){ 
//has classes in school or none
?>

<div class="search_box"><input class="largeform flick chop" type="text" value="<?php echo $nx['74']; ?>"></div> 

<div class="load_classes" school_id="<?php echo $edu_list['s_id'];?>"></div>  

<?php } else { ?>

<?php echo $nx['55']; ?>

<?php } ?>    </td>

<td class="form_cobble" valign="top">

<p><?php echo $nx['73']; ?></p>
 
<!-- <input type="text" value="" name="" class="flick largeform" validation="{new_sg:x}">  -->
<input type="text" value="Name of class/subject, or teacher's name, or both." name="sg_name" class="flick largeform">
<input type="text" value="<?php echo $edu_list['name'];?>" name="sg_schoolforwhich" disabled class="largeform">
<input type="hidden" name="sg_school_id" value="<?php echo $edu_list['s_id'];?>">
<textarea name="sg_desc" class="flick largeform" validation="new_sg:desc">
Describe the class, insert useful material, etc.
</textarea>

<button class="right q_submit" submit="new_class_sg" submit_call="sg_">Start classroom discussions!</button>

</td> </tr></table>    
</div>

</div>
     <!-- <h3><?php echo $edu_list['school_name']; ?></h3>



<?php echo $edu_list['']; ?> -->   

<div class="box">                      
<h3 class='inline_block'><?php echo $edu_list['name']; ?></h3><a href="find-classes" class="school_menu spec rad prompt full_glow" school_id="<?php echo $edu_list['s_id']?>"><span>&#10004;</span> Find Your Classes!</a>
<a href="delete_record:<?php echo $edu_list['e_id']; ?>" class="school_menu spec rad prompt">Delete Education Entry</a>
<a href="other_options" class="school_menu spec rad prompt">Other Options</a>
<h4 class='noselect'>History</h4>    
<table>
<tr>
<th class="left_side">Year Started</th>
<td><?php echo $edu_list['started']; ?></td>
</tr>
<tr>
<th class="left_side">Year Finished</th>
<td><?php echo ($edu_list['is_current'] == "true") ? $nx['54'] : $edu_list['finished']; ?></td>
</tr>

</table>
<h4 class='noselect'>School Details</h4>
<table><tr>
<th class="left_side">Link</th>  
<td><a href='<?php echo $edu_list['link']; ?>'><?php echo $edu_list['link']; ?></a></td>
</tr></table>
</div> 

<?php }

if(count($nt) < 2){

if(!preg_match("#College#",$nt)){

echo "<div class='panel rad'><table><tr><td width='25%' class='left_side'>College</td><td>";
     //le education fields           
     echo "<input class='largeform flick school_search' value='College entered/currently entering...'>";

echo "</td></tr></table></div>";

}

if(!preg_match("#High School#",$nt)){

echo "<div class='panel rad'><table><tr><td width='25%' class='left_side'>High School</td><td>";
     echo "<input class='largeform flick school_search' value='High school entered/currently entering...'>";
echo "</td></tr></table></div>";

}



}


  echo "</div>";

}
echo "</td></tr>";

echo "</table>";

echo "</span>";