<?php
    
    $db_main = mysqli_connect("localhost","root","");   
    mysqli_query($db_main,"CREATE DATABASE test2");
    $db_main2 = mysqli_connect("localhost","root","","test2");   
    $templine='';
              $lines = file("captiv8.sql");
// Loop through each line
$sqlSource = file_get_contents('captiv8.sql');

mysqli_multi_query($db_main2,$sqlSource);

 echo "Tables imported successfully";
?>