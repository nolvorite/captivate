<?php if(isset($_SESSION['login_q']) && compare_dz($logged_dt['salt'],$_SESSION['salt_q'])): ?>  

<?php if(count($_GET) == 1 && isset($_GET['query'])): ?> 

if($(this).hasClass("chop")){  
    search_val = $(this).val();      
    $(this).parent().next().load("<?php echo main_dir; ?>core/simcheck.php?fetch=class_sg&school_id="+ $(this).parent().next().attr('school_id') +"&extra_name=" + encodeURIComponent(search_val))
}

if($(this).hasClass("school_search")){    
//I need to make the wait a certain amount of time after a person is done typing before displaying results
//In this case i'll set it to 3/4 of a second
//seems like there's already a jquery function for this but im not sure                
//or I could have just nulled the settimeout function before the last time it was executed
    event.stopPropagation();    
    if($(this).next().is("div")){   // $(this).next("#spark").html($(this).val());         
 $(this).next("div").load("<?php echo main_dir; ?>core/simcheck.php?action=school_search&criteria="+encodeURIComponent($(this).parent().prev().text())+"&search_q=" +encodeURIComponent($(this).val()));
}else{
$(this).after("<div></div>");    }
}
 <?php endif; ?>                                                  
  
   <?php endif; ?>