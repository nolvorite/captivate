<?php
// AJAX calls page
// this is the hotline center, bitchessssss
// made that comment months ago, I just gotta say: what an obscure metaphor.
// I should get appropriate headers here to prevent DDOS's later, because this seems like the page to abuse for them lol
session_start();

date_default_timezone_set('UTC');

$db_main = mysqli_connect("localhost", "root", "", "captiv8");

require_once("vars.php");
require_once("auxiliary.php");    

if (isset($_GET['availability'])) {
    $filter = hack_free($_GET['availability']);
     $mns = mysqli_query($db_main, "SELECT username FROM userz WHERE username='$filter'");
     if (mysqli_num_rows($mns) > 0) {
        echo "taken";
         } 
    mysqli_free_result($mns);
     } 

if (isset($_SESSION['login_q'])) { // Inside this conditional are all the server requests from a page that requires a user to be logged in
    
    if ($swiss_army -> compare_dz($logged_dt['salt'], $_SESSION['salt_q'])) { // extra measures
        // probably unnecessary, but maybe not
        if (isset($_GET['action'])) {
            
            switch ($_GET['action']) {
            case "alter_post":
                 if (isset($_GET['typeof_alter'])) {
                    if ($db_checks -> check_perms_of_post("altering_posts", $_FILTERED['postid'])) {
                        $dt_assoc = $db_checks -> check_perms_of_post("altering_posts", $_FILTERED['postid'], "post_data");
                         switch ($_GET['typeof_alter']) {
                        case "edit":
                             $edit_post = mysqli_query($db_main, "UPDATE posts SET content='$_DATA[dt]' WHERE postid='$_FILTERED[postid]'");
                             if ($edit_post) {
                                echo json_encode(["status" => "success", "message" => $nx['67'], "html" => $_DATA['dt']]);
                                 // i'll set up the post formatting later
                            } 
                            else {
                                echo json_encode(["status" => "fail", "message" => $nx['68']]);
                                 } 
                            break;
                         case "delete":
                             // make them confirm first
                            if (isset($_SESSION['confirm_delete'][$_FILTERED['postid']])) {
                                $post_search = $db_checks -> quick_return("posts", "postid", $_FILTERED['postid'], "array", true);
                                 $get_post_rights = $db_checks -> get_alter_rights($post_search, "delete");
                                 // update post count of threads for non-thread post deletions
                                // var_dump($get_post_rights);
                                // var_dump($post_search,$get_post_rights);
                                if ($get_post_rights) {
                                    $reduce_to_skeleton = mysqli_query($db_main, "DELETE FROM posts                     WHERE topic_hash='$post_search[topic_hash]'");
                                     $update_post_count = mysqli_query($db_main, "UPDATE posts SET num_replies = num_replies - 1 WHERE postid='$post_search[thread_id]'");
                                     if ($reduce_to_skeleton) {
                                        echo $nx['69'];
                                         } 
                                } 
                                
                                } 
                            else {
                                $_SESSION['confirm_delete'][$_FILTERED['postid']] = true;
                                 echo $nx['68'];
                                 } 
                            break;
                             } 
                        } else {
                        echo "Hah.";
                         } 
                    } 
                break;
             case "sync_all":
                 $sync_in =
                 [
                 [mysqli_query($db_main, "SELECT * FROM notifications WHERE towhom='$_MONITORED[login_q]' AND status='0' ORDER BY stamptime DESC"), "notifs"]
                 ,
                 [mysqli_query($db_main, "SELECT * FROM posts WHERE (forwhom='$_MONITORED[login_q]' AND is_read='0') OR (bywhom='$_MONITORED[login_q]' AND viewed_by_poster='false') AND cnttype='3'"), "chat"]
                 ,
                 ];
                
                 for($i = 0; $i <= count($sync_in) -1;$i++) {
                    $shill = [$sync_in[$i][1] => mysqli_num_rows($sync_in[$i][0])];
                     $swiss_army -> merge($new_unreads, $shill);
                    
                     if (mysqli_num_rows($sync_in[$i][0]) > 0) {
                        mysqli_free_result($sync_in[$i][0]);
                         } 
                    } 
                
                
                echo json_encode($new_unreads);
                
                 unset($new_unreads);
                
                
                 break;
             case "user_gc_search":
                 //cg_type,gc_name,user_search
                 //but first get the list of users
                 
                 $username_search = mysqli_query($db_main, "SELECT b.granted_by,c.profile_pic_url,c.last_active_at,c.username,d.type FROM sg_permissions a, sg_permissions b LEFT JOIN userz c ON c.username=b.granted_by LEFT JOIN images d ON c.profile_pic_url=d.url_hash WHERE a.towhom = b.granted_by AND a.granted_by = b.towhom AND a.access_type='friend snowglobe' AND b.access_type='friend snowglobe' AND a.towhom != a.granted_by AND b.granted_by !=b.towhom AND b.towhom = '$_MONITORED[login_q]' AND b.granted_by LIKE '$_FILTERED[user_query]%' ORDER BY a.date_g DESC LIMIT 0,25");
                 if(mysqli_num_rows($username_search) >= 1){ //connection does exist
                     while($list = mysqli_fetch_assoc($username_search)){
                         $profile_pic = ($list['profile_pic_url'] === "none") ? image_dir."propic_mid.png" : main_dir."images/".$list['profile_pic_url'] . "." . $list['type'];
                         echo "<a href='select_user' class='prompt' name='$list[username]'><div class='user'><h4>$list[username]</h4><table><tr><td width='1%'><img src='$profile_pic' alt='propic'></td><td class='misc'>$nx[last_active_text] ".$swiss_army->time_rounds($list['last_active_at']) ."</td></tr></table></div></a>";
                     }                                                                                   
                 }
             break;
             case "new_groupchat":
                 echo "<table><tr><th><h3>$nx[create_gc_txt]</h3></th><td>
                 <p><input name='gc_name' class='largeform flick' value='$nx[gc_name]' /> </p>  </td></tr>
                 <tr><th><h4>$nx[users_to_invite]</h4></th><td><p>
                 <input name='user_search' class='largeform flick' value='$nx[u_search]' id='u_search'>

                 </p></td></tr>
                 
                 <tr><td colspan='2'><div id='gc_u_list'>$nx[loading_text]</div></td></tr>
                 <tr><th><h4>Type of groupchat</h4></th><td><div class='t_option'><input type='radio' name='gc_type' value='public' checked='checked'> Public</div><div class='t_option'><input type='radio' name='gc_type' value='private' selected='selected'> Private</div></td></tr>
                 
                 <tr id='submit_row'><td colspan='2'><a href='finish_gc' class='prompt button_samp'>$nx[finish_gc]</a><a href='cancel_gc' class='prompt button_samp'>$nx[cancel_gc]</a></td></tr>
                 </table>          
                 
                          
                 ";
             break;
             case "submit_new_gc":
                 //check to see that all users are compatible
                 //then create a fake snowglobe as a snowglobe data to be a prototype for the groupchat's properties
                 //then create snowglobe permissions, set creator as root admin and others as users in the group
                 //then create chat history. well maybe not
                 //and that's all lol
                 
                 var_dump($_POST,$_SPIN);
                 
                 for($i = 0; $i <= count($_SPIN['values']) - 1; $i++){
                     if(!$db_checks->chat_rights_check($_MONITORED['login_q'],$_SPIN['values'][$i])){
                         $break_loop = true;
                         break;
                     }
                 }
                 if(!isset($break_loop) && ($_SPIN['type'] === "public" || $_SPIN['type'] === "private")){
                     //and it begins
                     
                     //create fake snowglobe
                     //data columns needed to be filled out: snowglobe name, root_admin_id, special_settings == groupchat
                     //sg_privacy will be set too
                     //default value of access_type is gonna be snowglobe_follow
                     
                     //it needs its own unique snowglobe URL soo
                     function true_sg_name(){ global $db_checks;
                         $hash = substr("a". hash("sha256",microtime(true)),0,30);
                         if($db_checks->quick_return("snowglobes","sg_url","'$hash'","num_rows") === 1){
                             true_sg_name();
                         }
                         else{return $hash;}
                     }
                     $new_hash = true_sg_name();
                     $create_snowglobe = mysqli_query($db_main, "INSERT INTO snowglobes(sg_name,sg_url,root_admin_id,special_settings,sg_privacy) VALUES('$_SPIN[name]','$new_hash','$logged_dt[userid]','groupchat','$_SPIN[type]')");
                     if($create_snowglobe){   $progress[] = "created fake snowglobe - " . microtime(true);
                         for($i = 0; $i <= count($_SPIN['values']) - 1; $i++){
                             $create_sgp = mysqli_query($db_main, "INSERT INTO sg_permissions(towhom,granted_by) VALUES('".$_SPIN['values'][$i]."','$new_hash')") or die(mysqli_error($db_main));
                         }   
                         $admin_sgp = mysqli_query($db_main, "INSERT INTO sg_permissions(access_type,towhom,granted_by) VALUES('root admin','$_MONITORED[login_q]','$new_hash')") or die(mysqli_error($db_main));        
                         if($admin_sgp){ //done!
                             $progress[] = "created all snowglobe permissions - " . microtime(true);  
                             $sg_info = $db_checks->quick_return("snowglobes","sg_url","'$new_hash'","array",true);
                             $json_msg = ["new_gc_id" => $sg_info['sg_id'],"note" => "success","alert" => $nx['created_sg_msg']];
                         }      
                     }     
                 }else{
                     if(isset($break_loop)){
                         $msg = isset($msg) ? $msg . "\n " .$nx['gc_errormsg_1'] : $nx['gc_errormsg_1'];
                     }
                     if(!($_SPIN['type'] === "public" || $_SPIN['type'] === "private")){
                         $msg = isset($msg) ? $msg . "\n" . $nx['gc_errormsg_2'] : $nx['gc_errormsg_2'];
                     } 
                     $json_msg = ["note" => $msg];
                 }
                 echo json_encode($json_msg);
             break;
             case "sg_scha":
                
                 if (isset($_GET['sg'])) {
                    $sg_call_and_pass = [mysqli_query($db_main, "SELECT * FROM snowglobes WHERE sg_name='$_FILTERED[sg]'"),
                     mysqli_query($db_main, "SELECT * FROM sg_permissions WHERE granted_by='$_FILTERED[sg]' AND towhom='$_MONITORED[login_q]'")];
                    
                     if ($sg_call_and_pass[0] && $sg_call_and_pass[1] && mysqli_num_rows($sg_call_and_pass[0]) > 0) {
                        // if a user's banned from posting, then he'll have "none" on his posting rights
                        // if a user unsubscribes and he has no posting rights, we're not going to delete that data field, but rather just have it as "unsubscribed"
                        if (mysqli_num_rows($sg_call_and_pass[1]) > 0) {
                            // you've done something in this snowglobe before, or got banned from it somehow lol
                            $sgp_data_parse = mysqli_fetch_assoc($sg_call_and_pass[1]);
                             if ($sgp_data_parse['access_type'] !== "blocked") {
                                if ($sgp_data_parse['posting_rights'] == "none") { // check if they're banned
                                    // then check if they're subscribed or unsubscribed
                                    if ($sgp_data_parse['access_type'] !== "snowglobe follow") {
                                        $update_sg = [mysqli_query($db_main, "UPDATE sg_permissions SET access_type='snowglobe follow' 
                        WHERE granted_by='$_FILTERED[sg]' AND towhom='$_MONITORED[login_q]'"), "followed and banned"];
                                         } 
                                    else {
                                        $update_sg = [mysqli_query($db_main, "UPDATE sg_permissions SET access_type='unfollowed but banned' 
                        WHERE granted_by='$_FILTERED[sg]' AND towhom='$_MONITORED[login_q]'"), "unfollowed and banned"];
                                         } 
                                    } 
                                else {
                                    $update_sg = [mysqli_query($db_main, "DELETE * FROM sg_permissions WHERE granted_by='$_FILTERED[sg]' 
                    AND towhom='$_MONITORED[login_q]'"), "unfollowed normally"];
                                     } 
                                } 
                            } 
                        else { // first time following/subcribing/etc
                            $update_sg = [mysqli_query($db_main, "INSERT INTO sg_permissions(access_type,towhom,date_g,granted_by,posting_rights) 
            VALUES('snowglobe follow','$_MONITORED[login_q]',now(),'$_FILTERED[sg]','both')"), "newly subscribed"];
                             } 
                        if (!$update_sg[0]) { // SQL error somehow
                            echo mysqli_error($db_main);
                             } 
                        switch ($update_sg[1]) {
                        case "followed and banned":
                             echo "Followed! Unfortunately, you have no posting rights.";
                             break;
                         case "unfollowed and banned":
                         case "unfollowed normally":
                             echo "Unfollowed.";
                             break;
                         case "newly subscribed":
                             echo "Successfully followed.";
                             break;
                             } 
                        } 
                    } 
                break;
             // {"action":"sg_view_save","pt_id":$(this).attr("id"),"number_type":$(this).parent().parent().attr("id")},
            // <a href="select_of_p_type" class="promptif(isset($_SESSION['saved_pt_views'][$sg_details['sg_id']][$p_type['pt_id']])):  pt_toggle" id=" echo $p_type['pt_id'];"><span>echo $p_type['call_name']; </span>
            case "sg_view_save":
                 $_GET['sg_id'] = intval($_GET['sg_id']);
                 $_GET['pt_id'] = intval($_GET['pt_id']);
                 if (!isset($_SESSION['saved_pt_views'][$_GET['sg_id']][$_GET['pt_id']]) && $_GET['pt_id'] !== 0) {
                    $_SESSION['saved_pt_views'][$_GET['sg_id']][$_GET['pt_id']] = true;
                     echo "in!";
                     } 
                else {
                    if ($_GET['pt_id'] !== 0) {
                        unset($_SESSION['saved_pt_views'][$_GET['sg_id']][$_GET['pt_id']]);
                         echo "out!";
                         } 
                    else {
                        unset($_SESSION['saved_pt_views'][$_GET['sg_id']]);
                         echo "back to default!";
                         } 
                    } 
                break;
            
             case "get_sg_follow_status":
                 // make a new column under sg_permissions, and it'll define posting rights. It's going to be none, topics, posts, or both with default being both and none for people who are banned from posting.
                if (isset($_GET['name'])) {
                    $sg_caps = mysqli_query($db_main, "SELECT * FROM snowglobes WHERE sg_url='$_FILTERED[name]'");
                     $sg_parse = mysqli_fetch_assoc($sg_caps);
                     if (mysqli_num_rows($sg_caps) > 0) { // check if snowglobe exists and you can in fact follow it
                        // private snowglobes
                        $sg_perm_check = mysqli_query($db_main, "SELECT * FROM sg_permissions WHERE granted_by = '$_FILTERED[name]' AND towhom='$_MONITORED[login_q]'");
                         if (mysqli_num_rows($sg_perm_check) > 0) { // then check if you've already been either banned or already followed/a mod/owner/etc
                            // if you're a mod/admin, you automatically have the snowglobe followed.
                            $spc_data = mysqli_fetch_assoc($sg_perm_check);
                             if ($spc_data['access_type'] !== "blocked") { // admins/mods lose their rights if they click the unfollow button when they're currently mods for that site
                                echo "<a href='sg_scha' class='prompt button_samp rad' sg='" . $_FILTERED['name'] . "'>Unfollow</a>";
                                 } 
                            } else {
                            
                            if ($sg_parse['sg_privacy'] == "public") {
                                echo "<a href='sg_scha' class='prompt button_samp rad greened' sg='" . $_FILTERED['name'] . "'>Follow</a>";
                                 } 
                            } 
                        
                        } 
                    } 
                break; //end snowglobe follow status
            
            
             case "sql_q": // first, test to check if it has a delete or drop statement
                echo "<br>";
                 if (!preg_match("#^[;]?[ ]{0,}(DELETE|DROP|delete|drop) #", $_POST['sql_q'])) {
                    
                    if (preg_match("#^[ ]{0,}(SELECT|select)#", $_POST['sql_q'])) {
                        
                        $_POST['sql_q'] = (preg_match("#LIMIT[ ]+[0-9]+([,][ ]{0,}[0-9]+)?[ ]{0,}[;]?[ ]{0,}$#", $_POST['sql_q'])) ? $_POST['sql_q'] : preg_replace("#^(.+)([;]?)[ ]{0,}$#", "$1 LIMIT 0,100$2", $_POST['sql_q']) ;
                         $type = "row fetch";
                         } 
                    
                    @$test_query = mysqli_query($db_main, $_POST['sql_q']);
                     if ($test_query) { // successful SQL query?
                        if (!preg_match("#^[ ]{0,}(SELECT|select) #", $_POST['sql_q'])) {
                            echo "The query <span class='sql_q'>" . htmlspecialchars($_POST['sql_q']) . "</span> was successful.";
                             } else { // display rows!
                            // $get_rows = mysqli_fetch_assoc($test_query);
                            /**
                             * foreach($get_rows as $keys => $values){
                             * $rows = isset($rows) ? array_merge([$keys],$rows) : [$keys];
                             * }
                             */
                            
                            if (mysqli_num_rows($test_query) == 0) {
                                echo "No rows returned. Your SQL syntax is valid, but maybe your search criteria is wrong.";
                                 } 
                            
                            if (mysqli_num_rows($test_query) !== null && mysqli_num_rows($test_query) > 0) {
                                echo "<table id='results' class='no_breaks'>";
                                
                                 while ($get_data = mysqli_fetch_assoc($test_query)) {
                                    $get_rows[] = $get_data;
                                     foreach($get_data as $keys => $values) {
                                        $key_dump = (isset($key_dump) && array_search($keys, $key_dump) === false) ? array_merge($key_dump, [$keys]) : [$keys]; //all possible keys
                                         } 
                                    } 
                                echo "<tr>";
                                 echo "<th>#</th>";
                                 foreach($key_dump as $field_names) {
                                    echo "<th>" . $field_names . "</th>";
                                     } 
                                echo "</tr>";
                                 foreach($get_rows as $get_rows2) {
                                    $x = isset($x) ? $x + 1 : 1;
                                     $y = (isset($y) && $y < 2) ? $y + 1 : 1;
                                     echo "<tr>";
                                     echo "<th>" . $x . "</th>";
                                     foreach($get_rows2 as $keys => $values) {
                                        $z = (isset($z) && $z < 2) ? $z + 1 : 1; //let's get stylish
                                         $values = (strlen($values) > 250) ? preg_replace("#^(.+){247}(.+)$#", "$1...", $values) : $values;
                                         echo "<td class='a$y$z'>" . $values . "</td>";
                                         } 
                                    unset($z);
                                     echo "</tr>";
                                    
                                     } 
                                unset($y);
                                 echo "</table><br>";
                                 unset($x);
                                 unset($key_dump);
                                 unset($get_rows);
                                 mysqli_free_result($test_query);
                                 } 
                            } 
                        } else {
                        echo !preg_match("#^[ ]{0,}$#", $_POST['sql_q']) ? "<div class='admin_notice'><strong>SQL error:</strong> " . mysqli_error($db_main) . " </div>" : "You left the SQL query empty. Please input something.";
                         } 
                    
                    } 
                break;
            
             case "sg_url_avail":
                 $sg_url_search = mysqli_query($db_main, "SELECT * FROM snowglobes WHERE sg_url='$_FILTERED[test]'");
                 if (mysqli_num_rows($sg_url_search) > 0) {
                    echo "<div class='notice'>Unfortunately, this URL is not available.</div>";
                     } else {
                    echo "<div class='notice green'>This URL is available</div>";
                     } 
                break;
            
            
             case "css_edit":
                
                
                 if (preg_match("#[.]css$#", $_POST['file'])) { // nice try kids
                    
                    $_POST['file'] = preg_replace("#^" . main_dir . "(core[/])?(.+)$#", "$2", $_POST['file']);
                    
                     $file_opener = fopen($_POST['file'], "w+");
                     // convert the file strings back
                    // $_POST['data'] = preg_replace("#template[\057]#","",$_POST['data']);
                    // no longer a need for above as i've replaced the trim with the full url
                    $z = fwrite($file_opener, $_POST['data']);
                    
                     if ($z) {
                        echo json_encode(array("notice" => "success", "bash" => "two"), JSON_UNESCAPED_SLASHES);
                         } 
                    
                    } 
                
                
                break;
            
             case "admin_notes":
                
                 $_POST["admin_notes"] = htmlspecialchars(mysqli_real_escape_string($db_main, $_POST["admin_notes"]));
                
                 $send_sql = mysqli_query($db_main, "UPDATE `internal_settings` SET `value` = '$_POST[admin_notes]' WHERE `internal_settings`.`int_s_id` = 1");
                
                 if ($send_sql) {
                    echo json_encode(array("notice" => "success"), JSON_UNESCAPED_SLASHES);
                     } 
                else {
                    echo mysqli_error();
                     } 
                
                break;
            
            
            
             // admin panel stuff
            case "drag_box":
                 if (isset($_GET['screen_max'])) {
                    if (isset($_SESSION['admin_panel']['xpos'])) {
                        if ((intval($_GET['screen_max'][0]) - $_GET['screen_max'][2] < $_SESSION['admin_panel']['xpos']) || (intval($_GET['screen_max'][1]) - $_GET['screen_max'][3] < $_SESSION['admin_panel']['ypos'])) {
                            unset($_SESSION['admin_panel']['xpos'], $_SESSION['admin_panel']['ypos']);
                             } 
                        } 
                    } 
                if (isset($_GET['offsets'])) {
                    $_SESSION['admin_panel']['xpos'] = intval($_GET['offsets'][0]);
                     $_SESSION['admin_panel']['ypos'] = intval($_GET['offsets'][1]);
                     } 
                
                
                break;
            
             case "change_panels":
                 unset($_SESSION['admin_panel']['current_view']);
                
                 if ($_GET['view'] !== "v_sess") {
                    $_SESSION['admin_panel']['current_view'] = $_GET['view'];
                     } 
                
                // no idea why I had so much code there previously
                break;
             case "generate_password":
                 /*		$nick = hash( 'sha512', $mn['salt'] . $_SPIN['pwrdnorm'] ); //our hash mix shoulde be more complicated than this
																$gravy = substr( $nick, 0, 40 ); //make a nice meal */
                $salt = substr(sha1(microtime(true).microtime(true)),0,20);
                $password_plaintext = substr($salt,0,15);
                $salad = substr(hash("sha512", $salt . $password_plaintext),0,100);
                echo "<tr class='res_list'><th>Salt</th><td>$salt</td></tr>";
                echo "<tr class='res_list'><th>Password Plaintext</th><td>$password_plaintext</td></tr>";
                echo "<tr class='res_list'><th>Salad</th><td>$salad</td></tr>";
                break;
             case "admin_opts":
                 if (isset($_GET['req'])) {
                    switch ($_GET['req']) {
                    case "minimize":
                         // unsetting and setting sessions with ternary operators... kinda tacky?
                        // lol doesn't work
                        if (isset($_SESSION['admin_panel']['minimized'])) {
                            
                            unset($_SESSION['admin_panel']['minimized']);
                            
                             } else {
                            
                            $_SESSION['admin_panel']['minimized'] = "set";
                            
                             } 
                        
                        break;
                         } 
                    } 
                break;
            
             // $.post("core/simcheck.php",{"action":"submit-chat-msg","towhom":$(this).parent().parent().attr("ref"),"message":$(this).parent().find(".chat_panel textarea").val()},function(message){
            case "submit-chat-msg":
            if(isset($_GET['debug']) && isset($_GET['user'])){
                        $_SPIN['towhom'] = $_GET['user'];
                        $_SPIN['message'] = "lorem ipsum dolor, bla bla bla";
                    }
                 if (!preg_match("#^[ \n\t\h]{0,}$#", $_SPIN["message"])) {
                    
                    
                    if ($swiss_army -> chat_perms_check($_MONITORED['login_q'], $_SPIN['towhom'])) {
                        $val_checks = (preg_match("#^[0-9]+$#",$_SPIN['towhom'])) ? 1 : 0;
                        // if (isset($_SESSION[''])) {
                        //}
                        
                         
                         if($val_checks === 0){
                             $recipient_info = $db_checks -> quick_return("userz", "username", "'" . $_SPIN['towhom'] . "'", "array", true); //get recipient data
                             
                             $chcheck = mysqli_query($db_main, "SELECT * FROM chat_history WHERE ch_unique_i = '$recipient_info[userid]_$logged_dt[userid]' OR ch_unique_i ='$logged_dt[userid]_$recipient_info[userid]'");
                             $chuniquei = mysqli_num_rows($chcheck) === 1 ? mysqli_fetch_assoc($chcheck) : "$recipient_info[userid]_$logged_dt[userid]";
                             $has_no_chat_history = (gettype($chuniquei) === "string") ? true : false;
                             $ch_unique_i = is_array($chuniquei) ? $chuniquei['ch_unique_i'] : $chuniquei;
                             $user_update = preg_match("#^$recipient_info[userid]_#",$ch_unique_i) ? "user_2_last" : "user_1_last";                
                         }
                         else {
                             $original_sg = $db_checks->quick_return("[RAW]","SELECT * FROM sg_permissions sgp INNER JOIN snowglobes sg ON sgp.granted_by = sg.sg_url WHERE sg.sg_id='$_SPIN[towhom]' AND forwhom='$_MONITORED[login_q]'","array"); 
                             
                             $recipient_search = mysqli_query($db_main, "SELECT * FROM sg_permissions sgp LEFT JOIN snowglobes sgl ON sgp.granted_by = sgl.sg_url WHERE sgp_id='$_SPIN[towhom]'");
                             $recipient_info = mysqli_fetch_assoc($recipient_search);
                             $ch_unique_i = "$".$recipient_info['sgp_id'] . "_" . $logged_dt['userid']; //unique identifier lol     
                             $has_no_chat_history = ($db_checks->quick_return("chat_history","ch_unique_i","'$ch_unique_i'","row_num") === 0) ? true : false;
                             $user_update = "user_2_last";
                         }
                             $mark_as_seen = mysqli_query($db_main, "UPDATE posts SET viewed_by_poster='true' WHERE viewed_by_poster='false' AND cnttype='3' AND bywhom='$_MONITORED[login_q]' AND forwhom='$_SPIN[towhom]'"); //update the posts in limbo
                             $submit_post = mysqli_query($db_main, "INSERT INTO posts(content,cnttype,forwhom,bywhom,settings,is_read,stamptime) VALUES('$_SPIN[message]','3','$_SPIN[towhom]','$_MONITORED[login_q]','2','$val_checks',CURRENT_TIMESTAMP)") or die(mysqli_error($db_main)); //i'll use the settings column somehow for something in the future lol, probably privacy-related
                             $update_append = ",$user_update = NOW()";
                         //groupchats will have one chat history per person, while conversations between two people will both have a unique one 
                         $update_ch = ($has_no_chat_history) ? mysqli_query($db_main, "INSERT INTO chat_history(forwhom,bywhom,ch_ts,ch_unique_i,$user_update)  VALUES('$_SPIN[towhom]','$_MONITORED[login_q]',NOW(),'$ch_unique_i',NOW()) ") or die(mysqli_error($db_main)) : mysqli_query($db_main, "UPDATE chat_history SET ch_ts = NOW()$update_append WHERE ch_unique_i = '$ch_unique_i'") or die(mysqli_error($db_main));
                         
                      
                        
                         
                         // $delete_prev_record = mysqli_query($db_main, "DELETE FROM chat_history WHERE bywhom='$_MONITORED[login_q]' AND forwhom='$_SPIN[towhom]'");
                        // $submit_ch_history =
                        echo (!$update_ch) ? mysqli_error($db_main) : "Successfully posted!<br>";
                        var_dump("INSERT INTO posts(content,cnttype,forwhom,bywhom,settings,is_read,stamptime) VALUES('$_SPIN[message]','3','$_SPIN[towhom]','$_MONITORED[login_q]','2','$val_checks',CURRENT_TIMESTAMP)",$ch_unique_i);   
                         }  
                    // mysqli_free_result( $chat_perms_check );
                }    
             break;            
             case "school_search":
                
                 $select_school = mysqli_query($db_main, "SELECT * FROM school WHERE name LIKE '%$_FILTERED[search_q]%' AND school_type = '$_FILTERED[criteria]' ORDER BY name ASC LIMIT 0,5");
                
                 if ($select_school && mysqli_num_rows($select_school) > 0) {
                    echo "<div id='school_search'>";
                    
                     while ($properties = mysqli_fetch_assoc($select_school)) {
                        
                        
                        ?>
            <div class="school_shell">
<a href="confirm_school" class="school_box rad prompt name_only" name="<?php echo $properties['name'] ?>"><h3><?php echo $properties['name'] ?>      
                                                                                             <span>

<?php
                        
                        
                         echo (isset($properties['established'])) ? " &middot; Established " . $properties['established'] : "";
                         echo "</span></h3></a>";
                        
                        
                         } 
                    
                    echo "</div>";
                     mysqli_free_result($select_school);
                     } else {
                    
                    if (preg_match("#([-A-Za-z]+)[ ]#", $_FILTERED['search_q'])) {
                        
                        echo "<a href='" . main_dir . "profile_nuise/" . $_MONITORED['login_q'] . "/find/new_school' class='add-new rad'>" . $nx[33] . "</a>";
                        
                         } 
                    
                    } 
                
                
                
                break;
             case "check_list":    
                 if(isset($_GET['user'])){
                     $zen = ($_GET['check_list'] === "one") ? "a.granted_by" : "sgl.sg_name";
                     $blank_value_check = preg_match("#^[ ]{0,}$#",$_FILTERED['user']) ? "" : " AND $zen LIKE '%".$_FILTERED['user']."%'";
                     switch(true){   
                         case ($_GET['check_list'] === "one"): //user search
                           //  if(isset($_SESSION['cl_dt'][$_FILTERED['check_list']][$_FILTERED['user']])) continue;
                             $search_q = mysqli_query($db_main, "SELECT a.towhom,a.granted_by FROM sg_permissions a, sg_permissions b WHERE a.towhom = b.granted_by AND a.granted_by = b.towhom AND a.access_type='friend snowglobe' AND b.access_type='friend snowglobe' AND a.towhom != a.granted_by AND b.granted_by !=b.towhom AND a.towhom = '$_MONITORED[login_q]'$blank_value_check ORDER BY a.date_g DESC LIMIT 0,25");
                         break;
                         case ($_GET['check_list'] === "two"): //groupchat search
                         //    if(isset($_SESSION['cl_dt'][$_GET['check_list']][$_FILTERED['user']])) continue;
                             $search_q = mysqli_query($db_main, "SELECT * FROM sg_permissions sgp LEFT JOIN snowglobes sgl ON sgp.granted_by = sgl.sg_url WHERE sg_url != '' AND sgp.towhom ='$_MONITORED[login_q]'$blank_value_check LIMIT 0,25"); 
                         break;       
                         
                     }
                     if($_GET['check_list'] === "one" || $_GET['check_list'] === "two"){    
                         if(isset($_SESSION['cl_dt'][$_FILTERED['check_list']][$_FILTERED['user']])){
                             $cl_dt = $_SESSION['cl_dt'][$_FILTERED['check_list']][$_FILTERED['user']];
                         }
                         else{
                             while($search_l = mysqli_fetch_assoc($search_q)){
                                 $cl_dt[] = $search_l;
                             }
                             //$_SESSION['cl_dt'][$_FILTERED['check_list']][$_FILTERED['user']] = $cl_dt;
                         }
                     } 
                     echo json_encode(["content" => isset($cl_dt) ? $cl_dt : []]);
                 }
             break;
             case "submit_dt": // quick AJAX POST request submissions
                if (isset($_GET['dt'])) {
                    
                    $initial_validation_message = "Please correct the following information:";
                     $initial_search_test = "SELECT * FROM userz WHERE username='$_MONITORED[login_q]'"; //if there's no initial data checks needed. What a tacky loophole right      
                     $successful_entry_msg = "";
                     $redir = "none";
                    
                    /**
                     * quick documentation for this. eugh
                     * necessary parameters:
                     * $table_name: table name to search
                     * $data_fields: data fields for submission to the DB
                     * $field_values: respective field values for data fields
                     * $message and $type (on success): to differentiate on JSON output
                     * 
                     * optional parameters:
                     * $checks: each array value will be a sub-array containing the following field values based on index--created this
                     * for string comparisons/regex tests
                     * 1st: regular expression test for data name
                     * 2nd: message output on JSON if regex test fails
                     * 3rd: field name of checked input
                     * 4th (optional): if set, will compare with string input and if equivalent, will either 
                     * set it to the 5th value or set data on 3rd to be an empty string     
                     * 5th (optional): if 4th is true, then it will be set to this value instead of blank.
                     * $misc_sql_queries = SQL queries to be processed.
                     * structure:
                     * if it's just a string, then it'll be processed after the first SQL query is processed and succeeded. Being processed after the first SQL query's succes is default.
                     * if it's an array, then all strings inside it will be recognized and processed with the default method.
                     * all the sub-arrays' first string(which would be the SQL query) will be processed depending on the second string value 
                     * depending on which one they are below
                     * "before": will be processed before the first SQL query
                     * "before data checks": will be processed before data checks
                     * "after data checks": ...and after
                     * "after": default method
                     */
                    
                     // maybe later I should remove all these nasty--er, numerous variables with the properties/data and have it all be inputted into one class function, with constants from that same class returning the values to be processed
                    require_once("dt_list.php");
                    
                    
                    
                     // begin data submission process
                    if (isset($misc_sql_queries)) {
                        $misc_query_list = is_array($misc_sql_queries) ?
                         [
                         $swiss_army -> filter_sql_queries($misc_sql_queries, "before"),
                         $swiss_army -> filter_sql_queries($misc_sql_queries, "after")
                         ] :
                         $misc_sql_queries;
                         if (is_array($misc_sql_queries)) {
                            foreach($misc_query_list[0] as $query) {
                                $misc_q_exec = mysqli_query($db_main, $query);
                                 if (!$misc_q_exec) {
                                    $message .= "\n(SQL error: " . mysqli_error($db_main) . ")";
                                     } 
                                } 
                            } 
                        } //return a string if it's not an array, otherwise separate them into their orders
                    
                     $valid_search = mysqli_query($db_main, $initial_search_test);
                     if (mysqli_num_rows($valid_search) < 1) {
                        if (isset($checks)) {
                            foreach($checks as $testing_values) {
                                if ($testing_values[0] === 0) {
                                    $error_d = "c";
                                     } 
                                // convert it to empty string or if a 4th string in the array is set, then switch to that value if it was left untouched
                                // with placeholder as value back in client-side
                                if (isset($testing_values[3]) && ($testing_values[3] == $_DATA[$testing_values[2]])) {
                                    $_DATA[$testing_values[2]] = isset($testing_values[4]) ? $testing_values[4] : "";
                                     } 
                                } 
                            } 
                        // check for data validity
                        if (!isset($error_d)) {
                            $insert_in = mysqli_query($db_main, "INSERT INTO " . $table_name . "($data_fields) VALUES($field_values)");
                             if ($insert_in) {
                                if (isset($misc_sql_queries)) {
                                    // default place of miscellaneous SQL queries, therefore has strings if it's only one
                                    if (is_array($misc_sql_queries) && $misc_sql_queries[1]) {
                                        foreach($misc_sql_queries[1] as $query) {
                                            $misc_q_exec = mysqli_query($db_main, $misc_sql_queries);
                                             if (!$misc_q_exec) {
                                                $message .= "\n(SQL error: " . mysqli_error($db_main) . ")";
                                                 } 
                                            } 
                                        } 
                                    else {
                                        $misc_q_exec = mysqli_query($db_main, $misc_sql_queries);
                                         if (!$misc_q_exec) {
                                            $message .= "\n(SQL error: " . mysqli_error($db_main) . ")";
                                             } 
                                        } 
                                    } 
                                $message = $successful_entry_msg;
                                 $type = "success";
                                 } 
                            else {
                                $message = "SQL query failed--" . mysqli_error($db_main);
                                 $type = "fail";
                                 } 
                            } 
                        else {
                            $message = $initial_validation_message;
                             foreach($checks as $validations) {
                                if ($validations[0] !== 1) {
                                    $x = isset($x) ? $x + 1 : 1;
                                     $message .= "\n" . $x . ". " . $validations[1];
                                     } 
                                } 
                            unset($x, $error_d);
                             } 
                        } 
                    
                    echo @json_encode(["type" => $type, "message" => $message, "redir" => $redir], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                     } 
                break;
             case "get_msgs_dt":
                
                 // what kind of data do we need
                // snowglobe permissions
                // check current conversations, list by date, index chat convos by date internally in mySQL
                // separate personal convos from snowglobe convos
                // as far as settings go, disable priority messages by default for snowglobes but not for users
                // priority messages would ring your phone/alert you of new chat messages
                $get_chat_history = mysqli_query($db_main, "SELECT * FROM chat_history ch LEFT JOIN sg_permissions sgp ON sgp.sgp_id=ch.forwhom LEFT JOIN snowglobes sg ON sg.sg_url=sgp.granted_by WHERE (ch.bywhom ='$_MONITORED[login_q]' OR ch.forwhom='$_MONITORED[login_q]')  ") or die(mysqli_error($db_main));
                 while ($ch_sync = mysqli_fetch_assoc($get_chat_history)) {
                    $ch_sync['ch_ts'] = $swiss_army -> time_rounds($ch_sync['ch_ts']);
                     $ch_log[] = $ch_sync;
                     } 
                echo json_encode($ch_log);
                 break;
             case "chat_with_user":
                 // find whether they're able to actually chat with the user whom they're trying to engage with
                if (isset($_GET['user'])) { // no idea why I set this for a conditional, figured it would help with validations and such
                    // echo "heh";
                     if(isset($_GET['debug'])){
                        var_dump($swiss_army -> chat_perms_check($_MONITORED['login_q'], $_FILTERED['user'],true));
                     }
                     if ($swiss_army -> chat_perms_check($_MONITORED['login_q'], $_FILTERED['user'])){   
                        if(preg_match("#^[0-9]+$#",$_FILTERED['user'])){
                            $is_a_chat_group = true;   
                            
                        }
                        else{
                            $recipient_info = $db_checks->quick_return("userz","username","'".$_FILTERED['user']."'","array",true);
                        }
                        $additional_criteria = ["", "","UNION SELECT * FROM posts WHERE bywhom='$_FILTERED[user]' AND forwhom='$_MONITORED[login_q]' AND cnttype = '3'"];
 
                        if (isset($_GET['is_read'])) {
                            $additional_criteria[1] = $additional_criteria[1] . " AND is_read='0'";
                             } 
                        if (isset($_GET['poster_sync'])) {
                            $additional_critieria[0] = $additional_criteria[0] . "AND viewed_by_poster='false'";
                             } 
                        if (isset($_GET['new_only'])) {
                            $additional_criteria = [" AND is_read='0'", " AND viewed_by_poster='false'",$additional_criteria[2]];
                             }
                        if(isset($is_a_chat_group)){
                            $additional_criteria[2] = "UNION SELECT * FROM posts WHERE forwhom='$_FILTERED[user]' AND cnttype='3'";
                            $get_snowglobe_dt = mysqli_query($db_main, "SELECT * FROM sg_permissions sgp INNER JOIN snowglobes sg ON sgp.granted_by=sg.sg_url AND sg_id=$_FILTERED[user]");
                            $sg_dt = mysqli_fetch_assoc($get_snowglobe_dt);
                            $sg_name = $sg_dt['sg_name'];
                        }     //unset($_SESSION['last_of'][$_MONITORED['login_q'] . '_' . $_FILTERED['user']]);   
                      //  echo "SELECT * FROM posts WHERE bywhom='$_MONITORED[login_q]' AND forwhom='$_FILTERED[user]' AND cnttype = '3'$additional_criteria[1] $additional_criteria[2] $additional_criteria[0] ORDER BY stamptime DESC LIMIT 0,36";
                      
                       if(isset($_GET['debug'])){   //check actual criteria lol
                       echo "SELECT * FROM posts WHERE bywhom='$_MONITORED[login_q]' AND forwhom='$_FILTERED[user]' AND cnttype = '3'$additional_criteria[1] $additional_criteria[2] $additional_criteria[0] ORDER BY stamptime DESC LIMIT 0,36<br><br>";
                       } 
                       
                        $search_for_posts = mysqli_query($db_main, "SELECT * FROM posts WHERE bywhom='$_MONITORED[login_q]' AND forwhom='$_FILTERED[user]' AND cnttype = '3'$additional_criteria[1] $additional_criteria[2] $additional_criteria[0] ORDER BY stamptime DESC LIMIT 0,36");
                         while ($zen = mysqli_fetch_assoc($search_for_posts)){ // each chat post attribute
                            foreach($zen as $key => $value) {
                                // update the last post counter whenever there's a new post from the poster OR there's a new reply from the recipient 
                                if ($key === "image_embed" && $value !== "none") {
                                    
                                    $img_fmt = mysqli_query($db_main, "SELECT type,url_hash,dimensions FROM images WHERE url_hash ='" . hack_free($value) . "'");
                                    if (mysqli_num_rows($img_fmt) === 1) {
                                        $img_dt = mysqli_fetch_assoc($img_fmt);
                                         $dimensions = $img_dt['dimensions'];
                                         $zen['img_format'] = $img_dt['type'];
                                         $zen['dimensions'] = $img_dt['dimensions'];
                                        
                                         } 
                                    else {
                                        $zen[$key] = "none";
                                         } 
                                    } 
                                if ($key === "stamptime") {
                                    $zen[$key] = strtolower($swiss_army -> time_rounds($zen[$key]));
                                     } 
                                if ($zen[$key] === null) {
                                    unset($zen[$key]);
                                     } 
                                } 
                            $post_list[] = $zen;
                             } 
                        $no_posts_note = isset($is_a_chat_group) ? $nx['no_gc_msgs'] : $nx['41'];
                        $search_cha = (mysqli_num_rows($search_for_posts) > 0) ? array_reverse($post_list) : $no_posts_note;
                        if(isset($is_a_chat_group)){
                            
                        }
                        
                         echo json_encode(["message" => "success", "user" => $_FILTERED['user'], "messages" => $search_cha,"cg_name" => isset($is_a_chat_group) ? $sg_name : ""], JSON_UNESCAPED_SLASHES);
                         } 
                    
                    if (isset($_GET["box_action"])) {
                        switch ($_GET["box_action"]) {
                        
                        case "open":
                             chat_list_q("open", $_FILTERED['user']); //saves chat box to be open for reference to next page load(s)
                             break;
                        
                         case "close":
                             echo "ahh";
                             chat_list_q("close", $_FILTERED['user']); //deletes the other user's name from the array
                             break;
                             } 
                        } 
                    
                    } 
                if (isset($_GET['format'])) {
                    switch ($_GET['format']) {
                    case "sync_in":       
                         //for non-groupchat messages
                         $select_unreads = mysqli_query($db_main, "SELECT p.bywhom,p.postid FROM posts p WHERE ((p.forwhom='$_MONITORED[login_q]' AND p.is_read=0) OR (p.bywhom='$_MONITORED[login_q]' AND p.viewed_by_poster = 'false')) AND p.cnttype='3'") or die(mysqli_error($db_main)); //new posts that are in post limbo and about to be added to queue
                         //there is definitely a more efficient way to get that exact same search query but im sticking with that for now
                         if (mysqli_num_rows($select_unreads) > 0){                                       
                            $num = 0;
                             if(mysqli_num_rows($select_unreads) > 0){
                             while ($zx = mysqli_fetch_assoc($select_unreads)) {    
                             if(isset($_GET['debug'])){var_dump($zx);}
                                if ($zx['bywhom'] !== $_MONITORED['login_q']) {
                                    if(isset($list_of_users)){
                                        switch($zx['sgp_id']){
                                            case "": //two-person conversation
                                                $list_of_users = array_search($list_of_users,$zx['bywhom']) === false ? array_merge($zx['bywhom'],$list_of_users) : $list_of_users;
                                                $count[$zx['bywhom']] = (isset($count[$zx['bywhom']])) ? $count[$zx['bywhom']]++ : 1;
                                            break;
                                            default: //two or more
                                                $list_of_users = array_search($list_of_users,$zx['sgp_id']) === false ? array_merge($zx['bywhom'],$list_of_users) : $list_of_users;
                                                $count["a". $zx['sgp_id']] = (isset($count["a".$zx['sgp_id']])) ? $count["a".$zx['sgp_id']]++ : 1;
                                            break;
                                        }
                                    }
                                }
                            }                                 
                            }
                         }
                            //$new_replies_num = !isset($new_messages) ? [] : $new_messages;
                        echo json_encode(["new_messages" => mysqli_num_rows($select_unreads),
                             "senders" => (isset($list_of_users)) ? $list_of_users : "none",
                             "senders_ea" => isset($count) ? $count : "none"]);    
                        
                           
                         unset($new_messages);
                         unset($new_replies_num);                                                           
                        
                         break;
                        
                         } 
                    } 
                
               
                
                //   echo isset($_SESSION['last_of'][$_FILTERED['user']]);
                if (isset($_GET['new_only']) && isset($_GET['user'])) { 
                   $last_posted = "(SELECT postid FROM posts WHERE cnttype='3' AND (forwhom='$_FILTERED[user]' OR (bywhom='$_FILTERED[user]' AND is_read='0')) ORDER BY postid DESC LIMIT 1)"; 
                   $gc_update = mysqli_query($db_main, "UPDATE chat_history SET user_2_last=NOW(),user_2_last_post=$last_posted WHERE forwhom='$_FILTERED[user]'") or die(mysqli_error($db_main)); 
                   if(isset($_GET['debug'])){echo "updated timestamp on chat history";}    
                    if(!preg_match("#^[0-9]+$#",$_FILTERED['user'])){
                        $chcheck = mysqli_query($db_main,"SELECT * FROM chat_history WHERE ch_unique_i='".$recipient_info['userid']."_".$logged_dt['userid']."' OR ch_unique_i='".$recipient_info['userid']."_".$logged_dt['userid']."'") or die(mysqli_error($db_main));
                        $ch_check = mysqli_fetch_assoc($chcheck);
                        $ch_unique_i = $ch_check['ch_unique_i'];
                        $user_update = preg_match("#^".$recipient_info['userid']."_#",$ch_unique_i) ? "user_2_last" : "user_1_last";
                        $ch_update = mysqli_query($db_main, "UPDATE chat_history SET $user_update=NOW() AND ".$user_update."_post=$last_posted WHERE forwhom='$_FILTERED[user]'");
                    }
                    // set new_only whenever someone makes a post, or the recipient makes a post and the other person hasnt received it yet
                    $mark_as_read = mysqli_query($db_main, "UPDATE posts SET is_read = '1' WHERE cnttype='3' AND forwhom='$_MONITORED[login_q]' AND bywhom='$_FILTERED[user]'");
                    $mark_as_seen = mysqli_query($db_main, "UPDATE posts SET viewed_by_poster='true' WHERE viewed_by_poster='false' AND cnttype='3' AND bywhom='$_MONITORED[login_q]' AND forwhom='$_FILTERED[user]'");                     
                     } 
                
                unset($post_list);
                 break; //end chat search 
            
            
             case "wikipedia_search":
                 include("wikipedia_search.php");
                 break;
            
             case "confirm_school":
                 if (isset($_GET['data'])) {
                    $strings = json_decode($_GET['data'], true); //rawwwwww
                     } else {
                    $strings['name'] = $_GET['name'];
                     } 
                // we gotta run a bunch of tests to determine the authenticity of this data mine
                // check to see if the school's already in the database
                $check_in_sys = mysqli_query($db_main, "SELECT * FROM school WHERE name ='" . hack_free($strings['name']) . "'");
                
                 if (mysqli_num_rows($check_in_sys) < 1) {
                    
                    if (preg_match("#high[ ]school#", strtolower($strings['name']))) {
                        $school_level = "1";
                         } 
                    if (preg_match("#(school (of)? )?(med([^ ]+)|law)( school)?|university|college#", strtolower($strings['name']))) {
                        $school_level = "2";
                         } 
                    
                    if (!isset($school_level)) {
                        // if not named either high school or university in the school's name
                        // you're gonna have to reconfirm this list with a wikipedia entry
                        $recheck = file_get_html($strings['link']) -> find("#mw-content-text table.infobox", 0);
                         foreach($recheck -> find("tr") as $data_set) {
                            $data_screened = str_get_html($data_set -> innertext) -> find("th[!colspan],td[!colspan]");
                            
                            
                             foreach($data_screened as $sheet) {
                                if (preg_match("#^<th#", $sheet)) {
                                    $name = strtolower(preg_replace("#[^A-Za-z]#", "", $sheet -> plaintext));
                                     // criteria evaluation
                                } 
                                else {
                                    $value = $sheet -> plaintext;
                                     // i'm lazy so i'll do all the modifying for this later                      E
                                } 
                                
                                if (isset($name)) {
                                    $properties[$name] = isset($value) ? preg_replace("#([\133](.+)[\135][ ]+)$#", "", $value) : "";
                                     } 
                                
                                } 
                            } 
                        
                        if (preg_match("#[-]12$#", $properties['grades'])) {
                            $school_level = "1";
                             } 
                        
                        
                        $recheck -> clear();
                         } else {
                        $properties = $strings;
                         } 
                    if (isset($school_level)) {
                        switch ($school_level) {
                        case "1":
                             $properties['ed_level'] = "High School";
                             break;
                         case "2":
                             $properties['ed_level'] = "College";
                             break;
                             } 
                        
                        if (mysqli_num_rows($check_in_sys) < 1) {
                        
                        if (isset($strings['link']) && preg_match("#en[.]wikipedia[.]org#", $strings['link'])) {
                        // check to see the validity of the link
                        // now check whether it's a high school or a college
                        // finally, submit the school to the school list and show the education completion form
                        $properties['link'] = $strings['link']; //link for later reference  
                         $properties['name'] = $strings['name'];
                        
                         // March 7, 2015 edit: I'm going to limit the data queries to only the name, location, and number of students, and the kind of school that it is.
                        // I guess i'm going to start dating my comments now
                        // I originally intended the location to be the city name, but I haven't found a search method for cities that is consistent in all wikipedia entries
                        $school_data =
                         [
                         "name" => $properties['name'],
                         "location" => isset($properties['coordinates']) ? $properties['coordinates'] : '',
                         "enrollment" => $properties["numberofstudents"],
                         "school_type" => $properties["ed_level"],
                         "link" => $properties["link"]
                         ]; //all that work and it turns out we only need those five data values...
                        
                         array_map("sql_filter", $school_data);
                        
                         $school_data = mysqli_query($db_main, "INSERT INTO school(name,location,enrollment_no,school_type,link) VALUES('$school_data[name]','$school_data[location]','$school_data[enrollment]','$school_data[school_type]','$school_data[link]')") or die(mysqli_error($db_main));
                         if ($school_data) {
                        $fill_details = "yes";
                         } 
                    unset($school_level);
                     unset($adjoiner);
                     } 
                
                
                } 
            } 
        } else {
        $fill_details = "yes";
         $zen = mysqli_fetch_assoc($check_in_sys);
         foreach($zen as $key => $value) {
            if ($zen[$key] !== null) {
                $properties[$key] = $value;
                 } 
            } 
        } 
    
    
    $z = (isset($z)) ? $z + 1 : $z;
    
     $znip = "i";
     if (isset($fill_details) && !isset($_SESSION[$znip])):
         $ed_level = isset($properties['school_type']) ? $properties['school_type'] : $properties['ed_level'];
    
     $znip = preg_replace("#[^A-Za-z]#", "", strtolower($ed_level));
    
     ?>

<?php if (!isset($_SESSION[$znip])): ?>    
<form class="zenith">
<br>
<div class="contentbox fill_details"> <h3>Fill details</h3>        
<table><tr><th class="marginals" style="width:200px">School Name</th><td>
<input type="text" name="school" value="<?php echo $strings['name'] ?>" class="largeform <?php echo 'a' . $z;
     ?>" disabled="disabled" name="school_name" style="opacity:.5">          </td>
</tr>
<?php if ($properties['school_type'] == "College"): ?>
<tr><th>Degrees:</th><td><input type="text" name="degree_s" class="largeform flick <?php echo 'a' . $z;
     ?>" value="Degrees/Majors pursued (separate with a comma if it's more than one)"></td></tr> <?php endif;
     ?>
<tr><th>Started:</th><td><input type="text" name="year_s" class="largeform flick <?php echo 'a' . $z;
     ?>" value="Input the year that you started going to this school."></td></tr>
<tr><th>Finished:</th><td>
<select class="largeform <?php echo 'a' . $z;
     ?>"  name="current_status"><option class="current <?php echo 'a' . $z;
     ?>" value="current" selected="selected">Currently Attending</option>
<option value="did_finish" value="finished">I finished attending this school.</option></select>
<input type="text" class="largeform flick inline <?php echo 'a' . $z;
     ?>" value="Insert Year Here..." name="year_finished" disabled="disabled">  
 <input type="hidden" name="edu_type" class="a1" value="<?php echo $ed_level;
     ?>"> 
<input type="submit" value="Complete Details" class="prompt" id="<?php echo 'a' . $z;
     ?>">
</td></tr>
</table>
</div>
</form>  
<?php $_SESSION[$znip] = "yes";
     endif;
     endif; //end submission form                    
     unset($properties);
     unset($fill_details);
     unset($znip);
    
     break; //end case "confirm_school"              
 // for now let's set the maximum image size to 3 MB. I know kind of large, but i'll find a way to compress it somehow in the future
case "file_test": // image uploads
    // for now i'll just limit each new post to one image upload
    @$image_data = getimagesize($_FILES['file_upload']['tmp_name']);
    
     // if the file is an image, then instantiating a variable with getimagesize to call the file name turns it into an array, so yeah
    // no need to check for file extensions heeh
    $json_message = ["message" => "", "result" => ""];
     if (isset($_POST) && is_array($image_data) && is_uploaded_file($_FILES['file_upload']['tmp_name']) && !isset($_SESSION['posted_an_image'])) {
        
        $size = $_FILES['file_upload']['size'];
         $max_size = isset($max_size) ? $max_size : 3000000;
         $name_for_ref = preg_replace("#(.+)[.].+$#", "$1", $_FILES['file_upload']['name']);
        
         if ($size < $max_size) {
            $image_content = addslashes(file_get_contents($_FILES['file_upload']['tmp_name']));
             $file_type = preg_replace("#image/([a-z0-9]+)$#", "$1", $_FILES['file_upload']['type']);
            
             $hash = substr(hash('sha512', sqrt(microtime(true))), 0, 25);
             $check_duplicates = mysqli_query($db_main, "SELECT img_name,url_hash FROM images WHERE img_name='$name_for_ref' AND url_hash='$hash'");
            
             if (mysqli_num_rows($check_duplicates) > 0) {
                $hash_clone = strrev($hash_clone); //forgot why I did this lol
                 } 
            
            switch ($_POST['loc']) {
            case "chat":
                $check_availability = $db_checks -> chat_perms_check($_MONITORED['login_q'], $_SPIN['recipient']);
                if ($check_availability === true) {
                   $chat_img_upload = true;
                    } 
                
            break;
            } 
            
            $technically_uploading = mysqli_query($db_main, "INSERT INTO images(img_name,url_hash,image_blob,type,size,dimensions,uploaded_by,under_post) VALUES('$name_for_ref','$hash','$image_content','$file_type','$size','" . $image_data[0] . "x" . $image_data[1] . "','$_MONITORED[login_q]','0')");
            
            
            
             if ($technically_uploading) {
            
            if ($chat_img_upload === true) {
            
            $submit_post = mysqli_query($db_main, "INSERT INTO posts(content,cnttype,forwhom,bywhom,settings,is_read,image_embed,stamptime) VALUES(' ','3','$_SPIN[recipient]','$_MONITORED[login_q]','2','0','$hash',CURRENT_TIMESTAMP)");
             if (!$submit_post) {
            $critical = "3";
             $reason = mysqli_error($db_main);
             } 
        
        } 
    
    $json_message['message'] = "File successfully uploaded!";
     if (isset($critical)) {
        switch ($critical) {
        case "3":
             $json_message['message'] = $json_message['message'] . " Image upload failed. Reason: " . $reason;
             break;
             } 
        } 
    $json_message['result'] = "success";
     $json_message['hash'] = $hash;
     $json_message['file_type'] = $file_type;
    
     mysqli_query($db_main, "UPDATE userz SET image_in_limbo='" . microtime(true) . "' WHERE username='$_MONITORED[login_q]'");
     } else {
    echo mysqli_error($db_main);
     } 


} else {
$json_message['message'] = "File too large. Please use another file.";
 $json_message['result'] = "fail";
 } 

} else {
$json_message['message'] = "Only images are allowed to be uplaoded";
 $json_message['result'] = "fail";
 } 

echo json_encode($json_message);
 break;

 case "complete_details":

 foreach($_GET['data'] as $q_k => $q_v) {

$zing = preg_split("#:#", $q_v);

 for($i = 0;$i <= count($zing) - 1; $i++) {
$zing[$i] = preg_replace("#^[ ]+|[ ]+$#", "", $zing[$i]);
 } 

$parse[$zing[0]] = hack_free($zing[1]);

 } 

// test for validity
// school : Nettleton High School (Arkansas),year_s : 2012,current_status : did_finish,undefined : current,year_finished : 201
if (isset($parse['school']) && isset($parse['year_s']) && isset($parse['current_status']) && isset($parse['year_finished']) && isset($parse['edu_type'])) { // first


// check to see that they haven't registered their education for that school yet
$parse['school'] = $swiss_army -> sql_filter ($parse['school']);
 $x = "SELECT * FROM education e, school s WHERE s.name='$parse[school]' AND e.school_id = s.s_id AND e.forwhom='$_MONITORED[login_q]'";
 // only one education entry per school per person
$edu_search = mysqli_query($db_main, $x) or die(mysqli_error($db_main));
 if (mysqli_num_rows($edu_search) < 1) {

$school_confirm = mysqli_query($db_main, "SELECT * FROM school WHERE name='$parse[school]'");



 if (mysqli_num_rows($school_confirm) > 0) { // second
    // just to get some extra details
    $get_deets = mysqli_fetch_assoc($school_confirm);
    
     if ($parse['current_status'] == "current") {
        $parse['year_finished'] = 0;
         } 
    $opt_degree = ($parse['edu_type'] == "College") ? "degree," : "";
    
     $degrees = (isset($parse['degrees'])) ? "'" . hack_free($parse['degrees']) . "'," : "";
     $status = ($parse['current_status'] !== "did_finish") ? "true" : "false";
    
     $submit_results = mysqli_query($db_main, "INSERT INTO education(" . $opt_degree . "started,finished,is_current,forwhom,school_id) VALUES(" . $degrees . "'$parse[year_s]','$parse[year_finished]','$status','$_MONITORED[login_q]','$get_deets[s_id]')") or die(mysqli_error($db_main));
    
     if ($submit_results) {
        echo $nx['38'];
         } 
    
    } 

mysqli_free_result($school_confirm);
 } 
} 

// submit to education
unset($parse);

 break; //end case "complete_details"


 } //end $_GET['action'] switch loose comparison             

 } //end $_GET['action'] conditional         


 if (isset($_GET['fetch'])) { // display data

if (isset($_SESSION['login_q'])) {

// really need to make a way to classify an account as being "admin"
if ($logged_dt['userid'] === "1") {

switch ($_GET['fetch']) {
case "post_type_panel":
 $get_post_types = mysqli_query($db_main, "SELECT * FROM post_types ORDER BY pt_id DESC");
 ?>

<tr class="pt_dt"><th>Default Listing</th><td>

<?php if (mysqli_num_rows($get_post_types) > 0): ?>
<div class="auto-bound">
<?php
     while ($pt_dt = mysqli_fetch_assoc($get_post_types)):
     if ($pt_dt['for_which_sg'] == 0) {
        $pt_dt['for_which_sg'] = "All Snowglobes";
         } else {
        if ($pt_dt['for_which_sg'] == 1) {
            $pt_dt['for_which_sg'] = "School Snowglobes";
             } else {
            $pt_dt['for_which_sg'] = "<a href='" . main_dir . "sg/" . $pt_dt['for_which_sg'] . "'>A Snowglobe</a>";
             } 
        
        } 
    ?>

<table class="pah"><tr>
<th colspan="2"><?php echo $pt_dt['call_name'];
     ?></th>
</tr>
<tr>
<th>Description</th><td><?php echo $pt_dt['description'];
     ?></td> </tr><tr>
<th>Applies to</th><td><?php echo $pt_dt['for_which_sg'];
     ?></td>
</tr>
</table><br>

<?php endwhile;
     else: ?>

There are currently no post_types. Feel free to make one in the fill-in form below!   

</div>   
      
<?php endif;
     ?>

</td></tr>
<tr class="fill_in">
<th>Fill-In Form</th>
<td><input type="text" class="flick largeform" value="Name" name="pt_name">
<textarea name="pt_desc" class="flick largeform"><?php echo $nx['58'];
     ?></textarea>       

<input type="text" class="flick largeform" value="<?php echo $nx['57'];
     ?>" name="pt_sg_apply">
<button submit="new_thread_type" submit_call="pt_">Add New Post Type!</button>
</td>
</tr>

<?php
    
     break;
    
     } 

} 

switch ($_GET['fetch']) { // non-admin functions
case "comments":
 // just get first-level comments for this
$get_comments = mysqli_query($db_main, "SELECT * FROM posts WHERE thread_id='$_FILTERED[postid]' AND parent='$_FILTERED[postid]' 
        ORDER BY stamptime DESC LIMIT 0,5");
 if ($db_checks -> check_perms_of_post("viewing_posts", $_FILTERED['postid'])) {
    $post_panel = $nx['79'];
     if (mysqli_num_rows($get_comments) > 0) {
        echo $post_panel;
         echo "<h4>$nx[80]</h4>";
         echo "<div class='post_shill'>";
         while ($comment_dt = mysqli_fetch_assoc($get_comments)) {
            $alt_num = (isset($alt_num) && $alt_num === "4") ? "3" : "4";
             echo "<div class='shills a$alt_num'><a href='" . main_dir . "profile/$comment_dt[bywhom]' class='profile_link'>$comment_dt[bywhom]</a>
                    <p>$comment_dt[content] <span class='date_posted'> - <a href='" . main_dir . "index.php?comment=$comment_dt[topic_hash]'>" . $swiss_army -> time_rounds($comment_dt['stamptime']) . "</a></span></p></div>";
             } 
        echo "</div>";
         } 
    else {
        
        echo $nx['78'];
         echo $post_panel;
         } 
    } 
break;
 case "class_sg":
 if (isset($_GET['school_id'])):
     $name_narrow_down = isset($_GET['extra_name']) ? " AND sg_name LIKE '%".$_FILTERED['extra_name']."%'" : "";
 $search_all_sg = mysqli_query($db_main, "SELECT * FROM snowglobes WHERE special_settings='school' 
            AND reference_num='$_FILTERED[school_id]'$name_narrow_down LIMIT 0,25");
 if (mysqli_num_rows($search_all_sg) > 0):
     while ($c_sg_dt = mysqli_fetch_assoc($search_all_sg)):
     ?>
                <!--  <?php echo $c_sg_dt[''];
 ?>   -->
                <div class="sgc">
                <h4><a href="<?php echo main_dir;
 ?>sg/<?php echo $c_sg_dt['sg_url'];
 ?>"><?php echo $c_sg_dt['sg_name'];
 ?></a></h4>
                <div class="sg_desc"><?php echo $c_sg_dt['description'];
 ?></div> 
                <p>Created <?php echo $swiss_army -> time_rounds($c_sg_dt['creation_date']);
 ?></p> 
                </div>
<?php
 endwhile;
 else: ?>
<div class="notice"><?php echo $nx['75'];
 ?></div>
<?php
 endif;
 endif;
 break;
 case "postedit_q":
 $select_post = mysqli_query($db_main, "SELECT * FROM posts post_c,sg_permissions sgp_c WHERE post_c.postid='$_FILTERED[postid]' 
     AND CASE WHEN post_c.forwhom='self' THEN 1 ELSE CASE WHEN sgp_c.towhom=post_c.bywhom THEN 1 ELSE 0 END END  GROUP BY postid");
 // no idea why it's giving out duplicate results
// checked for both mod and user rights above
// check for admin, mod, and user rights
if ($logged_dt['userid'] === 1 || mysqli_num_rows($select_post) == 1) {
    $post_dt = mysqli_fetch_assoc($select_post);
     echo $post_dt['content'];
     } 
break;

 } 

} 
} 


if (isset($_GET['nm_time'])) {
switch ($_GET['nm_time']) {
case "notifs":
 $notifs_grab = mysqli_query($db_main, "SELECT * FROM notifications WHERE towhom='$_MONITORED[login_q]' ORDER BY stamptime DESC LIMIT 0,10");
 if (mysqli_num_rows($notifs_grab) > 0) {

// $("#notifications .spacer").html().append("<a href='"+ data.notifs[i]['url'] +"'><div class='notifs'>"+ data.notifs[i]['content'] +"</div></a>");
$x = -1;
 while ($notifs_zen = mysqli_fetch_assoc($notifs_grab)) {
    $x++;
     $pawn[$x] = $notifs_zen;
     $pawn[$x]['stamptime'] = $swiss_army -> time_rounds($pawn[$x]['stamptime']); //convert to time-rounds format
     } 


if (!isset($_GET['action'])) {
    
    $search_new = mysqli_query($db_main, "SELECT * FROM notifications WHERE status=0 AND towhom='$_MONITORED[login_q]' ORDER BY stamptime DESC LIMIT 0,10");
    
     echo json_encode(array("result" => "new", "unread" => mysqli_num_rows($search_new), "notifs" => $pawn), JSON_UNESCAPED_SLASHES);
     } 

else { // clear notifications
    // 10 at a time
    // but delay it
    // 1 is read, 0 is unread
    // delay it for the same amount of time as it would get refreshed, or maybe a little less
    switch ($_GET['action']) { // user profile actions
    
    case "clearnotifs":
         $clear_last_10 = mysqli_query($db_main, "UPDATE notifications SET status=1 WHERE towhom IN (SELECT towhom FROM(SELECT towhom FROM notifications WHERE towhom='$_MONITORED[login_q]' ORDER BY stamptime DESC LIMIT 0,10)tmp)");
        
         if ($clear_last_10) {
            
            $search_new = mysqli_query($db_main, "SELECT * FROM notifications WHERE towhom='$_MONITORED[login_q]' AND status=0 ORDER BY stamptime DESC LIMIT 0,10");
            
            
            
             echo json_encode(array("result" => "completed", "notifs_left" => mysqli_num_rows($search_new)), JSON_UNESCAPED_SLASHES);
            
             } 
        break;
        
        
         // we have to disable error displays here because timeout issues
        // if the school's name was never listed before in the education database, we have to send the data to the school table, but first have the user clear up details for himself
        // if a data name was never entered before as a column, alter the education table to make an entry for it
        // then set the user's education to the respective recently posted school(s)
        } 
    
    
    } 

mysqli_free_result($search_new);


/**
 * $.each(data.notifs,function(i,v){
 * $("#notifications .spacer").html().append("<a href='"+ data.notifs[i]['url'] +"'><div class='notifs'>"+ data.notifs[i]['content'] +"</div></a>");
 * });
 */

 } else {

$msgs = array("result" => "nonew");
 echo json_encode($msgs);

 } //end "notifs" switch conditional




 break;

 } //end switch statement


 } //end $nm_time conditional

 // and we're in
if (isset($_GET['friend_status'])) {
// who should we look up first
// oh right
// well it doesn't really matter
// all the friends actions
$check_2 = mysqli_query($db_main, "SELECT * FROM userz WHERE username='" . $_FILTERED['friend_status'][1] . "'");
 $check_3 = mysqli_fetch_assoc($check_2);
 // first check for null, then check for values
if ($logged_dt['userid'] !== $check_3['userid']) { // not the same account, duh
// check for snowglobe permissions
$sea_rchin = mysqli_query($db_main, "SELECT * FROM sg_permissions WHERE access_type='friend snowglobe' AND towhom='$_MONITORED[login_q]' AND granted_by='$check_3[username]'");
 if (mysqli_num_rows($sea_rchin) < 1) {
if (!isset($_GET['action'])) {
echo "<a href='add-friend' class='prompt button_samp rad'>Follow " . $check_3['username'] . "'s snowglobe</a>";
 } else {

// not friends, and decided to give a friend request, and requestee's snowglobe is private
// this seems too much like facebook. eugh
// I want to change it to something else
// More like a twitter/IG kinda thing. Technically i'm still copying, but still
// get snowglobe settings
$snowglobe_check[0] = mysqli_query($db_main, "SELECT * FROM sg_settings WHERE id='user_" . $check_3['userid'] . "'") or die($error);
 $snowglobe_check[1] = mysqli_fetch_assoc($snowglobe_check[0]);

 // a1_check is to see if people can follow/see the posted content of the snowglobe
if ($snowglobe_check[1]['a1_check'] == "all") { // public profile
// later i'll have an option where a user could have a person following him follow the person who followed first
unset($_SESSION['content_pool']);
 $ng = mysqli_query($db_main, "INSERT INTO sg_permissions(access_type,towhom,date_g,granted_by) VALUES('friend snowglobe','$_MONITORED[login_q]',now(),'$check_3[username]')");
 if ($ng) {
    echo "<a href='add-friend' class='prompt button_samp rad greened'>Followed.</a>";
     } 
} 

} 

} else {
echo "<a href='add-friend' class='prompt button_samp rad greened'>Followed.</a>";
 } 

} 
mysqli_free_result($check_2);
 } 

if (isset($_GET['post_que'])) {

if (isset($_GET['poll_vote'])) { // live polling
// get which id they voted on and check to see that it's a valid poll entry
$poll_settings = mysqli_query($db_main, "SELECT * FROM polls WHERE post_id_root=$_FILTERED[post_que] AND define_set != 'poll_choice'");
 while ($settings_dt = mysqli_fetch_assoc($poll_settings)) {
$p_nats[$settings_dt['define_set']] = $settings_dt['value'];
 } 
$get_poll = mysqli_query($db_main, "SELECT * FROM polls WHERE post_id_root=$_FILTERED[post_que] AND data_id=$_FILTERED[poll_vote] AND define_set='poll_choice'"); //more like, the poll vote data
 $poll_choice_zen = mysqli_fetch_assoc($get_poll);

 if ($p_nats['choice_selection'] == 0) { // check if you can vote on more than one option

if (mysqli_num_rows($get_poll) > 0) {
$poll_vote_check = mysqli_query($db_main, "SELECT * FROM pollvotes_q WHERE bywhom='$_MONITORED[login_q]' AND which_poll='$_FILTERED[post_que]'");



 // check if they've already voted
if (mysqli_num_rows($poll_vote_check) < 1) {
$poll_vote_q[1] = mysqli_query($db_main, "INSERT INTO pollvotes_q(bywhom,timeof,choice_id,which_poll) VALUES('$_MONITORED[login_q]',now(),'$_FILTERED[poll_vote]','$_FILTERED[post_que]')");
 $update = $poll_choice_zen['votes'] + 1;
 $poll_vote_q[2] = mysqli_query($db_main, "UPDATE polls SET votes='$update' WHERE data_id='$poll_choice_zen[data_id]'");
 if ($poll_vote_q[1] && $poll_vote_q[2]) {
    
    } else {
    echo $error;
     } 
} 
// or not
} 


} 
// show poll results
$poll_disp = mysqli_query($db_main, "SELECT * FROM polls WHERE post_id_root=$_FILTERED[post_que] AND define_set = 'poll_choice' ORDER BY data_id DESC");
 $i = 0;
 while ($pchoi_dt = mysqli_fetch_assoc($poll_disp)) {
$i++; //get total
 $total = ($i < 2) ? $pchoi_dt['votes'] : $pchoi_dt['votes'] + $total;
 $pdisp_dt[$i] = $pchoi_dt;
 } 
for($j = 1;$j <= count($pdisp_dt);$j++) {
$pct = ($pdisp_dt[$j]['votes'] / $total) * 100;
 echo "<div class='optionbox results'>" . $pdisp_dt[$j]['value'] . " (" . $pct . "%)</div>";
 } 

mysqli_free_result($poll_disp);
 mysqli_free_result($get_poll);
 mysqli_free_result($poll_vote_check);
 mysqli_free_result($poll_settings);

 } 

// votes
if (isset($_GET['vote_action'])) {
$vote = ($_GET['vote_action'] == "up") ? "1" : "0";



 // check to see if the user's already voted
$vote_q = mysqli_query($db_main, "SELECT * FROM votes_q WHERE bywhom='" . $_MONITORED['login_q'] . "' AND which_post='$_FILTERED[post_que]'");
 $post_check = mysqli_query($db_main, "SELECT * FROM posts WHERE postid=$_FILTERED[post_que]");
 $post_dt = mysqli_fetch_assoc($post_check);
 $residual = ($vote == "1") ? array($post_dt['upvotes'] + 1, $post_dt['downvotes']-1, 1) : array($post_dt['upvotes']-1, $post_dt['downvotes'] + 1, 0); //changed his mind, therefore upvotes should be 1 higher and downvotes 1 lower, and vice versa depending on how he voted.
 if (mysqli_num_rows($vote_q) > 0) {
$vote_dt = mysqli_fetch_assoc($vote_q);

 if ($vote_dt['vote'] == $vote) { // trying to unvote where you vote

$new_count = $post_dt['upvotes'] - 1;


 $vote_a = mysqli_multi_query($db_main, "DELETE FROM votes_q WHERE which_post='$_FILTERED[post_que]' AND bywhom='$_MONITORED[login_q]'; UPDATE posts SET upvotes ='$new_count' WHERE postid='$_FILTERED[post_que]'");

 if ($vote_a) {
$msgs = array("notice" => "Unvoted!");
 } 
} else { // since I disabled downvoting this next part is useless for now
/**
 * $vote_a = mysqli_query($db_main, "UPDATE votes_q SET timeof=now(),vote='$vote' WHERE which_post='$_FILTERED[post_que]';");
 * $vote_b = mysqli_query($db_main, "UPDATE posts SET upvotes=$residual[0],downvotes=$residual[1] WHERE postid=$post_dt[postid]");
 * if($vote_a && $vote_b){
 * 
 * $msgs = array("notice" => "Changed your mind and ".$_FILTERED['vote_action'] . "voted!");          
 * }else{echo mysqli_error($db_main);}
 */} 




} else {



// but if not, and it's a New vote....
$vote_a = mysqli_query($db_main, "INSERT INTO votes_q(bywhom,timeof,which_post,vote) VALUES('$_MONITORED[login_q]',now(),'" . $_FILTERED['post_que'] . "','$vote');");
 // in here we only need the first result
$residual_2 = ($vote == "1") ? intval($post_dt['upvotes']) + 1 : intval($post_dt['downvotes']) + 1;
 $vote_b = mysqli_query($db_main, "UPDATE posts SET " . $_FILTERED['vote_action'] . "votes='$residual_2' WHERE postid=$post_dt[postid] AND bywhom='$post_dt[bywhom]'");
 if ($vote_a && $vote_b) {
$msgs = array("notice" => $_FILTERED['vote_action'] . "voted!");

 $msgs = array("notice" => $_FILTERED['vote_action'] . "voted!");
 } else {
echo mysqli_error($db_main);
 } 
} 
echo json_encode($msgs);
 mysqli_free_result($vote_q);
 mysqli_free_result($post_check);
 } 

} 
} 
} 



if (isset($_REQUEST['search_1'])) {

if (is_array($_REQUEST['search_1'])) {
for($z = 0; $z <= count($_REQUEST['search_1'])-1;$z++) {
if (!$_REQUEST['search_1'][$z]) {
$_REQUEST['search_1'][$z] = "";
 } 
$molded[$z] = htmlspecialchars($_REQUEST['search_1'][$z]);
 } //my goodness

 } //$(molded)[1] or later will always be the outputs
 else {
return false;
 } 

if (preg_match("#^username2$#", $molded[0])) { // username check
$mns = mysqli_query($db_main, "SELECT * FROM userz WHERE username='" . $molded[1] . "'");
 if (preg_match("#^[A-Za-z0-9-_]{4,16}$#", $molded[1]) && mysqli_num_rows($mns) < 1) { // no usernames registered? and also the usual limits
echo "<u>" . $molded[1] . "</u> has not been registered yet, and can be registered. &#10004";

 } else {
if (preg_match("#^[A-Za-z0-9-_]{4,16}$#", $molded[1])) { // priority 1 is checking to see if it fits the username criteria
if (mysqli_num_rows($mns) > 0) {
echo "This username is already taken, unfortunately. If you've already signed up and forgot your password, please go to the forgotten password page.";
 // then if it's still available
} else {
echo "<u>" . $molded[1] . "</u> has not been registered yet, and can be registered. &#10004";
 } 
} else {
echo "Must be between 4-16 characters, numbers, letters and hyphens only (also an underscore)";
 } 

} 

mysqli_free_result($mns);

 } 


if (preg_match("#^pwrd2$#", $molded[0])) { // password
if (preg_match("#^(.){10,50}$#", $molded[1])) {
echo "Valid password. &#10004";
 } else {
echo "Password too short/too long. 10-50 characters";
 } 

} 
if (preg_match("#^email2$#", $molded[0])) { // you know which one this is
if (preg_match("#^([-_A-Za-z0-9]){1,30}[@](([-_A-Za-z0-9]){1,100}[.])+([-_A-Za-z0-9]){1,100}[.]*(([-_A-Za-z0-9]){1,100})+$#", $molded[1])) {
echo "Well that's a valid email address. &#10004<br> To get the full features of Captivate, you must confirm your email address.";
 } else {
echo "You did not enter a valid email address.";
 } // email validation regexes don't usually account for subdomains, I feel like
 } 
if (preg_match("#^fullname2$#", $molded[0])) {
if (preg_match("#^([A-Za-z-]{2,})+[ ]([A-Za-z-]{2,}[ ]){0,2}([A-Za-z-]{2,})+$#", $molded[1])) {
echo "Successfully registered your full name. &#10004";
 } else {
echo "We just need a first and a last name.";
 } 
} 
} 


exit();
 ?>