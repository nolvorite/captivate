<?php     
      
echo "<span class='clear' id='nook'></span>

<table id='header'><tr><td width='1%'><a href='".main_dir."' id='logo'><img src='".image_dir."2.png'></a></td><td width='99%'>";       
if(!isset($_SESSION['login_q'])){       //not logged in to a user account, essentially. Should probably make this more elaborate too       

                                      #D4DBE5                    #1D3946               #5397B4

echo "<form action='".main_dir."index.php?verify=". $_SESSION['temp_n'] ."&direct=login' id='login' method='post'><input type='text' value='".$nx[0]."' name='usernorm' class='flick'><input type='password' name='pwrdnorm' value='".$nx[1]."' class='flick'><input type='submit' value='".$nx[2]."' class='dt1space'>
<a href='".main_dir."signup' class='signup-a'>".$nx[3]."</a>";
if(isset($_COOKIE['inc_ombination'])){
echo '<div class="notice left glow">'.$_COOKIE["inc_ombination"].'</div>';
setcookie("inc_ombination","yeah",time()-1);
}
echo "</form>";   
echo "</div></div>"  ;               }else{  //actually logged in. I know, such a fancy method for determining right, just a single session. I will probably change this. Preferably a session hash that's been salted. Was going to do it yesterday but had no time, now I don't feel like working on them

$profile_link = main_dir . "profile/" . $_MONITORED['login_q']; 
//menu
echo "<div id='user_menu'>
                                                  
<span class='drop'><div class='left uplink'>".$nx[11].", <a class='username' href='$profile_link'>". $_MONITORED['login_q'] ."</a>.  </div><div class='dropdown_content rad'><span class='quick_links'><a href='".main_dir."profile_nuise/". $_MONITORED['login_q'] ."/find/usedservices'>".$nx[12]."</a><a href='".main_dir."profile_nuise/". $_MONITORED['login_q'] ."'>".$nx[13]."</a><a href='".main_dir."logout/".$_MONITORED['temp_n']."'>".$nx[53]."</a></span></div></span>";


//Notifications
echo "<span class='drop' id='notifs_drop'><div class='left uplink' id='notifs_bar'><a href='".main_dir."profile_nuise/".$_MONITORED['login_q']."/notifs'>Notifications</a> </div>";
echo "<div class='dropdown_content rad' id='notifications'>";
$notifs_grab = mysqli_query($db_main, "SELECT n.content,n.url,n.towhom,n.stamptime,n.status,n.from,u.profile_pic_url FROM notifications n LEFT JOIN userz u ON n.from = u.username WHERE n.towhom='$_MONITORED[login_q]' ORDER BY n.stamptime DESC LIMIT 0,10");
if(mysqli_num_rows($notifs_grab) > 0){   



 while($notifs_iterate = mysqli_fetch_assoc($notifs_grab)){
 echo "<a href='". main_dir . $notifs_iterate['url'] ."'>";echo "<table>";
 $pp_url = $notifs_iterate['profile_pic_url'] === "none" ? image_dir."nopropic_small.png" : main_dir."images/".$notifs_iterate['profile_pic_url'];
 
 echo "<tr><td width='1%'><img src='$pp_url' alt='propic'></td><td>";
 
 $notifs_iterate['stamptime'] = $swiss_army->time_rounds($notifs_iterate['stamptime']);
 echo "<div class='notifs'>". preg_replace('#^<a href(.+)>(.+)<(.+)>(.+)$#','<span>$2</span> $4',$notifs_iterate['content']) ."<br><em>".$notifs_iterate['stamptime']."</em></div>";
echo "</td></tr>";
echo "</table></a>";                                                                
}


}
mysqli_free_result($notifs_grab);
echo"</div>";
echo"</span>";

//right side


echo"
</div>";}  echo "</td><td></td></tr></table>";          

// welcome note                                                                            
if(isset($_SESSION['welcome'])){echo "<div class='welcome_note' id='first1'>".$nx[4]."</div>";} 

echo "<table><tr id='body'><td id='left1'>" ; 

                 
if(logged_in_check){       

echo "<div id='left_menu'> <h3>Main Menu</h3>
<a href='$profile_link'>Your Profile</a>
<a href='".main_dir."profile_nuise/". $_MONITORED['login_q'] ."/find/snowglobes'>Your Snowglobes</a>
<a href='".main_dir."profile_nuise/". $_MONITORED['login_q'] ."/find/settings'>Display Settings</a>
<a href='message_panel' class='prompt'>Messages</a>
<a href='misc_opts' class='prompt'>Miscellaneous</a>
</div>";  

       
} 

if(isset($_GET['snowglobe'])){
    $fill_data->sg_sidebar($sg_details); 
}

if(logged_in_check){ if(index_page_check){  //promotion for our school lecture snowglobe feature
//Originally I had planned to call it "lecture notes" but it seems kind of ambiguous at this point

if(mysqli_num_rows($edu_select) > 0):

$find_followed_class_snowglobes = mysqli_query($db_main, "SELECT * FROM sg_permissions WHERE towhom='$_MONITORED[login_q]' AND granted_by REGEXP '[/05B]sc[/05C]$' AND EXISTS(SELECT * FROM education WHERE forwhom='$_MONITORED[login_q]' AND is_current='true')");

// if(mysqli_num_rows($find_followed_class_snowglobes) > 0){
?>

<!-- <div class="box notice2">
<h3>School Classes</h3>         -->

<?php                    
  /*    $get_school_regs = mysqli_fetch_assoc($find_followed_class_snowglobes);
      while($regs_dt = mysqli_fetch_assoc($get_school_regs)){
      merge($regs,preg_replace("#^([0-9]+)#","$1",$regs_dt['granted_by']));
      }
      $regs = preg_replace("#[^,0-9]#","",json_encode($regs)); //so tacky
      var_dump($regs);                     */
     //if(){
?>


<?php// }   ?>  

</div>  

<?php// }
//else{ ?>

<?php// echo $nx['56']; ?>

<?php //} 
endif;                                             
// echo $nx['29'];    // posting tips


$find_users_to_chat_with = mysqli_query($db_main, "SELECT a.towhom,a.granted_by FROM sg_permissions a, sg_permissions b WHERE a.towhom = b.granted_by AND a.granted_by = b.towhom AND a.access_type='friend snowglobe' AND b.access_type='friend snowglobe' AND a.towhom != a.granted_by AND b.granted_by !=b.towhom AND a.towhom = '$_MONITORED[login_q]' ORDER BY a.date_g DESC LIMIT 0,25");

$groupchat_list = mysqli_query($db_main, "SELECT * FROM sg_permissions sgp LEFT JOIN snowglobes sgl ON sgp.granted_by = sgl.sg_url WHERE sg_url != '' AND sgp.towhom ='$_MONITORED[login_q]' LIMIT 0,25");

while($fetch_cha = mysqli_fetch_assoc($find_users_to_chat_with)){  //   $fetch_chat['granted_by'] has the user whom you could chat with
    $fetch_chat[$fetch_cha['granted_by']] = $fetch_cha;
}  
while($gcsl = mysqli_fetch_assoc($groupchat_list)){ 
    $gc_sl[] = $gcsl;
}   

//get list of tabbed users      

if($logged_dt['tabbed_users'] !== "null"){          
   

foreach(json_decode($logged_dt['tabbed_users']) as $num => $value){  
    $tab_list[$value] = true;
if(preg_match("#^[0-9]+$#",$value)){
    $gc_spec_search = mysqli_query($db_main,"SELECT * FROM sg_permissions sgp LEFT JOIN snowglobes sgl ON sgp.granted_by = sgl.sg_url WHERE sg_id='$value'");
    $sgp_dt2 = mysqli_fetch_assoc($gc_spec_search);
    $header = $sgp_dt2['sg_name'];
}
else{
    $header = $nx['40'] . $value;
}

echo "<div class='box chat-b' ref='" . $value . "' preloaded='true'>
    <h3>". $header . "</h3> <div class='cb_wrap'>
    <div class='chat_box'>
        <div class='loading_text'>$nx[60]</div>
    </div></div>
    <div class='chat_panel'>
        <textarea></textarea>
    </div>
    <form ref='". $value ."'><input type='file' name='file_upload' class='upload inline2' title='' rel='chat'><a class='prompt button_samp rad chat-buttons greened placeholder image_upload_btn' href='upload-chat-img'>&nbsp;</a></form>
    <a class='prompt button_samp rad chat-buttons greened' href='submit-chat-msg'>SUBMIT</a>   <a class='prompt button_samp rad chat-buttons' href='close-chat'>Close</a>
</div>";

}
}

echo "<div class='box clb' id='chat_list'><h3>Online Users</h3><p>$nx[66]</p>";
echo "<div class='cl_wrap'><div class='cl'>";
if(mysqli_num_rows($find_users_to_chat_with) > 0){
    
    foreach($fetch_chat as $key => $value){
        $check_for_selectd = isset($tab_list[$key]) ? " selectd" : "";
    ?>  
        <a href="chat-with-user:<?php echo $fetch_chat[$key]['granted_by']; ?>" class="prompt chat-option<?php echo $check_for_selectd; ?>"><?php echo $fetch_chat[$key]['granted_by']; ?></a>
    <?php
    }
 

    
}
echo "</div></div>";
    if(mysqli_num_rows($find_users_to_chat_with) > 5){
    ?>
        <input class="flick clf" value="Search for user..." id="user_search">
    <?php
    }

echo '</div>';

echo "<div class='box clb' id='gc_list'><h3>Groupchats</h3>";
echo "<div class='cl_wrap'><div class='cl'>";
if(mysqli_num_rows($groupchat_list) > 0){   
        for($i = 0;$i <= count($gc_sl) - 1; $i++){    
        $check_for_selectd = isset($tab_list[$gc_sl[$i]['sg_id']]) ? " selectd" : "";  
        ?>    
            <a href="chat-with-group:<?php echo $gc_sl[$i]['sg_id']; ?>" class="prompt chat-option<?php echo $check_for_selectd; ?>"><?php echo $gc_sl[$i]['sg_name']; ?></a>
        <?php
        }
    }  
     echo "</div>";
     echo"</div>";
if(mysqli_num_rows($groupchat_list) > 5){
 ?>
        <input class="flick clf" value="Search for groupchat..." id="gc_search">
    <?php              
}       echo "<div class='right_align'><a href='new_groupchat' class='prompt new_gc_button'>$nx[create_gc_txt]</a></div>";
        echo "<div id='gc_msg' class='contentbox hide'></div>";

echo "</div>";  
}



}
else{
    if(isset($_GET['thread_view']) && is_array($thread_data)){
        $sidebar = ($thread_data['forwhom'] === "self") ? 
        "SELECT * FROM userz WHERE username='$thread_data[bywhom]'" :
        "SELECT * FROM snowglobes WHERE sg_url='$thread_data[forwhom]'";
        $sidebar = mysqli_query($db_main,$sidebar);
        $sidebar_dt = mysqli_fetch_assoc($sidebar);
        if($thread_data['forwhom'] === "self"){
            $fill_data->about_poster_sidebar($sidebar_dt);
        }
        else{
            $fill_data->sg_sidebar($sidebar_dt,true);
        }
    }
} 


//if(preg_match()){}\

      echo "<div class='box space1'></div>";
echo "</td><td width='99%' id='vc2'>";        




//edit profile action, and all the other actions for query= [ ]
if(isset($_GET['query']) && logged_in_check){   
    require_once("core/edit_profile.php");     
}

require_once("core/idx_1.php");

if(index_page_check){
    echo "<span id='nf_encapsulated'>";
    require_once("core/news_feed.php");
    echo "</span>";
}

  
          
//main body
// .rightside gives extra tweaks, and is the right side of the sub-table, not the main dividing table. That's #vc2
//#left1 is the main left side
//when making a tooltip that is based on the internal tooltip function as opposed to just getting a title, make sure to have the title attribute with a value of " " on the object that's getting it 
//direct has all the main functions
//$_COOKIE['limbo'] has the welcome message


if(isset($_COOKIE['limbooo'][1])){
echo "<div class='contentbox'><h3>". $_COOKIE['limbooo'][0] ."</h3><p>". $_COOKIE['limbooo'][1] ."</p></div>";

setcookie("glimpse","asd",time()-1);
}




//i'm gonna need all these for later reference
//main $_GET cases = direct, profile (user profile), find(search for posts)
//query is the search data for any database search on a post or a user. probably gonna need more than one in a lot of cases 
//data you get under usedservices under find should be only for the user's owner to see
//each profile has their own snowglobe, which is their own profile page essentially, but I don't want to call it a profile page. It's too cliche. With the snowglobe, it's going to be less about you(not that it ought to be), but rather it's more about your world. Which I would think is actually a greater representation of yourself than something called a "profile". While "profiles" are just mere summations, your snowglobe is a manifestation of yourself for crying out loud. Yeah
// verify, signup are all under direct
//i'm probably gonna need to create a snowglobe page editor. Shouldn't be too bad, just a ton of dragging and ajax
//for now i'm gonna make the default snowglobe

require_once("core/actions.php");

require_once("core/redirects.php");

echo "</td></tr>";
echo "</table>";   

// scripts that belong at the bottom of the page?

require_once("core/extra_bottom_content.php");          
?>