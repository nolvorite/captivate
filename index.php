<?php      

 session_start(); $_SESSION['page_load_time'] = microtime(true);
ob_start();       



/**
 * NOTICE: if you want to modify files in here, here's the file tree in terms of file inclusions(that's been require_once()d) with in the each's respective source codes, and also what is contained inside it
 * 
 * Edit April 29: I'll update this later.
 * 
 * index.php
 * nd1.php - everything inside <head>
 * -js.php - Javascript files
 * nd2.php - everything inside <body>
 * -idx_1.php - profile view, post view, etc
 * -idx_2.php -
 */

require_once("core/internal.php"); // form actions, database registering, etc cetera, also site configurations -- the main hub, I guess
      

echo "<!DOCTYPE html><html>";
echo "<head>";
require_once("core/nd1.php"); 
echo "</head>";
/**
 * end html header, start actual content
 */
echo "<body>";
require_once("core/nd2.php"); 
// the most hip admin panel ever
$_SESSION['page_load_time'] = microtime(true) - $_SESSION["page_load_time"] . "ms";
require_once("core/admin_panel.php");
echo "</body>";
echo "</html>";


/**
 * end actual content
 */
$swiss_army->clear_array($_SESSION, "error");

// session_destroy();
if (isset($_SESSION['login_q']) && isset($_SESSION['salt_q'])) {
    mysqli_free_result($logged_query);
} 
                          #8B9097
if (isset($_COOKIE['limbooo'][1])) {
    for($bm = 0;$bm <= count($_COOKIE['limbooo'])-1;$bm++) {
        setcookie("limbooo[" . $bm . "]", "a", time()-1);
         } 
    }
    
 //   var_dump($_SESSION);
    


?>
