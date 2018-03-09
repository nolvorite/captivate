<?php

if (count($_GET) == 1) {
    
    // edit profile
    echo "<form action='" . main_dir . "index.php?verify=" . $_SESSION['temp_n'] . "&action=edit_profile' method='POST'>";
    echo "<div class='contentbox' id='profile_edit'>";
    echo "<h3>" . $nx[32] . "</h3>";
    
    // basic information edits
    echo "<table class='base'><tr><td width='70%' class='topcheck'>";
    echo "<h4>$nx[basic_info]</h4>";
    
    echo "<table class='dashed'>";
    echo"<tr>";
     echo "<td width='50%'>";
    
    echo "<input type='text' class='largeform flick' name='fullname' value='$nx[full_name]'>";
    echo "</td><td class='nam'><strong>$nx[currently_text]</strong> $logged_dt[fullname]</td>";
    echo "</tr>";
    echo"<tr>";
     echo "<td width='50%' id='dob-edit'>" . $dobordate;
     echo "</td><td class='nam'>$nx[dob]</td>";
    echo "</tr>";
    
    
    echo "</table>";
    
    echo "</td>";
    echo "<td width='30%' valign='top' class='topcheck'>";
    echo "<h4>$nx[profile_pic_header]</h4>";
    
    switch ($logged_dt['profile_pic_url']) {
    case "none":
        echo "<img src='" . image_dir . "profilepicpre.png' alt='$nx[no_profile_pic_alt]'><p class='small_text'>$nx[no_profile_pic_msg]</p>";
        break;
    default:
        
        break;
        } 
    
    echo $fill_data -> file_upload("profile_pic_img", "<a href='upload_profile_pic' class='placeholder profile_pic_upload rad prompt'>$nx[pic_search_txt]</a>");
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    
    /**
     * this is where the education form used to be
     */
    
    echo "<input type='submit' value='$nx[finish_profiledit_submit_text]'>";
    
    
    echo "</div>";
    
    echo "</form>";
    
    
    } else {
    
    
    if (isset($_GET['find'])) {
        switch ($_GET['find']) {
        case "new_school":
            // time to switch up my coding style
            // I need to get into objects, as it just seems intuitive
            ?>

<div class="contentbox visc">
<h3 class="dashes bottom"><?php echo $nx['34']; //Add a school here
            ?></h3>
<h4>Details - <span><?php echo $nx['35'];
            ?></span></h4>
<p>
<input class="flick largeform" type="text" value="School Name">
<input class="flick largeform" type="text" value="Type your city's name to narrow down the search...">
<input type="submit" value="SEARCH SCHOOL" class="prompt">
</p>
</div>

<?php
            
            break;
        case "snowglobes": // snowglobe
            // find other snowglobes by user
            $sg_search = mysqli_query($db_main, "SELECT * FROM snowglobes WHERE root_admin_id='$logged_dt[userid]' ORDER BY sg_id DESC") or die(mysqli_error($db_main));
            
            ?>

<div class="contentbox new_sg">
<h3 class="dashes bottom"><?php echo $nx['43']; //Snowglobes. Obviously we're going to have the person's profile at least
            ?></h3><h4><?php echo $nx['45'];
            ?></h4>

<p>
<?php echo $nx['44'];
            // input creation date
            // input privacy
            // check to see if it was finished being modified
            // Description
            ?>
</p>
<h4><?php echo $nx['46'];
            ?></h4>
<p><strong>Link:</strong> <a href="<?php echo $profile_link;
            ?>"><?php echo $profile_link;
            ?></a></p>
<h4>Other Snowglobes</h4>
<?php if (mysqli_num_rows($sg_search) > 0) {
                ?>
<div id="snowglobe_list">
<?php while ($sg_snipe = mysqli_fetch_assoc($sg_search)) {
                    ?>



<div class="box snowglobe_div"><h4><a href="<?php echo $main_dir . "sg/" . $sg_snipe['sg_url'];
                    ?>"><?php echo $sg_snipe['sg_name'];
                    ?></a> created  <?php echo $swiss_army -> time_rounds($sg_snipe['creation_date']);
                    ?></h4>

<p class="desc"><?php echo (empty($sg_snipe['description'])) ? $nx['48'] : $sg_snipe['description'];
                    ?></p>   
<p class="misc"><?php echo $sg_snipe['followers'];
                    ?> subscriber<?php echo ($sg_snipe['followers'] > 1) ? "s" : "";
                    ?> &middot; <?php echo $sg_snipe['no_threads'] . " threads, " . $sg_snipe['no_replies'] . " replies" ?></p>

<?php if ($sg_snipe['is_finished_being_modified'] == 0): ?>
<p class="misc"><?php echo "(Unfinished Editing) <a href='edit_snowglobe:" . $sg_snipe['sg_url'] . "' class='button_samp prompt rad'>FINISH EDITING</a>";
                    ?></p> 
<?php endif;
                    ?>                         
</div>



<?php } 
                ?>

</div><?php } else {
                ?>

<?php } 
            ?>
</div>

<div class="contentbox new_sg">
<h3 class="dashes bottom"><?php echo $nx['47']; //Snowglobes. Obviously we're going to have the person's profile at least
            ?></h3>
<div class="space">
<form action="<?php echo $main_dir . "profile_nuise/" . $_MONITORED['login_q'] . "/find/snowglobes/submit";
            ?>" method="POST">
<table>
<!-- <tr><td width="25%"></td><td></td></tr> -->
<tr><td width="25%">Snowglobe name</td><td><input type="text" class="largeform flick" value="Name must be at least 4 characters." name="sg_name"></td></tr>
<tr><td width="25%">Snowglobe URL</td><td><input type="text" class="largeform flick" value="Will be accessed via http://url.url/sg/{your input here}. Must be at least 4 characters." name="sg_url"></td></tr>

<tr><td width="25%">Snowglobe description</td><td><textarea class="largeform flick" name="sg_desc">(Can be left blank)</textarea></td></tr>
<tr><td width="25%">Privacy settings</td><td><input type="radio" name="sg_privacy" value="private" checked> Make this snowglobe only accessible to people who are invited in <input type="radio" name="sg_privacy" value="public"> Make the snowglobe accessible to everyone </td></tr>
</table>
<p class="right"><input type="submit" value="CREATE A NEW SNOWGLOBE!"> </p>
</form>
</div>

</div>


<?php
            
            break;
            } 
        } 
    
    } 

?>