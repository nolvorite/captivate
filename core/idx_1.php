<?php
//data display mostly
//profile view, thread view, snowglobe view, etc. belong here

if(logged_in_check && index_page_check){   //make a new post panel(for threads)
    //I want to make the format different from the snowglobe post panel because reasons
    $fill_data->pt_post_opt($default_pts);
    echo "<form method='POST' action='index.php?direct=new_post' id='post_k'>
    <span id='input_save'></span>
    <div class='extra_opts'><a href='add-poll' class='prompt' id='attach_poll_q'>".$nx[30]."</a></div><div id='main_new_post' class='contentbox'>"; 
    echo "<div class='sect_1'><div class='pseudo_form'><table><td id='pt_opts'></td><td><input type='text' maxlength='150' value='".$nx['17']."' class='flick largeform' name='tcha1' id='title_trigger'></td></tr></table></div>
    <textarea name='tcha2' class='flick largeform' id='post_content'>".$nx['18']."</textarea></div>";  
//post as: formats

//image uploads


//title and content
echo "<div class='sect_2 button_row'>
<input type='file' name='file_upload' class='upload inline2' title='' rel='thread'>
<div class='placeholder cute_button'>Upload Image[s]</div>
<span class='drop user_opts'><div class='uplink rad'>".$nx['19']."</div><div class='dropdown_content' id='snowglobe_opts'>

<div class='spacer rad'>
";

//check for all snowglobes they can make a thread in, of course being able to post in your own profile snowglobe is always your right, and it'll be called "1"
echo "<input type='checkbox' checked='checked' name='sg_1' snowglobe='".$nx['20']."'></div></div></span>";

echo "<input type='submit' value='".$nx['21']."'></div>";
echo "</div></form>";  


//end submission form


//later i'm going to add a message here if the user hasn't fully filled out their profile. 
//later...
//is now


 if(mysqli_num_rows($edu_select) < 1): ?>
 
 
 <a href="http://localhost/captivate/profile_nuise/<?php echo $_MONITORED["login_q"]; ?>" class="pawn"><div class="notice center"><?php echo $nx[42]; ?></div></a>





<?php endif;         }

if(isset($_GET['snowglobe'])): 
    if(!(isset($_GET['get_more']) && $_GET['get_more'] == "special") && !isset($_GET['sg_lp_id'])):
        unset($_SESSION['last_postid']);
    endif;
?>
<div class="header box" id="sg_header">
<table><tr><td width="1%" class="sg_logo"></td><td class="header_etc">
<h3><a href="<?php echo main_dir; ?>sg/<?php echo $sg_details['sg_url']; ?>"><?php echo $sg_details['sg_name']; ?></a></h3></td></tr></table>    
<!-- the snowglobe sidebar is back at nd2.php  -->
</div>



<?php      

$fill_data->pt_post_opt($ptype_list);                 
      
 echo "<div class='contentbox hide' id='content'>".$nx['31']."</div>";            
echo "<form action='".main_dir."index.php?direct=new_post' method='POST' id='post_k'>
<span id='input_save'></span>
<div class='extra_opts'><a href='add-poll' class='prompt' id='attach_poll_q'>".$nx[30]."</a></div><div id='main_new_post' class='contentbox'>"; 
echo "<div class='sect_1'><div class='pseudo_form'><table><td><input type='text' maxlength='150' value='".$nx['17']."' class='flick largeform' name='tcha1' id='title_trigger'></td></tr></table></div>
<textarea name='tcha2' class='flick largeform' id='post_content'>".$nx['18']."</textarea></div>";  
//post as: formats


//title and content
echo "<div class='sect_2 button_row'>";
   echo "<input type='file' name='file_upload' class='upload inline' title=''>
<div class='placeholder cute_button'>Upload Image[s]</div>";
//check for all snowglobes they can make a thread in, of course being able to post in your own profile snowglobe is always your right, and it'll be called "1"
echo "<input type='hidden' name='sg_".$sg_details['sg_url']."' value='on'>";
//as for the rest...

echo "<input type='submit' value='".$nx['21']."'></div>";
echo "</div></form>";  

// view posts

if(mysqli_num_rows($sg_data[1]) > 0){
$y = 0;
?>

<div id="p_type_selector">
    <span class="drop">
        <div class="uplink cute_button" name="all" id="pt_drop" sg_id="<?php echo $sg_details['sg_id'];?>">
            <span>Viewing:</span>
        </div>
        <div class="dropdown_content">
            <div class="spacer">
                <table class="z_type">
                <tr>              
                <td>                                         
                <a href="select_of_p_type" class="prompt<?php if(!isset($_SESSION['saved_pt_views']) || !isset($_SESSION['saved_pt_views'][$sg_details['sg_id']])): ?> selected<?php endif;  ?> pt_toggle all_posts_q" id="0">All Posts</a>
                <?php  
                //$m is the row increment, $n is the first row conditional set to count all posts as one entry, 
                //and if $o is recognized, then there's a new column
                $n = true; 
                foreach($ptype_list as $p_type): 
                    $m = (!isset($m) || (isset($m) && $m == 4)) ? 0 : $m + 1 ;
                    if(isset($o)) {
                        echo "<td>\n";
                    }    
                    ?>          
                    <a href="select_of_p_type" class="prompt<?php if(isset($_SESSION['saved_pt_views'][$sg_details['sg_id']][$p_type['pt_id']])): ?> selected<?php endif; ?> pt_toggle" id="<?php echo $p_type['pt_id'];?>"><span><?php echo $p_type['call_name']; ?></span>
                    <p><?php echo $p_type['description']; ?></p>                                 
                    </a>
                    <?php 
                    if(($m == 3 && isset($n)) || ($m == 4 && !isset($n)) ){  
                        echo "\n</td>"; 
                        $o = 0;  //set the ending </td>, or not
                        if(isset($n)){ unset($n); } 
                    }                         
                endforeach; 
                ?>
                <?php if(!isset($o)): ?> </td> <?php else: unset($o); endif; ?> 
                </tr>
                </table>
            </div>
        </div>
    </span>
</div>

<?php
echo "<span id='nf_encapsulated'>";
include("core/news_feed.php");
echo "</span>";
}
else{
?>
<?php echo $nx['49']; ?>
<?php
} endif;   //end $_GET['snowglobe'] conditional

if(isset($_GET['thread_view'])){
    //viewing threads
    //it'll be a combination of the topic's hash and it's thread nickname
    //should help with search engines
    //ergo
    if($view_thread && ($thread_data['cnttype'] === "1")){
    echo "<script type='text/javascript'>var thread_id=".$thread_data['postid']."</script>";     
    echo "<div class='contentbox' id='thread_main'><h3>".$thread_data['title']." 
    <small>by <a href='".main_dir."index.php?profile=".$thread_data['bywhom']."'>".$thread_data['bywhom']."</a> at 
    <span class='white'>". date(dflt_date_f, strtotime($thread_data['stamptime']))."</span></small></h3>";      //".$thread_data['']."
    if(!preg_match("#^[ ]{0,}$#",$thread_data['content'])){echo "<p>".$thread_data['content']."</p>";  }
        if($thread_data['image_embed'] !== "none"){
        $get_image = mysqli_query($db_main, "SELECT type FROM images WHERE url_hash='$thread_data[image_embed]'");
        $image_dt = mysqli_fetch_assoc($get_image);
        $image_type = $image_dt['type'];
        echo "<p><img src='".main_dir."images/".$thread_data['image_embed'].".$image_type'></p>";
    }
    echo "</div>";
    //reply-action to thread
    if(isset($_SESSION['login_q'])){                  //solution to our session hash failing to verify :/
        //maybe if I alias the submission page then it won't fail to verify somehow
        //echo "<div id='thread_reply' class='contentbox'><form action='".main_dir."index.php?direct=new_post&verify=".$_SESSION['temp_n']."' method='POST'>
        echo "<div id='thread_reply' class='contentbox'><form action='".main_dir."index.php?direct=new_post' method='POST'>
        <input type='hidden' value='".$thread_data['postid']."' name='parent_comment'>
        <input type='hidden' value='".$thread_data['postid']."' name='thread_id'>
        <textarea name='tcha2' class='flick largeform'>Comment on this thread...</textarea>   <input type='submit' value='POST REPLY'>
    </form></div>";  
    }      //get main threads only
    $snipe8 = mysqli_query($db_main, "SELECT * FROM posts WHERE parent = '$thread_data[postid]' ORDER BY stamptime DESC LIMIT 0,100");
    $queue = mysqli_num_rows($snipe8); 
    if($queue === "0"){
        echo "<div class='contentbox notice_msg'>There are no replies in this topic yet.</div>";
    }
    else{
    if(!isset($_GET['comment'])){
        while($comment_loop = mysqli_fetch_assoc($snipe8)){
            $sync_25 = mysqli_query($db_main, "SELECT * FROM posts WHERE parent='$comment_loop[parent]' 
            AND thread_id='$thread_data[postid]' ORDER BY stamptime DESC LIMIT 0,25");
            $fill_data->show_reply($comment_loop);
            //second and third and fourth level of comments and so on
            if(mysqli_num_rows($sync_25) > 0){//function $lean_machines->get_posts($pid_call,$tid_call = "1",$limit = "25",$chain = "5")
                $lean_machines->get_posts($comment_loop['postid'],$thread_data['postid']);
            }
        }
    }
    else{  
        while($comment_loop = mysqli_fetch_assoc($specific)){     
            $sync_25 = mysqli_query($db_main, "SELECT * FROM posts WHERE parent='$comment_loop[parent]' 
            AND thread_id='$thread_data[postid]' ORDER BY stamptime DESC LIMIT 0,25");     
            
            if($comment_loop['parent'] === $thread_data['postid']){ //1st ancestor generation
                $fill_data->show_reply($comment_loop,true);    
                //this is soooooo tacky
                if(mysqli_num_rows($sync_25) > 0){//function $lean_machines->get_posts($pid_call,$tid_call = "1",$limit = "25",$chain = "5")
                    $lean_machines->get_posts($comment_loop['postid'],$thread_data['postid']);
                }
                mysqli_free_result($sync_25);
            }
            else{     //all the replies past 1st        
                $_SESSION['reply_sync']['count'] = 5;   //initiate post count
                $_SESSION['reply_sync']['incr'] = 0;
                $lean_machines->show_parents($comment_loop['postid'],$comment_loop['parent'],$_SESSION['reply_sync']['count']);
                //this is soooooo tacky     
                echo "<div class='margin'>";               
                $fill_data->show_reply($comment_loop,true);     
                $lean_machines->get_posts($comment_loop['postid'],$thread_data['postid']);      
                echo "</div>";          
                if($_SESSION['reply_sync']['incr'] === 1) {unset($_SESSION['reply_sync']['incr']);echo "</div>";} 
                else{
                while(isset($_SESSION['reply_sync']['incr'])){   //close all the margins
                    //wtf, for some reason while becomes laggy when there's only one item in array when it's called
                    $_SESSION['reply_sync']['incr'] = $_SESSION['reply_sync']['incr'] - 1;
                    if($_SESSION['reply_sync']['incr'] === 1) unset($_SESSION['reply_sync']['incr']);
                    echo "</div>";
                }
                }              
            }
        }    
        mysqli_free_result($specific);
    }  
}
mysqli_free_result($snipe8);
mysqli_free_result($view_thread); }else{
$lean_machines->redir_process("Location:".main_dir."index.php"); $_SESSION['error'] = $swiss_army->extraurl();}
}else{
if(isset($_GET['comment'])){
$search_for_thread = mysqli_query($db_main, "SELECT * FROM posts WHERE topic_hash='$_FILTERED[comment]' ORDER BY stamptime DESC");
if(mysqli_num_rows($search_for_thread) > 0){
$post_data = mysqli_fetch_assoc($search_for_thread);                    
$thread_query = mysqli_query($db_main, "SELECT * FROM posts WHERE postid='$post_data[thread_id]'");
$thread_dt = mysqli_fetch_assoc($thread_query);
$lean_machines->redir_process("Location:thread/" . $thread_dt['thread_nick'] . "_" . $thread_dt['topic_hash'] . "/comment/" . $_GET['comment']);
}else{$lean_machines->redir_process("Location:".main_dir."index.php"); $_SESSION['error' .rand(56,1515)] = $swiss_army->extraurl();}
}
}


if(isset($_GET['profile'])){    //viewing a profile
if($profile_query){
echo "<div class='contentbox profile_main'><table><tr class='row_1'><td class='side_1' width='99%'><h3 class='viewing_profile'><span class='nix1'>". $matched['username'] ."</span></h3>";
if(isset($_SESSION['login_q'])){
echo " <span id='check_friend_status' class='flick side_links' ref1='".$_MONITORED['login_q']."' ref2='".$matched['username']."'>Loading friendship status...</span>";
}                                                                    

echo"</td><td class='side_2 a_row1' width='1%'><div class='last_active_note'>$nx[last_active_text]".strtolower($swiss_army->time_rounds($matched['last_active_at']))."</div></td></tr>";  //row 1

echo "<tr class='row_2'><td class='side_1' width='99%'><div class='auto_filler'>";
echo "<h3 class='content_q'> ".$matched['username']."'s posts </h3>";
$query_2 = mysqli_query($db_main, "SELECT * FROM posts WHERE bywhom='$matched[username]' AND (cnttype='1' OR cnttype='2') ORDER BY stamptime DESC LIMIT 0,36");
if(mysqli_num_rows($query_2) < 1){
echo "<h3 class='notice2'>". $nx['22'] ."</h3>";
}else{  //has posts



while($post_loop = mysqli_fetch_assoc($query_2)){ //echo "<br>".$post_loop['cnttype']."</br>";
if(isset($shade)){
$shade = ($shade === " gloss") ? "" : " gloss";
}else{
$shade = "";
}  
if($post_loop['cnttype'] == "1"){         //threads

$post_type ="thread";
if($post_loop['num_replies'] === 1){    
$msg_format = $post_loop['num_replies'] . " REPLY TO THIS THREAD";   }
if($post_loop['num_replies'] < 1){
$msg_format = "NO REPLIES";   }
if($post_loop['num_replies'] > 1){
$msg_format =  $post_loop['num_replies'] . " REPLIES TO THIS THREAD";   }
$post_loop['content'] = (preg_match("#^.{50,}$#",$post_loop['content'])) ? preg_replace("#^(.){50}(.){1,}$#","$1",$post_loop['content']) : $post_loop['content'];
$post_loop['content'] = (preg_match("#^[ ]{0,}$#",$post_loop['content'])) ? "" : "<p>".$post_loop['content']."</p>";  


echo "<div class='posts_t1 ".$post_type."_post".$shade."'><h4>
<a href='".main_dir."thread/".$post_loop['thread_nick']."_".$post_loop['topic_hash']."'>".$post_loop['title']."</a> 
<span>Posted ".date(dflt_date_f, strtotime($post_loop['stamptime']))."</span>
<span class='reply_notch rad'>".$msg_format."</span></h4>".$post_loop['content']."</div>";          //".$post_loop['']."

}
if($post_loop['cnttype'] == "2"){  $post_type = "comment";
$snipe8 = mysqli_query($db_main, "SELECT * FROM posts WHERE postid = '$post_loop[parent]'");
$snipe9 = mysqli_fetch_assoc($snipe8);    //find parent of comment
$snipe10 = mysqli_query($db_main, "SELECT * FROM posts WHERE postid = '$snipe9[parent]'");
$snipe11 = mysqli_fetch_assoc($snipe10);   //find parent of parent comment   
$snipe12 = mysqli_query($db_main, "SELECT * FROM posts WHERE postid = '$post_loop[thread_id]'");
$snipe13 = mysqli_fetch_assoc($snipe12);   //find original thread     


//first, find the comment ancestor's post info
//check to see if its parent is the thread

$post_tree_msg = ($snipe9['parent'] !== $post_loop['thread_id']) ? array("to thread",$snipe13['topic_hash'],$snipe13['thread_nick'],$snipe13['title']) : array("in a comment under",$snipe13['topic_hash'],$snipe13['thread_nick'],$snipe13['title']);         //check if parent of current comment is thread id

//I really need to be more logistical about this

echo "<div class='posts_t1 ".$post_type."_post".$shade."'><h4>Replying ".$post_tree_msg[0]." 
<a href='".main_dir."thread/".$post_tree_msg[2]."_".$post_tree_msg[1]."/comment/".$post_loop['topic_hash']."'>".$post_tree_msg[3]."</a> 
<span>Posted ".$swiss_army->time_rounds($post_loop['stamptime'])."</span></h4><p>".$post_loop['content']."</p></div>";         

mysqli_free_result($snipe8);   mysqli_free_result($snipe10);   mysqli_free_result($snipe12);
}

}


}         //<dt></dt><dd></dd>
echo "</div></td>";                                                        
echo "<td class='side_info'>  <div class='spacer'>



".$nx[16]. date(dflt_date_f, strtotime($matched['joindate'])) . " 
<h3>Personal Information</h3>
              <blockquote>
<dt>Full Name</dt><dd>".$matched['fullname']."</dd>
</blockquote>

<h3>Q & A</h3>";

echo" 
</div>
</td></tr>";

echo "</table></div>";

mysqli_free_result($query_2);
if(isset($snipe_7)){mysqli_free_result($snipe7);    }

}else{$lean_machines->redir_process("Location:".main_dir."index.php"); $_SESSION['error' .rand(56,1515)] = $swiss_army->extraurl();}
mysqli_free_result($profile_query);
}

//show posts from others in the news feed
?>