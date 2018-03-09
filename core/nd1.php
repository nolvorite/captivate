

<!-- <link rel="stylesheet" type="text/css" href="<?php echo main_dir; ?>core/css.css">     style-->
<link rel="stylesheet" type="text/css" href="<?php echo main_dir; ?>core/light_theme.css">     <!--light theme-->
<link rel="icon" type="image/png" href="<?php echo main_dir; ?>core/img/icon.png"> 
<?php



/*html start*/
echo "<title>";

if(isset($_GET['query']) && $swiss_army->compare_dz($logged_dt['salt'],$_SESSION['salt_q'])){
switch(count($_GET)){
case 1: echo "Editing Profile and Settings";        break;
case 2:    if(isset($_GET['dispos'])){
switch($_GET['dispos']){
case "new_school": 
echo "Captivate - Adding unlisted school"; 
break;
}

}  }
}

if(isset($_GET['snowglobe'])){
echo $sg_details['sg_name'];
}



if(isset($_GET['direct']) || preg_match("#index.php([\072]([0-9]){0,15})*$#", $swiss_army->extraurl())){

if(isset($_GET['direct'])){if($_GET['direct'] == "signup"){
echo "Sign up at Captivate";
}

  } else{echo "Captivate - Varied Social Media";} 
}
if(isset($_GET['profile'])){
echo "Captivate - ". $_FILTERED['profile']."'s snowglobe";
}

if(isset($_GET['thread_view'])){
$snip = (isset($_GET['comment'])) ? $nx['26'] : "";
echo $thread_data['title'];
}

if(isset($_GET['find'])){
if(isset($_GET['query'])){
switch($_GET['find']){
case "snowglobes":
echo "Snowglobes by " .$_FILTERED['query'];
break;
}
}
}

echo "</title>";  
echo '<meta charset="UTF-8">';

    /*html end*/    include("js.php");

/*function effic($snipe,$variable,$test_value,$functionar){//for annoying isset checks
if(!isset($snipe)){return false;  //won't show PHP errors, I think
}
}else{if(isset($variable)){
if($test_value == $variable){$functionar();}
else{return false;}
}
else{$functionar();}
}       poor substitution for isset() checks, but i'll keep it for later reference anyway */
 ?> 


