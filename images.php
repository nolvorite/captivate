<?php


$db_main = mysqli_connect("localhost","root","","captiv8");      
mysqli_set_charset($db_main,"ISO-8859-1");

date_default_timezone_set('America/Chicago');   

require("core/auxiliary.php");    

if(isset($_GET['src'])):

$get_image_data = mysqli_query($db_main,"SELECT * FROM images WHERE url_hash='$_FILTERED[src]'");
if(mysqli_num_rows($get_image_data) == 1){


$actual_image_data = mysqli_fetch_assoc($get_image_data);     
header("Content-type:image/".$actual_image_data['type']);
header('Content-Disposition: inline; filename="'.$actual_image_data['url_hash'].'.'.$actual_image_data["type"].'');

echo $actual_image_data['image_blob'];

}

endif;
                                                                  
                                                                  

                                                                  ?>