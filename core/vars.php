<?php     
$check_3['username'] = isset($check_3['username']) ? $check_3['username'] : "";


//text references
//remember that they will show up as $nx[n] where n = its position in the sequence -1
//This is so that I can change it for other translations easier in the future.
@$nx = array(  //english
"username..." /*1 - username pre-click text*/,
"password" /* 2- password pre-click text*/,
"LOG IN" /*3 - log in button text*/,
"SIGN UP" /*4 -signup button text*/,
/*5 - first-time visitor*/"<p>Are you a first-time visitor?<br><br>Captivate is a social media
site that has multiple ways
of interaction with other
users.  <br><br>

If this is truly your first time
visiting this site, we
encourage you to sign up
to fully know of all
our types of social media.
which includes, but is not
limited to a message board,
a gallery. polls, and a 
normal news feed.   <br><br>

You ask, what makes us
different from all the other
social media sites? Diversity
of content. Some may have
some, or even most, of the
kind of social media we
have, but we have all of
them lumped in one site. <br><br>
Signing up is totally free and will take less than a minute at the most.</p><div class='bottom_menu'><a href='?direct=signup' class='side1'>Sign Up</a><a href='toggle' class='side2 prompt'>Close</a></div>",   // 6 - next, up, some fake sample terms and conditions lol 
'<h3>Terms and conditions</h3>
<div class="boxxed">
<p>Welcome to our online store! Captivate and its associates provide their services to you subject to the following conditions. You are bound by the rules of these within any page of http://captivate.ws. </p>

<h4>PRIVACY</h4>

<p>Please review our Privacy Notice, which also governs your visit to our website, to understand our practices. </p>

<h4>ELECTRONIC COMMUNICATIONS</h4>

<p>When you visit Captivate or send e-mails to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by e-mail or by posting notices on this site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing. </p>

<h4>COPYRIGHT</h4>

<p>All content included on this site, such as text, graphics, logos, button icons, images, audio clips, digital downloads, data compilations, and software, is the property of Captivate or its content suppliers and protected by international copyright laws. The compilation of all content on this site is the exclusive property of Captivate, with copyright authorship for this collection by Captivate, and protected by international copyright laws. </p>

<h4>TRADE MARKS</h4>

<p>Captivates trademarks and trade dress may not be used in connection with any product or service that is not Captivates, in any manner that is likely to cause confusion among customers, or in any manner that disparages or discredits Captivate. All other trademarks not owned by Captivate or its subsidiaries that appear on this site are the property of their respective owners, who may or may not be affiliated with, connected to, or sponsored by Captivate or its subsidiaries. </p>

<h4>LICENSE AND SITE ACCESS</h4>

<p>Captivate grants you a limited license to access and make personal use of this site and not to download (other than page caching) or modify it, or any portion of it, except with express written consent of Captivate. This license does not include any resale or commercial use of this site or its contents: any collection and use of any product listings, descriptions, or prices: any derivative use of this site or its contents: any downloading or copying of account information for the benefit of another merchant: or any use of data mining, robots, or similar data gathering and extraction tools. This site or any portion of this site may not be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without express written consent of Captivate. You may not frame or utilize framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of Captivate and our associates without express written consent. You may not use any meta tags or any other "hidden text" utilizing Captivates name or trademarks without the express written consent of Captivate. Any unauthorized use terminates the permission or license granted by Captivate. You are granted a limited, revocable, and nonexclusive right to create a hyperlink to the home page of Captivate so long as the link does not portray Captivate, its associates, or their products or services in a false, misleading, derogatory, or otherwise offensive matter. You may not use any Captivate logo or other proprietary graphic or trademark as part of the link without express written permission. </p>

<h4>YOUR MEMBERSHIP ACCOUNT</h4>

<p>If you use this site, you are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer, and you agree to accept responsibility for all activities that occur under your account or password. If you are under 18, you may use our website only with involvement of a parent or guardian. Captivate and its associates reserve the right to refuse service, terminate accounts, remove or edit content, or cancel orders in their sole discretion. </p>

<h4>REVIEWS, COMMENTS, EMAILS, AND OTHER CONTENT</h4>

<p>Visitors may post reviews, comments, and other content: and submit suggestions, ideas, comments, questions, or other information, so long as the content is not illegal, obscene, threatening, defamatory, invasive of privacy, infringing of intellectual property rights, or otherwise injurious to third parties or objectionable and does not consist of or contain software viruses, political campaigning, commercial solicitation, chain letters, mass mailings, or any form of "spam." You may not use a false e-mail address, impersonate any person or entity, or otherwise mislead as to the origin of a card or other content. Captivate reserves the right (but not the obligation) to remove or edit such content, but does not regularly review posted content. If you do post content or submit material, and unless we indicate otherwise, you grant Captivate and its associates a nonexclusive, royalty-free, perpetual, irrevocable, and fully sublicensable right to use, reproduce, modify, adapt, publish, translate, create derivative works from, distribute, and display such content throughout the world in any media. You grant Captivate and its associates and sublicensees the right to use the name that you submit in connection with such content, if they choose. You represent and warrant that you own or otherwise control all of the rights to the content that you post: that the content is accurate: that use of the content you supply does not violate this policy and will not cause injury to any person or entity: and that you will indemnify Captivate or its associates for all claims resulting from content you supply. Captivate has the right but not the obligation to monitor and edit or remove any activity or content. Captivate takes no responsibility and assumes no liability for any content posted by you or any third party. </p>

<h4>RISK OF LOSS</h4>

<p>All items purchased from Captivate are made pursuant to a shipment contract. This basically means that the risk of loss and title for such items pass to you upon our delivery to the carrier. </p>

<h4>PRODUCT DESCRIPTIONS</h4>

<p>Captivate and its associates attempt to be as accurate as possible. However, Captivate does not warrant that product descriptions or other content of this site is accurate, complete, reliable, current, or error-free. If a product offered by Captivate itself is not as described, your sole remedy is to return it in unused condition. </p>

<p>DISCLAIMER OF WARRANTIES AND LIMITATION OF LIABILITY THIS SITE IS PROVIDED BY Captivate ON AN "AS IS" AND "AS AVAILABLE" BASIS. Captivate MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, AS TO THE OPERATION OF THIS SITE OR THE INFORMATION, CONTENT, MATERIALS, OR PRODUCTS INCLUDED ON THIS SITE. YOU EXPRESSLY AGREE THAT YOUR USE OF THIS SITE IS AT YOUR SOLE RISK. TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, Captivate DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. Captivate DOES NOT WARRANT THAT THIS SITE, ITS SERVERS, OR E-MAIL SENT FROM Captivate ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. Captivate WILL NOT BE LIABLE FOR ANY DAMAGES OF ANY KIND ARISING FROM THE USE OF THIS SITE, INCLUDING, BUT NOT LIMITED TO DIRECT, INDIRECT, INCIDENTAL, PUNITIVE, AND CONSEQUENTIAL DAMAGES. CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS, OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MIGHT HAVE ADDITIONAL RIGHTS. </p>

<h4>APPLICABLE LAW</h4>

<p>By visiting Captivate, you agree that the laws of the state of DEFINE_STATE, DEFINE_COUNTRY, without regard to principles of conflict of laws, will govern these Conditions of Use and any dispute of any sort that might arise between you and Captivate or its associates. </p>

<h4>DISPUTES</h4>

<p>Any dispute relating in any way to your visit to Captivate or to products you purchase through Captivate shall be submitted to confidential arbitration in DEFINE_STATE, DEFINE_COUNTRY, except that, to the extent you have in any manner violated or threatened to violate Captivates intellectual property rights, Captivate may seek injunctive or other appropriate relief in any state or federal court in the state of DEFINE_STATE, DEFINE_COUNTRY, and you consent to exclusive jurisdiction and venue in such courts. Arbitration under this agreement shall be conducted under the rules then prevailing of the American Arbitration Association. The arbitrators award shall be binding and may be entered as a judgment in any court of competent jurisdiction. To the fullest extent permitted by applicable law, no arbitration under this Agreement shall be joined to an arbitration involving any other party subject to this Agreement, whether through class arbitration proceedings or otherwise. </p>

<h4>SITE POLICIES, MODIFICATION, AND SEVERABILITY</h4>

<p class="nobuff">Please review our other policies, such as our Shipping and Returns policy, posted on this site. These policies also govern your visit to Captivate. We reserve the right to make changes to our site, policies, and these Conditions of Use at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and shall not affect the validity and enforceability of any remaining condition. </p></div>',
'Desired username', // 7 - username registrar
'Your password',    //8 - password registrar test
'Your email address', //9 - email address
'Your full name','Date of birth',"Greetings", // have this just for the sake of different languages 10, 11, and 12 right here
"View all of your currently used services", //13 - view all currently used services 
"Edit your profile" // 14 - profile link at user_menu dropdown
, "Viewing Profile" // 15 - 
, "OPTIONS" // 16 - More uptions under menu
, "Joined " // 17 - 
, "Thoughts you might have right now, post it here!"     //on the new post - 18
, "...Even explain it in detail, if you like!"//19
, "Post into these snowglobes?" // 20th
, "Your Profile" //21st€
, "Post it!"      //22 - this is in caps btw
, "This user has no posts yet." //23 
,"Replying to " //24
, "Reply to this comment... Ergo, comment on this comment." //25 - reply to this comment text
, "Submit comment!" //26 submit comment submit button
, " (Comment chain) " //27 viewing comment chain page title
, "(One-liner) [Insert Joke Here]"
, "Punchline goes here. Hopefully, it not only sounds funny in your head."
, "<div class='box'>
<h3>About to post? Here's a quick format guide:</h3>
<p>Put the code <span class='code'>(One-liner) [Insert Joke Here]</span> in the beginning of the title and you can post your own one-liner joke! The punchline is in the content. Hopefully the joke you made will be worth the cued laugh track in your mind. Whenever people see the post, the punchline is hidden in the spoilers. The second box will have the punchline. <strong><a href='one-liner' class='prompt'>Try it!</a> &middot; <a href='' id='close_button1'>(Close window)</a></strong></p>

</div>",
"ATTACH A POLL",
//remember
//poll settings
//additional poll questions
//let's make it up to 20 questions maximum

"<h3>Poll maker</h3><input type='text' class='largeform flick pollquestion' value='Insert poll question here...' name='poll_question'><h3>Poll choices<span> - You must have at least two</span></h3><input type='text' class='largeform flick' value='Add a choice here' name='poll_choice'><input type='text' class='largeform flick' value='Add a choice here' name='poll_choice'><a href='add-more-choices' class='prompt button_samp rad'>Add more choices...</a><h3>Poll options</h3><div class='optionbox'><input type='checkbox' name='choice_addition'><span>Make anyone able to add poll choices</span></div><div class='optionbox'><input type='checkbox' name='choice_selection'><span>Only one poll choice allowed for each user</span></div><a href='finished-poll-q' class='greened prompt button_samp rad'>Finish poll questions</a><a href='close-poll' class='redded prompt button_samp rad'>Discard Poll</a>"
,"Editing your profile"   , 
"Unfortunately, the school you typed down is currently not in the directory. Click here to add your school into the current directory."
, "Add your school","Your search query is going to be referenced with all Wikipedia school entries. If your school is not listed in Wikipedia, you will have to type in details for yourself."
, "","There are no more posts to display.","Successfully completed input!","Unfortunately, you have failed to enter correct details."   //39th at this point
,"Chatting with: ","Start a conversation with this user!","Are you currently enrolled in high school/college? Get a head start on your classes by signing up for our <em>Lecture Notes</em> feature!","Your snowglobes",
"A Snowglobe is pretty to look at, and usually represents some place that is suspended within the bounds of a sphere. In Captivate, however, there's a lot more depth to a Snowglobe. It's a place where you can talk about a specific topic, or maybe about a whole host of topics. Be it about anything mundane like road rage, stern like being a Call of Duty fan, or more serious discourse like your dog's pet peeves, the limits of discussion just don't exist. Anything as varied as your whims, and even your idleness, or boredom, can be talked about in a Snowglobe.", //What are snowglobes? #44
"What is a Snowglobe anyway?","Your Profile","Create a new Snowglobe","<em>No current description for this snowglobe. Be sure to edit this to get more people to tune into your snowglobe!</em>","<div class='notice no_posts center'>There are currently no posts in this Snowglobe. Be the first to post by using the template above!</div>","<center>There is no description available for this Snowglobe at the moment.</center>","Posted in your own snowglobe","Posted in","Logout","You have already finished attending this school",
'Unfortunately, there are currently no <strong message="Snowglobes that are registered to a class in a school." dd_class="reg">Classroom Snowglobes</strong> registered to your school. However, you should certainly sign up (one of) your class(es) to enjoy all the benefits of it, well, being signed up, which includes, but is certainly not limited to:

<ol>
<!-- <li></li> -->
<li>Being caught up in the class schedule.</li>
<li>Getting feedback on your own class projects.</li>
<li>Reminding your teacher/professor/etc. on your sick days, etc.</li>
<li>Studying material related to the class subject.</li>
<li>Other students in the class regularly updating the site with homework advice, class results, and miscellaneous questions regarding lecture material, or merely the class as a whole.</li>

</ol>',
"<div class='box notice2' reference='future_classes'><h3>School Classes <a href='close_box' class='prompt' message=\"Don't show again\" dd_class='reg'>[x]</a></h3><p>Will you be taking classes in the near future? If so, then Captivate has features that will help you greatly in your studies.</p><p><a href='".main_dir."profile_nuise/".$_SESSION['login_q']."' class='button_samp rad'>Find out more!</a></p></div>",
"Does this apply to any snowglobe in particular? Leave blank to make it default.","Description","There are no posts to display.","Loading...","Post a new...",
'Describe your " + z + " here.',
'Insert Your " + z + " Here',
" (More replies in this comment chain.)",
" <strong>(Selected Post)</strong>",
"Users will show up here if you both follow each other. Of course you can send a message regardless, but it will be shown somewhere less exposed.",
"Successfully edited!",
"Click Again to Confirm Delete","Successfully Deleted!",
"[Post Deleted]","Classroom Snowglobe","Fill-in Form",
"Didn't find the class you were looking for? Feel free to add the class you're in yourself! Other people in your school will be notified that a new Class Snowglobe once you have submitted the form below.","Narrow down your search by name here...",'No Class Snowglobes found.',"Click here..."," Comment","There are no comments here. Be the first to reply!",

"<textarea class='largeform flick'>Insert Comment Here</textarea><a href='submit-comment-quick' class='prompt button_samp rad greened'>Submit</a>","Most Recent Replies"
,

"<strong>&darr;</strong> Minimize Panel",
"<strong>&uarr;</strong> Maximize Panel",
"<strong>&hArr;</strong> Enable Drag",
"<strong>&hArr;</strong> Disable Drag"  ,
"<div class='scale_notice'>Scaled down to \"+ Math.round(pct*100) +\"%, click <a href='\"+img_src+\"' target='_blank'>here</a> to show in full.</div>",
"[QUESTION]",
"We just need a first and last name.", 



//lol now im starting named key-value pairs
"no_gc_msgs" => "There are no messages right now in this groupchat. You can be the first one!",
"finish_profiledit_submit_text" => "Finish Editing",
"currently_text" => "Currently:",
"full_name" => "Full name",
"dob" => "Date of birth",
"profile_pic_header" => "Profile Picture",           
"pic_search_txt" => "Search for pictures...",
"basic_info" => "Basic Information",
"no_profile_pic_msg" => "Currently none. Feel free to upload one by using the \"Search for Pictures\" button below.",
"replied_to_your" => "replied to your",
"content_types" => ["thread","comment"],
"no_profile_pic_alt" => "No Profile Pic",
"last_active_text" => "Last active ",
"create_gc_txt" => "Create Groupchat...",    
"users_to_invite" => "Initial Groupchat members<br><small>(Invite 2 or more people who follow you!)</small>",
"gc_name" => "Name of groupchat...",
"u_search" => "Search for user...",
"loading_text" => "Loading...",
"finish_gc" => "Finish Groupchat",
"cancel_gc" => "Cancel Groupchat",
"selected_users_txt" => "Selected Users",
"created_sg_msg" => "Created new groupchat!",
"gc_errormsg_1" => "\n One of the users you selected cannot be selected.",
"gc_errormsg_2" => "\n Groupchat type error",
"sql_textbox_note" => "Type your SQL query here... Any SQL query that will drop a table, or delete a row will require a password."
);

//i'm gonna loop all these date-times
function loop_date_fields($limit,$startofloop,$values = null){  
    $x = $startofloop;
    $return_val = "";
    if(isset($values)){
        $values = (preg_match("#,#",$values) === 1) ? preg_split("#,#",$values) : [$x => $values];
    }
    do {
        $option_display = isset($values[$x - $startofloop]) ? $values[$x - $startofloop] : $x;
        $return_val .= "<option value='$x'>$option_display</span>";
    } while($limit > $x && $x++);
    return $return_val;
}
$dobordate = "<select name='day2' class='largeform'>".loop_date_fields(31,1)."</select> 
  <select name='month2' class='largeform'>
  ".loop_date_fields(12,1,'January,February,March,April,May,June,July,August,September,October,November,December')."
  </select> 
  <select name='year2' class='largeform'>".loop_date_fields(2015,1900)."</select>";

class fill_data_aux {
    public function pt_disp(&$array_queued){
        $post_help_info = strlen($array_queued['post_help']) > 0 ? " post_help='".$array_queued['post_help']."'" : "";
        return "                    <li class='rad pt_opt_in' pt_id='$array_queued[pt_id]'$post_help_info desc='$array_queued[description]'>$array_queued[call_name]</li>\n";  
    }
    public function js_parsed($var){ global $nx;
        switch($var){
            case "edit_button":
                echo "<a href='finish-edit' class='comment_opts rad edit_q finish-edit_q'>Finish Editing</a>";
            break;
            case "reply_form":
                echo "<form action='".main_dir."index.php?direct=new_post' method='POST'><input type='hidden' value='\"+thread_id+\"' name='thread_id'><input type='hidden' value='Comments' name='tcha1'><input value='\"+ attribution_id +\"' name='parent_comment' type='hidden'><textarea name='tcha2' class='largeform flick'>$nx[24]</textarea><input type='submit' value='$nx[25]' class='comment_opts'></form>";
            break;
        }
    } 
}

class fill_data extends fill_data_aux { //first time doing classes
    public function chat_box($user){
        return '<div class="box" ref="'.$user.'"><h3>Chatting with '.$user.'</h3><div class="message_box"></div></div>';
    }
    public function file_upload($input_name,$html_placeholder){
        $input_name = preg_match("#^[a-zA-Z0-9_-]+$#",$input_name) ? $input_name : "";
        return "<input type='file' name='$input_name' class='upload inline2' title=''>$html_placeholder";    
    }
    public function show_reply(&$array_name,$is_selected_comment = null){  global $logged_dt,$nx,$db_checks,$swiss_army;
        //get the reply template

        //going to load the reply template from vars.php
        //$array_name will be the array that's called from the for/while loop that'll be getting all the replies
        //you can find it at vars.php
                                                                                   
        //I have no clue why the hell I did those wrappers. wtf was I thinking      
        $selected_class_add = isset($is_selected_comment) ? [" selected",$nx['65']] : ["",""];
        $more_replies_notice = isset($array_name['more_replies_notice']) ? $nx['64'] : "";
        echo "<div class='contentbox comment_box$selected_class_add[0]'><table><tr> <td class='user_info'><h4><a href='/profile/".$array_name['bywhom']."'>".$array_name['bywhom']."</a></h4></td><td>
        <p class='post_text'> ".$array_name['content']. "<span class='side_info'> - Posted <a href='".main_dir."thread/".$_GET['thread_view']."/comment/".$array_name['topic_hash'] ."'>". date(dflt_date_f, strtotime($array_name['stamptime'])) ."</a> $selected_class_add[1] $more_replies_notice</span> </p>";
        if(isset($_SESSION['login_q']) && $swiss_army->compare_dz($logged_dt['salt'],$_SESSION['salt_q'])){
            echo "<div class='opts_block reply_m' alt='".$array_name['postid']."'>";
            echo "<a href='comment_q-u' class='comment_opts comment_q-u rad' id='post_".$array_name['postid']."'
            name='post_".$array_name['postid']."'>Reply</a>";
            //admin rights, mod rights, and then user's rights to their own posts
            if($db_checks->check_perms_of_post("altering_posts",$array_name['postid'])){
                echo "<a href='edit' class='comment_opts rad edit edit_q' id='edit_".$array_name['postid']."'>Edit</a>";
                echo "<a href='delete' class='comment_opts rad delete' id='delete_".$array_name['postid']."'>Delete</a>";
            }
            echo "</div>";
        } 
        echo "</td></tr></table></div>";
    }
    public function sg_sidebar(&$sg_details,$thread_view = false){
        global $nx;
        echo '<div id="sg_desc" class="box rad"><h3>'.$sg_details["sg_name"].'</h3>';
        if((!empty($sg_details['description']) || !preg_match("#^[ ]+$#",$sg_details['description']))){ 
            echo (preg_match("#^ {0,}$#",$sg_details['description'])) ? $nx['50'] : $sg_details['description']; 
        }
        if($thread_view){                                                   
            echo "<a href='".main_dir."sg/".$sg_details['sg_url']."' class='go_back_msg rad'>Back to thread listing</a>";
        }
        echo "</div>";
    }   
    public function about_poster_sidebar(&$sidebar_dt){   
        echo '<div class="box">
            <h3>About the poster:</h3>
            <p><a href="'.main_dir.'profile/'.$sidebar_dt["username"].'">Profile Link</a><br>
            </div>';       
    }
    public function pt_post_opt(&$sql_que_in_q){
        global $nx;
        echo "<div id='content' class='contentbox hide'>".$nx['31']."</div>";
        echo 
        "
        <div id='pt_post_op' class='hide'>
            <span class='drop'>
                <div class='uplink rad'>$nx[61]</div>
                <div class='dropdown_content'>
                    <ul>";
        echo "                    <li class='rad pt_opt_in' pt_id='0'>No special formatting (Default)</li>";
        if(is_array($sql_que_in_q)){ //sql query in question in case the reference wasn't clear
            foreach($sql_que_in_q as $post_type){
                echo $this->pt_disp($post_type);                
            }
        }
        else{
            while($post_type = mysqli_fetch_assoc($sql_que_in_q)){
                echo $this->pt_disp($post_type);              
            }
        }
        echo
        "
                    </ul>
                </div>
            </span>
        </div>";   //on second thought, indenting HTML seems a bit much lol
    }       
}
$fill_data = new fill_data();   

?>