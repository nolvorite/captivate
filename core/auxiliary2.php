<?php

require_once("server_info_privilege_checks.php");

// All user data needed in other php files, sanitized variables, time formatting, etc. goes here.
// also functions that need to be embedded in all the files
// June 16 edit: all this shit should be in one class lol. Or at least 2-3 hierchially extended to each other
if ( isset( $_SESSION['login_q'] ) && isset( $_SESSION['salt_q'] ) ) {
				$logged_query = mysqli_query( $db_main, "SELECT userid,salt,day2,month2,year2,tabbed_users,misc_prefs,fullname,login_att_last FROM users WHERE username='$_SESSION[login_q]'" );
				$logged_dt = mysqli_fetch_assoc( $logged_query );
				// any way to exclude just the password hash from column selection?
} 

require_once( "lib/simple_html_dom.php" );

if ( isset( $_GET['error_reporting'] ) && $_GET['error_reporting'] == "false" ) { // This would be helpful for all get
				error_reporting( ~E_ALL );
				}
        
class swiss_army extends db_checks {
public function merge( &$array_to_merge_in, &$merge_object, $placement_method = "prepend", $override = true )
{
				
				if ( isset( $array_to_merge_in ) ) {
								if ( $override = true || ( $override = false && !isset( $merge_object ) ) ) {
												switch ( $placement_method ) {
																default:
																case "prepend":
																				$array_to_merge_in = array_merge( $merge_object, $array_to_merge_in );
																				break;
																case "append":
																				$array_to_merge_in = array_merge( $array_to_merge_in, $merge_object );
																				break;
																				
																				} 
												
												} 
								
								} else {
								
								$array_to_merge_in = ( !is_array( $merge_object ) ) ? [$merge_object] : $merge_object; //turn strings into array with the string being first in the array
								
								
								} 
				
				} 
public function blank_parse( &$queued_var )
{
				 return ( isset( $queued_var ) ) ? $queued_var : "";
				}   
        
public function verify_var( &$array_in_question )
{
				// for the wikipedia data entries which have different field names for certain entries, like a location/coordinates,v
				// all arrays that you're going to put in reference here has to have an email sign before it to remove error notices
				foreach( $array_in_question as $variable ) {
								if ( isset( $variable ) ) {
												return $variable; //first variable that exists will be the return value
												} 
								} 
				}
public function extraurl()
{
				if ( strlen( $_SERVER['QUERY_STRING'] ) > 0 ) {
								$snp = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
				} else {
								$snp = $_SERVER['PHP_SELF'];
				} 
				return $snp;
				}
public function sql_filter($x)
{
				global $db_main;
				return mysqli_real_escape_string( $db_main, $x );
				}
        
public function approp_s( $n )
{
				return ( $n === 0 || $n > 1 ) ? "s" : "";
				}               
        

public function url_friendly( $dt )
{
				 $yy = $dt;
				 return strtolower( preg_replace( "#-{2,}#", "-", preg_replace( "#[^A-Za-z0-9_]#", "-", $yy ) ) );
				}
        
public function search_for_string( &$array_ref, $search_string = "" )
{
				 if ( ( is_array( $array_ref ) && in_array( $search_string, $array_ref ) ) ||
												 ( !is_array( $array_ref ) && preg_match( "#" . preg_quote( $search_string ) . "#", $array_ref ) ) ) {
								return true;
								 } 
				return false;
				}
        
public function filter_sql_queries( &$array, $order )
{
				 // have to check for sub-arrays and just strings
				if ( is_array( $array ) ) {
								foreach( $array as $key => $val ) {
												if ( ( !is_array( $val ) && $order == "after" ) || ( is_array( $val ) && ( array_search( $order, $val ) !== false ) ) ) {
																$filtered[$key] = is_array( $val ) ? $val[0] : $val;
																 } 
												} 
								} 
				else {
								return false;
								 } 
				return $filtered;
				}
        
public function clear_array( &$listof, $search = "", $value_search = "" )
{
				// this was really ambiguous when I made it, but then again so were most other things
				// this clears all functions where the keys match a regex pattern
				if ( is_array( $listof ) ) {
								foreach( $listof as $key => $value ) {
												if ( strlen( $search ) > 0 || strlen( $value_search ) > 0 ) {
																
																if ( preg_match( "#^" . $search . "#", $key ) ) {
																				unset( $listof[$key] );
																} 
																} 
								} 
								} 
}

public function compare_dz( $a, $b )
{
				// nanosecond timing attack prevention
				// because no password_compare() in PHP 5.4
        // now in 5.6 but w/e
				if ( strlen( $a ) !== strlen( $b ) ) {
								return false;
								 } 
				$result = 0;
				 for ( $i = 0; $i < strlen( $a ); $i++ ) {
								$result |= ord( $a[$i] ) ^ ord( $b[$i] );
								 } 
				return $result === 0;
				}
        
public function time_rounds( $date )
{
				$diff = microtime(true) - strtotime( $date );
				if ( $diff < 60 ) {								
						 	$date2 = "Less than a minute ago";
								} 
				if ( $diff < 3600 && $diff > 59 ) {
								$scheck = $this->plural( floor( $diff / ( 3600 * 24 ) ) );
								$date2 = floor( $diff / ( 60 ) ) . " minute" . $this->plural( floor( $diff / ( 60 ) ) ) . " ago";
								} 
				if ( $diff < 3600 * 24 && $diff > 3599 ) {
								$scheck = $this->plural( floor( $diff / ( 3600 * 24 ) ) );
								$date2 = floor( $diff / ( 3600 ) ) . " hour" . $this->plural( floor( $diff / ( 3600 ) ) ) . " ago";
								} 
				if ( $diff < 3600 * 24 * 7 && $diff > ( 3600 * 24 )-1 ) {
								$scheck = $this->plural( floor( $diff / ( 3600 * 24 ) ) );
								$date2 = floor( $diff / ( 3600 * 24 ) ) . " day" . $scheck . " ago";
								} 
				if ( $diff < 3600 * 24 * 30.46 && $diff > ( 3600 * 24 * 7 )-1 ) {
								$scheck = $this->plural( floor( $diff / ( 3600 * 24 * 7 ) ) );
								$date2 = floor( $diff / ( 3600 * 24 * 7 ) ) . " week" . $scheck . " ago";
								} 
				if ( $diff < 3600 * 24 * 7 * 365 && $diff > ( 3600 * 24 * 30.46 )-1 ) {
								$scheck = $this->plural( floor( $diff / ( 3600 * 24 * 30 ) ) );
								$date2 = floor( $diff / ( 3600 * 24 * 30 ) ) . " month" . $scheck . " ago";
								} 
				if ( $diff > ( 3600 * 24 * 7 * 365 )-1 ) {
								$scheck = $this->plural( floor( $diff / ( 3600 * 24 ) ) );
								$date2 = floor( $diff / ( 3600 * 24 * 7 * 365 ) ) . " year" . $scheck . " ago";
								} 
				return $date2;
				} 
public function plural( $s )
{
				if ( $s > 1 ) {
								return "s";
				} 
				}   
        
        //
        //
        //
        //end class 
}

class lean_machines extends swiss_army {
public function redir_process( $url )
{
				global $allow_redirs, $stopping_point;
				$egg = "";
				if ( isset( $stopping_point ) ) {
								$preg_test = preg_match( "#" . $stopping_point . "#", $_SERVER['QUERY_STRING'] );
								if ( $preg_test === 1 ) {
												unset( $egg );
								} 
								} 
				
				if ( $allow_redirs === true && isset( $egg ) ) {
								header( $url );
								exit;
				} 
				}
        
         
}
$error = "<div class='contentbox'> <h3>SQL error</h3><p>" . mysqli_error( $db_main ) . "</p></div>"; 
$swiss_army = new swiss_army;
$lean_machines = new lean_machines;

function hack_free(&$data,$extras = ""){   //yeah not making this object-inheritable lol
global $swiss_army;

    if(strlen($extras) > 0){
        $extras = preg_split("#,#",$extras);
    }     
    global $db_main;
    if(is_array($data)){ //...
        array_map("hack_free",$data);  //how did I not know about this function?
    }
    else{
        /*le filters*/

        $data = !$swiss_army->search_for_string($extras,"htmlspecialchars") ? htmlspecialchars($data) : $data;       
        /* posting format code goes here */
        $data = $swiss_army->search_for_string($extras,"break-lines") ? str_replace("\n","<br>",$data) : $data;
        //that was bizzare
        /*and then filters again*/
        $data = !$swiss_army->search_for_string($extras,"mysqli_real_escape_string") ? mysqli_real_escape_string($db_main,$data) : $data;
        $data = !$swiss_army->search_for_string($extras,"stripslashes") ? stripslashes($data) : $data;
        $data = !$swiss_army->search_for_string($extras,"trim") ? trim($data) : $data;
      
    }
    return $data;
}







$time = [
'now' => time(),
'precise' => microtime( true ),
'necessary' => microtime()
];


 




 

function key_associations( $key_names, $is_one_to_others = null ) // this limits which GET parameters you can have on any page to only appropriate associations.
{
				// this is so tacky
				global $main_dir;
				
				$key_names = explode( ",", $key_names );
				foreach( $_GET as $keys => $values ) {
								$key_params[] = $keys;
				} 
				$matches = array_intersect( $key_params, $key_names );
				if ( count( $matches ) > 0 ) {
								foreach( $key_params as $keys ) {
												if ( in_array( $keys, $key_names ) == false ) {
																$lean_machines->redir_process( "Location: " . $main_dir );
												} 
												} 
								} 
				unset( $key_params );
				} 

 



 



 
if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
				foreach( $_POST as $key => $room ) {
								$z = isset( $z ) ? $z + 1 : 0;
								$copy = $_POST[$key];
								
								$_SPIN[$key] = hack_free( $copy, "break-lines,stripslashes" );
								$_DATA[$key] = $_SPIN[$key]; //don't know why I have two different copies
								$clone[$z] = $_SPIN[$key]; //increment array key
								 // lol spin and data are essentially the same thing
				} 
				
				
				} 

if ( isset( $_SESSION ) ) {
				foreach( $_SESSION as $mkey => $mvalue ) { // reiterate each session and array
								$_MONITORED[$mkey] = hack_free( $mvalue );
								// $_MONITORED[$mkey] = mysqli_real_escape_string($db_main, $_MONITORED[$mkey]);
				} 
				} 
foreach( $_GET as $mkey => $mvalue ) {
				$_FILTERED[$mkey] = hack_free( $mvalue );
				} 



$z = 0; 
function chat_list_q( $status, $user ) // get list of users
{
				// search for a user's current tabbed users
				global $db_main, $_MONITORED;
				$logged_dt_again = mysqli_query( $db_main, "SELECT tabbed_users FROM users WHERE username='$_MONITORED[login_q]'" );
				$logged_dt_c = mysqli_fetch_assoc( $logged_dt_again );
				$logged_dt_c = ( $logged_dt_c['tabbed_users'] !== "[]" ) ? json_decode( $logged_dt_c['tabbed_users'] ) : "";
				// json_decode it
				
				
				switch ( $status ) {
								
								case "open":
												// update it
												if ( count( $logged_dt_c ) < 5 ) { // check if it's empty and that there's less than 5 of it in
																$logged_dt_c = ( is_array( $logged_dt_c ) && array_search( $user, $logged_dt_c ) === false ) ? array_merge( $logged_dt_c, [$user] ) : [$user]; //the latter condition is there to prevent name duplicates
																} 
												break;
								
								case "close":
												unset( $logged_dt_c[array_search( $user, $logged_dt_c )] ); //i'm very lazy
												break;
												} 
				$logged_dt_c = json_encode( $logged_dt_c );
				
				// json_encode it back, and reupload to server the new information
				$update_q = mysqli_query( $db_main, "UPDATE users SET tabbed_users='$logged_dt_c' WHERE username='$_MONITORED[login_q]'" );
				
				mysqli_free_result( $logged_dt_again );
				
				} 

function get_posts( $pid_call, $tid_call, $limit = "25", $chain = "5", $special_select = "none", $show_more = "no" )
{
				 // first, sort them out by hierarchial position
				// then date
				// chain is the number of comment ancestor-generations?(after the 1st child comment ancestor-generations?) you want to display
				// ...post children generations?
				global $db_main, $thread_data, $original_post, $fill_data;
				 if ( $special_select == "none" ) {
								$q_req = mysqli_query( $db_main, "SELECT * FROM posts WHERE parent='$pid_call' AND thread_id='$tid_call' 
         ORDER BY stamptime DESC LIMIT 0,$limit" );
								 $x = $chain;
								 if ( mysqli_num_rows( $q_req ) > 0 && $x > 0 ) {
												while ( $q_dt = mysqli_fetch_assoc( $q_req ) ) {
																$margins = ( $special_select === "none" ) ? array( "<div class='margin'>", "</div>" ) : array( "", "" );
																 echo $margins[0];
																 $inception_q = mysqli_query( $db_main, "SELECT * FROM posts WHERE parent='" . $q_dt['postid'] . "' 
                 AND thread_id='$tid_call' ORDER BY stamptime DESC LIMIT 0,$limit" ) or die( mysqli_error( $db_main ) );
																 if ( mysqli_num_rows( $inception_q ) > 0 && $x === 1 ) {
																				$q_dt['more_replies_notice'] = true;
																				 } 
																$fill_data -> show_reply( $q_dt );
																 if ( mysqli_num_rows( $inception_q ) > 0 ) {
																				get_posts( $q_dt['postid'], $tid_call, "25", $chain-1 );
																				 } 
																echo $margins[1];
																 } 
												} 
								if ( $q_req ) {
												mysqli_free_result( $q_req );
								} 
								} 
				else {
								// last solution, and then i'm gonna contemplate ragequitting
								// moral of the story? Never use recursive functions unless you clearly know your parameters.
								// get selected comment
								$get_selected_comment = mysqli_query( $db_main, "SELECT * FROM posts WHERE postid=$special_select AND thread_id='$thread_data[postid]'" );
								 if ( mysqli_num_rows( $get_selected_comment ) == "1" ) {
												$q_dt = mysqli_fetch_assoc( $get_selected_comment );
												 $get_comments_above_selected = mysqli_query( $db_main, "SELECT * FROM posts WHERE postid='$q_dt[parent]' 
            AND thread_id='$thread_data[postid]'" );
												 if ( mysqli_num_rows( $get_comments_above_selected ) > 0 ) {
																$q_parents = mysqli_fetch_assoc( $get_comments_above_selected );
																 } 
												else {
																$lean_machines->redir_process( "Location:" . $main_dir . "index.php" );
																 $_SESSION['error' . rand( 56, 1515 )] = $swiss_army->extraurl();
																 } 
												} 
								else {
												$lean_machines->redir_process( "Location:" . $main_dir . "index.php" );
												 $_SESSION['error' . rand( 56, 1515 )] = $swiss_army->extraurl();
												 } 
								// get all child posts of single-referred-post comment
								$get_child_comments = mysqli_query( $db_main, "SELECT * FROM posts WHERE parent='$_SESSION[original_post_query]' 
        AND thread_id='$thread_data[postid]' ORDER BY stamptime DESC LIMIT 0,25" );
								 if ( mysqli_num_rows( $get_child_comments ) > 0 ) {
												echo mysqli_num_rows( $get_child_comments );
												 while ( $sync_children = mysqli_fetch_assoc( $get_child_comments ) ) {
																get_posts( $sync_children['postid'], $thread_data['postid'] );
																 } 
												mysqli_free_result( $get_child_comments );
												 } 
								} //end function
				} 
function show_parents( $queue, $parent, $parent_comments = "5" )
{
				 global $db_main, $thread_data, $nay, $fill_data, $x;
				 $show_1 = mysqli_query( $db_main, "SELECT * FROM posts WHERE postid='$parent' AND thread_id='$thread_data[postid]'" );
				 $tell_1 = ( mysqli_num_rows( $show_1 ) > 0 ) ? mysqli_fetch_assoc( $show_1 ) : "dead";
				 if ( $tell_1 !== "dead" && $_SESSION['reply_sync']['count'] > 0 ) {
								$_SESSION['reply_sync']['count'] = $_SESSION['reply_sync']['count'] - 1;
								 if ( $_SESSION['reply_sync']['count'] === 0 ) $_SESSION['reply_sync']['has_more_parents'] = "set";
								 $_SESSION['reply_sync']['incr'] = $_SESSION['reply_sync']['incr'] + 1;
								 // remember, it has to be limited to 5 ancestor generations
								$show_2 = mysqli_query( $db_main, "SELECT * FROM posts WHERE postid='$tell_1[parent]' AND thread_id='$thread_data[postid]'" );
								 $tell_2 = ( mysqli_num_rows( $show_2 ) > 0 ) ? mysqli_fetch_assoc( $show_2 ) : "dead";
								 if ( $tell_2 !== "dead" && $_SESSION['reply_sync']['count'] > 0 ) {
												$_SESSION['reply_sync']['count'] = $_SESSION['reply_sync']['count'] - 1;
												 if ( $_SESSION['reply_sync']['count'] === 0 ) $_SESSION['reply_sync']['has_more_parents'] = "set";
												 $_SESSION['reply_sync']['incr'] = $_SESSION['reply_sync']['incr'] + 1;
												 if ( $tell_2['parent'] !== $thread_data['postid'] && !isset( $_SESSION['reply_sync']['has_more_parents'] ) ) {
																show_parents( $tell_2['postid'], $tell_2['parent'], $_SESSION['reply_sync']['count'] - 1 );
																 echo ( is_array( $tell_1 ) && !isset( $_SESSION['reply_sync']['has_more_parents'] ) ) ? "<div class='margin'>" : "";
																 if ( isset( $_SESSION['reply_sync']['has_more_parents'] ) ) unset( $_SESSION['reply_sync']['has_more_parents'] );
																 } 
												$fill_data -> show_reply( $tell_2 );
												 } 
								echo ( is_array( $tell_2 ) && !isset( $_SESSION['reply_sync']['has_more_parents'] ) ) ? "<div class='margin'>" : "";
								 if ( isset( $_SESSION['reply_sync']['has_more_parents'] ) ) unset( $_SESSION['reply_sync']['has_more_parents'] );
								 $fill_data -> show_reply( $tell_1 );
								 mysqli_free_result( $show_2 );
								 } 
				mysqli_free_result( $show_1 );
				 // end show_parents();
} 

if(!isset($_GET['get_more'])){ //news_feed.php needs it
    if(!isset($_GET['verify'])){        
        $_SESSION['temp_n'] = session_hash
        // microtime(true)
        ;//FIXED finally wooo
    }
                                                  
    
}




?>