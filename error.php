<?php 
    for($i = 1;$i<=100;$i++){
        $buzzes = [($i % 3 == 0) ? "Fizz" : "",($i % 5 == 0) ? "Buzz" : ""];
        echo $i. ". " .$buzzes[0] . $buzzes[1] . "<br>";
    }   
?>