<?php     
if(isset($_GET['get_more'])){
    session_start();
    //crap, i'm gonna need a copy of a lot of the internalities, I should say, at internal.php           
    require_once("vars.php"); 
    require_once("internal.php");
} 
if(isset($sg_details) && isset($_SESSION['saved_pt_views'][$sg_details['sg_id']]) 
&& (count($_SESSION['saved_pt_views'][$sg_details['sg_id']]) > 0) && !isset($_GET['sg_id'])){  
    $_GET['get_more'] = "special";
    $_GET['sg_id'] = $sg_details['sg_id']; //too much conditionals for such a tiny statement expression eugh

}
if(news_feed_check){     
    if(isset($_SESSION['salt_q']) && $swiss_army->compare_dz($_SESSION['salt_q'],$logged_dt['salt'])){     //personal news feed      
        echo (!(isset($_GET['get_more']) || isset($_GET['load_option']))) ? "<div id='news_feed'>" : "";
        //data we need to get:               
        //the logged user's own posts, and every snowglobe he's subscribed to           

        //first get the user's own posts, then find every access type with this user's id and access type for snowglobe_(#snowglobeid)
        //have to find an efficient way to load the most recent posts
        //among other ways of assortment
        //i'm gonna have to put it in a session
        //clear it when the person followss new people
        //that way it's only going to have to search once   
      
        //In my defense I never thought of the concept of inner joins as I was typing that^

        $que_posts[6] = mysqli_query($db_main, "SELECT * FROM sg_permissions WHERE towhom='$_MONITORED[login_q]'");                          
        $post_query = "SELECT * FROM posts post_dt, sg_permissions sgp_dt WHERE post_dt.bywhom=sgp_dt.granted_by 
        AND sgp_dt.towhom='$_MONITORED[login_q]' AND 
        (post_dt.forwhom='self' OR EXISTS(SELECT * FROM snowglobes WHERE sg_url=post_dt.forwhom)) 
        AND post_dt.cnttype=1 ORDER BY stamptime DESC LIMIT 0, 25";   
        // I dont wanna left join on two tables already selected

        if(isset($_GET['snowglobe']) || isset($_GET['load_option'])){
            $_FILTERED['snowglobe'] = isset($_FILTERED['snowglobe']) ? $_FILTERED['snowglobe'] : 
            preg_replace("#^sg_(.+)$#","$1",$_FILTERED['load_option']);
            $post_query = "SELECT * FROM posts WHERE forwhom='$_FILTERED[snowglobe]' ORDER BY stamptime DESC LIMIT 0,25";
  
        }  
        if(isset($_SESSION['last_postid'])){      
            $post_query = (!isset($_GET['get_more'])) ? $post_query : "SELECT * FROM posts post_dt, sg_permissions sgp_dt 
            WHERE post_dt.bywhom=sgp_dt.granted_by AND sgp_dt.towhom='$_MONITORED[login_q]' 
            AND (post_dt.forwhom='self' OR EXISTS(SELECT * FROM snowglobes WHERE sg_url=post_dt.forwhom)) 
            AND post_dt.cnttype=1 AND post_dt.postid < '$_MONITORED[last_postid]' ORDER BY stamptime DESC LIMIT 0,25";
            if(isset($_GET['snowglobe']) || isset($_GET['load_option'])){
                $post_query = (!isset($_GET['get_more']) && isset($_GET['load_option'])) ? $post_query : 
                "SELECT * FROM posts WHERE forwhom='$_FILTERED[snowglobe]' 
                AND postid < '$_MONITORED[last_postid]' ORDER BY stamptime DESC LIMIT 0,25";
            }
        } 
        if(isset($_GET['get_more'],$_GET['sg_id']) && $_GET['get_more'] == "special"){
            $get_sg_details = mysqli_query($db_main, "SELECT * FROM snowglobes WHERE sg_id='". intval($_GET['sg_id']) ."'");
            $sg_details = mysqli_fetch_assoc($get_sg_details);                                       
            if(isset($_SESSION['last_postid']) && isset($_SESSION['saved_pt_views'][$_GET['sg_id']])){      
                foreach($_SESSION['saved_pt_views'] as $pt_key => $useless_value){
                    //to append to le sql query
                    $key_phrasing = isset($key_phrasing) ? $key_phrasing . " OR settings='".intval($pt_key)."'" : "settings='".intval($pt_key)."'";
                }                 
                $post_query = (isset($_SESSION['last_postid']) && isset($_SESSION['sg_lp_id'])) ? 
                "SELECT * FROM posts WHERE forwhom='$sg_details[sg_url]' AND postid < '$_SESSION[last_postid]' AND settings='$_MONITORED[sg_lp_id]'" : 
                "SELECT * FROM posts WHERE forwhom='$sg_details[sg_url]' AND ($key_phrasing)";   
            }
            else{ //all posts reset
                $post_query = "SELECT * FROM posts WHERE forwhom='$sg_details[sg_url]' ORDER BY stamptime DESC LIMIT 0,25";
            }
        }
        //HTML content starts here
        //begin posts queue                         
        $que_posts[0] = mysqli_query($db_main,$post_query);
        if(mysqli_num_rows($que_posts[0]) == 0){
            $message = (isset($_GET['get_more']) && $_GET['get_more'] == "special") ? $nx['59'] : $nx['37'];
            echo (isset($_GET['get_more']) && $_GET['get_more'] == "special") ? "<br>" : "";
            echo "<div class='notice center'>". $message ."</div>";
        }
        //begin news feed
        while($que_own = mysqli_fetch_assoc($que_posts[0])){
            $posterdt = mysqli_query($db_main,"SELECT u.profile_pic_url,img.type,img.url_hash FROM userz u LEFT JOIN images img ON u.profile_pic_url=img.url_hash WHERE username='$que_own[bywhom]'");
            $poster_dt = mysqli_fetch_assoc($posterdt);
            if(!preg_match("#(self|[\050][a-z][\051])$#",$que_own['forwhom'])){
                //test for non-profile posts, and therefore ones with their own snowglobe, and get its data
                $zen = mysqli_query($db_main, "SELECT * FROM snowglobes WHERE sg_url='$que_own[forwhom]'");
                $snowglobe_zen = mysqli_fetch_assoc($zen);
            }
            $_SESSION['last_postid'] = $que_own['postid'];    //get last record for later reference
            //begin posts
    //        $score = round(($que_own['upvotes'] / ($que_own['upvotes'] + $que_own['downvotes'])),2) *100;
            $que_posts[1] = mysqli_query($db_main, "SELECT * FROM votes_q WHERE which_post='$que_own[postid]'");
            if(mysqli_num_rows($que_posts[1]) > 0){ //it's too easy to get lost in your train of thought
                //practice comments, idiot
                //find vote made by this user in this topic
                $que_posts[2] = mysqli_query($db_main, "SELECT * FROM votes_q WHERE which_post='$que_own[postid]' AND bywhom='$_MONITORED[login_q]'");
                //then reference it to a variable
                $que_vote =  mysqli_fetch_assoc($que_posts[2]);
                if($que_vote['vote'] > 0){
                    $vote = "yes";
                }
                else{
                    $vote = (mysqli_num_rows($que_posts[2]) > 0) ? "no" : "none";  
                }
            }
            else{ //if there were no votes made in this topic(EXCLUDING the user's own, which would be yes when created
                //I did this to save some bandwidth
                $vote = "yes";
            }
            $que_posts[3] = mysqli_query($db_main, "SELECT * FROM polls where post_id_root='$que_own[postid]' 
            ORDER by (CASE 'define_set' WHEN 'question' THEN 1 WHEN 'choice_selection' THEN 2 WHEN 'choice_addition' 
            THEN 3 WHEN 'poll_choice' THEN 4 ELSE 25 END) DESC");      //check to see if it has a poll, also have the poll questions be last
            $spoiler_patt = "#^[\050](One-liner)[\051]([ ]{0,5})[\133](.+)[\135]$#";
            $select_class[0] = ($vote == "yes" && isset($que_posts[2]) > 0) ? "selected" : "";
            $select_class[1] = ($vote == "no" && isset($que_posts[2]) > 0) ? "selected" : ""; 
            $num_comments_disp = "<a href='show-comments' class='mini-notice prompt rad' postid='$que_own[postid]'><strong>$que_own[num_replies]</strong> $nx[77]".$swiss_army->approp_s($que_own['num_replies'])."</a>";
            $blank_post_check = (preg_match("#^[ \n\t\h]{0,}$#",$que_own['content']) === 1);
            $content_blur = $blank_post_check ? false : "<p>".$que_own['content'] ;
            $spoiler_tags = (preg_match($spoiler_patt,$que_own['title']) === 1) ? "<span class='quote_parse'>\"</span>" : "";
            $content_blur = ($content_blur !== false || $blank_post_check) ? "<p>" . $spoiler_tags . $que_own['content'] : false;
            $content_blur = (preg_match($spoiler_patt,$que_own['title']) && ($content_blur !== false)) ? $content_blur . $spoiler_tags . "</p>" : $content_blur ;
            $zing = ($content_blur == false || preg_match($spoiler_patt,$que_own['title'])) ? "" : "</p>";
            $align_blocks = preg_match("#^[ ]+$#",$que_own['content']) ? " valign='middle'" : "";
            
            //begin start of one news feed entry
            $profile_pic = ($poster_dt['profile_pic_url'] === "none") ? image_dir."propic_mid.png" : main_dir."images/".$poster_dt['profile_pic_url'] . "." . $poster_dt['type'];
            echo "<div class='contentbox index_post' alt='".$que_own['postid']."'>
            <table><tr><td width='1%' class='profile_disp'><a href='".main_dir."profile/".$que_own['bywhom'] ."'><img src='$profile_pic' alt='{".$que_own['bywhom']." pic}'></a></td><td width='1%' class='left_side username_disp'><strong><a href='".main_dir."profile/".$que_own['bywhom'] ."'>".$que_own['bywhom'] ."</a>
            </strong></td><td class='content'$align_blocks>";
            
            //begin HTML content
            
            //spoiler formatting
          
            
            if(preg_match($spoiler_patt,$que_own['title'])){
                preg_match("#^[\050]([^\041]+)[\051]([ ]{0,})[\133](.+)[\135]$[ ]{0,}#",$que_own['title'],$matches);
                echo $num_comments_disp;
                switch($matches[1]){
                    case "One-liner":
                        echo "<strong>". $matches[3] ." <a class='prompt' href='toggle'>$nx[76]</a></strong>";
                        echo "<div class='spoiler'>".$content_blur."</div>";    
                    break;   
                }
            }                       
            else{   
                $wraps = ($blank_post_check === true) ? ["<span class='post_head'>","</span>"] : ["<div class='r_title rad'>","</div>"];
                echo "<table><tr>";
                
                if($blank_post_check === false){
                
                echo"<td width='1%'>$num_comments_disp</td><td>" . $wraps[0] .$que_own['title'] . $wraps[1]. "</td>";
                
                }
                
                else {  
                
                echo "<td class='title_only'>" . $wraps[0] .$que_own['title'] . $wraps[1]. "$num_comments_disp</td>";
                
                }
                
                echo "</tr></table>" . $content_blur;                                 
            }
            echo "<span class='bywhom'> ";
            echo (isset($_SESSION['login_q']) && $que_own['forwhom'] == "self" && $que_own['bywhom'] == $_MONITORED['login_q']) ? 
            "(".$nx['51'].")" : "";
            if(index_page_check && isset($snowglobe_zen)){  
                echo "(".$nx['52'] . " <a href='".main_dir."sg/".$snowglobe_zen['sg_url']."' class='sg_link'>" . $snowglobe_zen['sg_name']."</a>)";
                unset($zen); unset($snowglobe_zen);
            }
            echo "</span>" .$zing;
            //image posts
              if($que_own['image_embed'] !== "none"){              
                  $q_image = mysqli_query($db_main, "SELECT url_hash,type FROM images WHERE under_post='$que_own[postid]' AND url_hash='$que_own[image_embed]'");
                  $que_image = mysqli_fetch_assoc($q_image);
                  echo "<p class='image_embed'>";
                  echo "<img src='".main_dir."images/".$que_image['url_hash'].".".$que_image['type']."'>";
                  echo "</p>";
                  mysqli_free_result($q_image);
              }
              echo "</td>";      //POLLS
              if(mysqli_num_rows($que_posts[3]) > 0){
                  echo "<td class='poll_choosy'>";  
                  $z = 0; $poll_dt = [];         $poll_choice_zen = [];      $total = 0;
                  while($poll_extract = mysqli_fetch_assoc($que_posts[3])){
                      //check if current poll data query in-loop is a poll choice and if there are previous results 
                      //that are poll choices, then merge the array
                      //but if not, just leave it be 
                      $poll_async = isset($poll_dt['poll_choice']) ? array_merge(array("pcid_" . $poll_extract['data_id'] => $poll_extract),$poll_async) : array("pcid_" . $poll_extract['data_id'] => $poll_extract);
                      $poll_dt[$poll_extract['define_set']] = ($poll_extract['define_set'] == "poll_choice") ? $poll_async : $poll_extract;
                  }
                  //1 is the question, 2 is the choice selection, 3 is the choice addition and everything after that are the poll questions
                  //looks like i'm gonna have to make another button for radios
                  $question = $poll_dt['question']['value'];
                  $selection_type = ($poll_dt['choice_selection']['value'] == "true") ? "radio" : "checkbox";
                  $addition_choice = $poll_dt['choice_addition']['value'];
                  echo "<h4>$nx[86] <span>$question</span></h4>";
                  echo "<span class='poll_choices is_".$selection_type."'>";
                  //check if userlogged has voted on any poll choice
                  $que_posts[4] = mysqli_query($db_main, "SELECT * FROM pollvotes_q WHERE which_poll='$que_own[postid]' 
                  AND bywhom='$_MONITORED[login_q]'");
                  if(mysqli_num_rows($que_posts[4]) > 0){
                      while($que_pollvotes = mysqli_fetch_assoc($que_posts[4])){    //get all poll votes
                          $this_voted_on[$que_pollvotes['choice_id']] = $que_pollvotes['choice_id'];
                      }
                      //add total poll votes
                      foreach($poll_dt['poll_choice'] as $totes){
                          $total = ($total == "0") ? $totes['votes'] : $totes['votes'] + $total;
                      } 
                      //display poll results
                      foreach($poll_dt['poll_choice'] as $poll_disp){
                          $pct = ($poll_disp['votes']/$total)*100;
                          $set_ayy = isset($this_voted_on[$poll_disp['data_id']]) ? " vote_select" : "";
                          echo "<div class='optionbox results".$set_ayy."'>" . $poll_disp['value'] . " (".$pct."%)";
                          if(isset($this_voted_on[$poll_disp['data_id']])){
                              echo "<span class='bywhom'>[You voted this.]</span>";
                          }
                          echo "</div>";
                      }
                      $this_voted_on = [];
                  }
                  else {    
                      foreach($poll_dt['poll_choice'] as $i => $j){    //i'll have to sort by most popular choices later, probably? On the one hand, they need to be as unbiased as possible
                      echo "<div class='optionbox'><input type='".$selection_type."' name='pc_".$poll_dt['poll_choice'][$i]['post_id_root']."' value='".$poll_dt['poll_choice'][$i]['data_id']."' ref='".$poll_dt['poll_choice'][$i]['post_id_root']."' class='poll_choice'><span>".$poll_dt['poll_choice'][$i]['value']."</span></div>";
                      }       
                      echo "<a href='vote' class='prompt button_samp rad'>VOTE!</a></span>";
                  }     
              echo "</td>";              }                  

              echo "<td width='1%' class='right_side'><span class='right side_info' alt='" .$que_own['postid'] . "'>
              <a href='thread/".$que_own['thread_nick']."_".$que_own['topic_hash']."' class='topic_link'>".$swiss_army->time_rounds($que_own['stamptime'])."</a>
              <span class='score'> (".$que_own['upvotes']." approval)</span>";
              echo "<span class='votes'><a href='up' class='vote_" .$select_class[0] . " upvote'></a>";
         // echo "    <a href='down' class='vote_" .$select_class[1] . "'><img src='".main_dir."core/img/down.png'></a>"    //im disabling downvotes for now
              echo"</span></span>
              </td></tr></table></div>";
        };       
        echo (!isset($_GET['get_more'])) ? "</div>" : "";
        $is_for_pt_spec = (isset($_GET['sg_lp_id'])) ? " rec='special'" : "";
        echo (mysqli_num_rows($que_posts[0]) > 0) ? "<a href='load-more' class='prompt load-more-button'$is_for_pt_spec>Load more posts</a>" : "";
        for($i = count($que_posts)-1;$i >= 0;$i--){
            if(isset($que_posts[$i])){ mysqli_free_result($que_posts[$i]); }
        }
        $swiss_army->clear_array($poll_dt);
    }
}
?>