<?php                  

$db_main = mysqli_connect("localhost","root","","captiv8");   

mysqli_set_charset($db_main,"ISO-8859-1");
date_default_timezone_set('UTC');         
$allow_redirs = true;



if(!isset($_GET['get_more'])){ //news_feed.php needs it

    if(!isset($_GET['verify']) && !isset($loc)){     
        $_SESSION['temp_n'] = substr(sha1(md5(base64_encode(sqrt(microtime(true))))),0,25); 
        // microtime(true)
        //FIXED finally wooo
    }
                                                                                               
    
}

require_once("auxiliary.php");       
define('dflt_date_f',"F j, Y, g:i A");
define('index_page_check',(preg_match("#index.php([\072]([0-9]){0,15})*$#", $swiss_army->extraurl()) && !isset($_SERVER['REDIRECT_URL'])));
define('news_feed_check',(index_page_check || isset($_GET['snowglobe']) || isset($_GET['get_more'])));
define('logged_in_check',isset($_SESSION['login_q']));

require_once(__DIR__."/vars.php"); 

//update post count
$get_all_thread_data = $db_checks->quick_return("posts","cnttype","'1'","array");
//print_r($get_all_thread_data);
for($i = 0; $i < sizeof($get_all_thread_data); $i++ ){    /* update post count lol
  $num_rows = $db_checks->quick_return("posts","thread_id",$get_all_thread_data[$i]['postid'],"row_num");
   echo $get_all_thread_data[$i]["postid"] . " - " .$num_rows . "<br>";
   $update_post_count = mysqli_query($db_main, "UPDATE posts SET num_replies = '$num_rows' WHERE postid='".$get_all_thread_data[$i]['postid']."'");   
   if(!$update_post_count){  echo mysqli_error($db_main);    } */   
}

if(!isset($_GET['get_more'])){
    if(count($_GET) > 0){                          
        $lean_machines->key_associations("thread_view,comment"); 
        $lean_machines->key_associations("phase"); 
        $lean_machines->key_associations("query,notifs,find,direct,verify"); 
        $lean_machines->key_associations("snowglobe");        
    }                                  
    if(isset($_GET['snowglobe'])){
        $sg_data[] = mysqli_query($db_main,"SELECT * FROM snowglobes WHERE sg_url='$_FILTERED[snowglobe]' 
        AND (sg_privacy='public' OR EXISTS(SELECT * FROM sg_permissions WHERE granted_by='$_FILTERED[snowglobe]' AND access_type != 'blocked'))");
        $sg_data[] = mysqli_query($db_main, "SELECT * FROM posts WHERE forwhom='$_FILTERED[snowglobe]' ORDER BY stamptime DESC");  
        //get snowglobe data, check for validation if it's private, then topics
        echo mysqli_error($db_main);
        if((!$sg_data[0] && !$sg_data[1]) || ($sg_data[0] && mysqli_num_rows($sg_data[0]) < 1)){
            $lean_machines->redir_process("Location:".main_dir."index.php");
        }
        else{
            $sg_details = mysqli_fetch_assoc($sg_data[0]);

            switch($sg_details['special_settings']){  
                case "school": $p_type_extra = 1;  break;
            }
            if(isset($p_type_extra)){
                $save = $p_type_extra;
                $p_type_extra = " OR for_which_sg='".$p_type_extra."'";
            }
            else{
                $p_type_extra = "";
                $save = "1"; //2lazy2sortthisout
            }
            $get_post_types = mysqli_query($db_main, "SELECT * FROM post_types WHERE 
            (
            for_which_sg = '0' 
            OR for_which_sg = '$sg_details[sg_url]'$p_type_extra
            ) 
            ORDER BY 
            CASE WHEN for_which_sg = '0' THEN 1 ELSE 0 END,
            CASE WHEN for_which_sg = '$save' THEN 1 ELSE 0 END,
            CASE WHEN for_which_sg = '$sg_details[sg_url]' THEN 1 ELSE 0 END,
            call_name ASC");
            while($p_types = mysqli_fetch_assoc($get_post_types)){
                $ptype_list[] = $p_types;
            } 
            //default post types are included first, then post types for your snowglobe category, then post types for your snowglobe
            //the later mentioned, the more priority it has
            //then it's ordered by call name in ascending order
        }
    }
    if(isset($_GET['query'])){
        $edu_find = mysqli_query($db_main, "SELECT * FROM education e, school s WHERE e.school_id = s.s_id AND e.forwhom='$_FILTERED[query]'");
    }                                                                                          
    if(isset($_GET['profile'])){
        $username = $_FILTERED['profile'];
        $profile_query = mysqli_query($db_main, "SELECT * FROM userz WHERE username='$username'"); $matched = mysqli_fetch_assoc($profile_query);
        if(!$profile_query || ($profile_query && mysqli_num_rows($profile_query) == 0)){
            $lean_machines->redir_process("Location:".main_dir."index.php"); 
            $_SESSION['error' .rand(56,1515)] = $swiss_army->extraurl();
            setcookie("limbooo[0]","profile_error");
        }
    }
    $full_url = [$_SERVER['PHP_SELF'],preg_replace("#(.+)[/][^/]+$#","$1",$_SERVER['PHP_SELF'])];   
    if(!isset($_SESSION['db_query'])){
        foreach($_SESSION as $nkey => $nvalue){
            if(preg_match("#^(free[_]sess[_])#", $nkey)){ unset($_SESSION[$nkey]); }
        }
    }  
    if(logged_in_check){                   
        mysqli_query($db_main, "UPDATE userz SET last_active_at=now() WHERE username='$_MONITORED[login_q]'");    
        //check for admin/mod privileges on first login
        if(!isset($_SESSION['admin_privy'])){
            $admin_search = mysqli_query($db_main, "SELECT * FROM admin_rights WHERE username = '$_MONITORED[login_q]'");   
            if(mysqli_num_rows($admin_search) === 1){
                $admin_search = mysqli_fetch_assoc($admin_search);
                
                //update admin rights on the database
                $_SESSION['admin_privy']['hash'] = hash('sha512',md5($_MONITORED['login_q'] . $_MONITORED['salt_q']) . hash('sha256',$logged_dt['salt'] . $logged_dt['login_att_last']));
                $_SESSION['admin_privy']['when'] = microtime(true);
                $_SESSION['admin_privy']['type'] = $admin_search['misc_info'];
                
                $update_db = mysqli_query($db_main, "UPDATE admin_rights SET ar_hash = '".$_SESSION['admin_privy']['hash']."',when_logged = '".$_SESSION['admin_privy']['when']."' WHERE username = '$_MONITORED[login_q]'");
                
            }
            else{
                $_SESSION['admin_privy']['type'] = "none";
            }       
        }
        else {
            if($_SESSION['admin_privy']['type'] !== "none"){      
                switch($db_checks->admin_rights($_SESSION['login_q'])){
                    case "Fully Confirmed":
                        //nothing. maybe something later lol
                    break;
                    case "Password Needed":
                        //Make some mechanism for the currently logged user to fill the password  
                    break;
                }
            }
        }
        //anything (data needed to be received, data needed to be updated, etc.) when a user's logged in

        if(index_page_check){
            $edu_select = mysqli_query($db_main, "SELECT * FROM education WHERE forwhom='$_MONITORED[login_q]';");
            $default_pts = mysqli_query($db_main, "SELECT * FROM post_types WHERE for_which_sg='0'") ;
        }
        $swiss_army->clear_array($_SESSION,"highschool|college"); //school, etc fillout clear on refresh
      //  if(!isset($_SESSION['admin_hash']))
    }
    if(isset($_GET['thread_view'])){
        $_TRIM = hack_free($_GET['thread_view']);
        preg_match("#^(.{3,50})_([A-Za-z0-9]{10})$#",$_TRIM,$_SPLIT);
        foreach($_SPLIT as $key => $value){
            $_NOG[$key] = mysqli_real_escape_string($db_main, $value);
        }                                                
        $view_thread = mysqli_query($db_main, "SELECT * FROM posts WHERE thread_nick = '$_NOG[1]' AND topic_hash = '$_NOG[2]'");
        $thread_data = mysqli_fetch_assoc($view_thread) ?: "";
        //topic_hash doesn't necessarily make it a topic
        if(isset($_GET['comment'])){
            $specific = mysqli_query($db_main, "SELECT * FROM posts WHERE topic_hash='". hack_free($_GET['comment']) ."' 
            AND (parent='$thread_data[postid]' OR thread_id='$thread_data[postid]')");
        }
    }   
}
if(isset($_GET['snowglobe'],$_SESSION['saved_pt_views']) || (isset($_GET['get_more']) && $_GET['get_more'] == "special")){
    foreach($_SESSION['saved_pt_views'] as $sg_id => $useless_value){
        if(count($_SESSION['saved_pt_views'][$sg_id]) == 0){
            unset($_SESSION['saved_pt_views'][$sg_id]);
        }
    }
}

if(preg_match("#index.php([\072]([0-9]){0,15})?$#",$swiss_army->extraurl()) && !isset($_SESSION["login_q"])) $_SESSION['welcome']="a";
if(!preg_match("#index.php((([\072])+([0-9]){0,15})*)$#",$swiss_army->extraurl()) || isset($_SESSION["login_q"])){
    if(isset($_SESSION['welcome'])){
        unset($_SESSION['welcome']);
    }
}
unset($_SESSION['confirm_delete'],$_SESSION['reply_sync']['count'],$_SESSION['reply_sync']['incr']);
?>                                                                                    