                                                                                
<script type="text/javascript"> 

$(window).on("load",function(){     
                  
//js that only works when you put it here because page has to be statically loaded first to get these information  

$(".content p img,#thread_main p img").each(function(){
    
         i_w = $(this).width();
         i_h = $(this).height();
         $(this).attr({"w":i_w,"h":i_h}).addClass('clear')
         
         
});

$(".content p img,#thread_main p img").each(function(){
    
    c_w = $(this).parent().width();      

    $(this).attr({"c":c_w});  
 //   $(this).css("display","block-inline");
 
 ///
           
        img_width = parseInt($(this).attr("w"));
        img_height = parseInt($(this).attr("h"));
        cell_width = parseInt($(this).attr("c"));
       
        if(img_width > cell_width){ 
            pct = cell_width / img_width;
            height_fix = pct * img_height;
            img_src = $(this).attr("src")
            $(this).removeClass('clear');
            $(this).css({"width":cell_width+"px","height":height_fix+"px"});
            $(this).before("<?php echo $nx['85']; ?>")

        }
    });  
    
    $("#body").attr("id"," ");
    
});   
                                     
<?php  if(news_feed_check){ include(main_dir ."core/index_feed_js.php");} 
 //include(main_dir ."core/index_feed_js.php");
?>    

<?php if(isset($logged_dt)) include(main_dir ."core/profile_edit.js");?>   

<?php if(isset($_GET['snowglobe'])): ?>

$.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"get_sg_follow_status","name":"<?php echo $_FILTERED['snowglobe']; ?>"}).done(function(html){

$("#sg_header .header_etc").append(html);

});

<?php endif; ?>  

$("#post_k input[type=submit]").click(function(){
if($("#post_k input[name=tcha1]").val() == $("#post_k input[name=tcha1]").prop("defaultValue")){return false;alert('Please provide a title.')}else{
if($("#post_k textarea[name=tcha2]").val() == $("#post_k textarea[name=tcha2]").prop("defaultValue")){$("#post_k textarea[name=tcha2]").val(" ");}
}
})   
$("span.signup input, span.signup select, button#axe1check").bind('keyup input', function(){$("#checkn1").load("<?php echo main_dir; ?>core/simcheck.php", {"search_1[]": [ $(this).attr('name') , $(this).val() ] });  });   
$("span.signup input, span.signup select, button#axe1check").bind('keyup focus mouseover', function(){ 
$.get('core/simcheck.php',{"availability":$("input[name=username2]").val()},function(data){ 
if(!data){nodoppels = "correct";}else{nodoppels = "wrong"}
});                //must have taken me a whole hour and a half to sort out all the regex/syntax problems on this whole if statement. good grief
if(/^[A-Za-z0-9-_]{4,16}$/.test($("span.signup input[name=username2]").val())
&& /^(.){10,50}$/.test($("span.signup input[name=pwrd2]").val() )  
&& /^([-_A-Za-z0-9]){1,30}[@](([-_A-Za-z0-9]){1,100}[.])+([-_A-Za-z0-9]){1,100}[.]*(([-_A-Za-z0-9]){1,100})+$/.test($("span.signup input[name=email2]").val() ) 
&& /^([A-Za-z-]{2,})+[ ]([A-Za-z-]{2,}[ ]){0,2}([A-Za-z-]{2,})+$/.test($("span.signup input[name=fullname2]").val()) 
&& $("span.signup input[name=fullname2]").val() != $("span.signup input[name=fullname2]").prop("defaultValue") 
&& $("select[name=day2]").val() != "0" && $("select[name=month2]").val() != "0" && $("select[name=year2]").val() != "0" && $("button#axe1check").hasClass("a2") && nodoppels == "correct"
)
{ 
$("span.signup input[type=submit]").addClass("signup-a").val("Continue with registration");   $("form").unbind()    


}else{ $("form").on('submit', function(mod){ 
mod.preventDefault();
$('div#part_one').before("<div class='notice'><h3>Notice</h3><p>Please correct some incorrect data.</p></div>");$(".notice:not(':first')").hide()


})
$("span.signup input[type=submit]").removeClass("signup-a").val('Register');}                                                        
}).each(function(){

if($(this).prop("defaultValue") == "Desired username"){$(this).tooltip({position: { my: "left+10 center", at: "right center-1" }, content: "<span id='checkn1'>Must be between 4-16 characters, numbers, letters and hyphens only (also an underscore)</span>"})}
if($(this).prop("defaultValue") == "Your password"){$(this).tooltip({position: { my: "left+10 center", at: "right center-1"}, content: "<span id='checkn1'>Must be at least 10 characters.</span>"})}
if($(this).prop("defaultValue") == "Your email address"){$(this).tooltip({position: { my: "left+10 center", at: "right center-1"}, content: "<span id='checkn1'>Just the normal email address.</span>"})}
if($(this).prop("defaultValue") == "Your full name"){$(this).tooltip({position: { my: "left+10 center", at: "right center-1" }, content: "<span id='checkn1'>We just need a first and last name.</span>"})}
});


$("input[name=sg_url]").bind("keyup input", function(){

if(/^[-_A-Za-z0-9]{4,30}$/.test($(this).val())){
msg_box = ($(this).next(".msg_notiff").length == 0) ? "<div class='msg_notiff'></div>" : " ";

$(this).after(msg_box);
$.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"sg_url_avail","test":$(this).val()}).done(function(check_msg){
$("input[name=sg_url]").next().html(check_msg);
});
}else{

if($(this).val() == $(this).prop("defaultValue") || $(this).val() == ""){$(".msg_notiff").remove();  }else{
//validation issuessssssss
if(/^(.+){4,30}$/.test($(this).val())){
$(this).next().html("<div class='notice'>You can only have letters, numbers, hyphens and underscores (- and _ respectively) for the URL.</div>");
}
}

}

});

$(".new_sg input[type=submit]").click(function(){
if($("textarea[name=sg_desc]").val() == $("textarea[name=sg_desc]").prop("defaultValue")){
$("textarea[name=sg_desc]").val("");
}
})

sessionStorage.clear();

$("#snowglobe_opts input[type=checkbox]").each(function(){$(this).wrap("<div class='optionbox' />").after("<span class='text'>"+ $(this).attr('snowglobe') +"</span>")});
$("#snowglobe_opts .optionbox:last").css("background-image","none");      
        
</script>           

<div id="message_panel" class="clear">
<h2>Messages <a href="close_msgs_panel" class="prompt">[Close]</a></h2>
<div id="loaded_msgs">
<div id="chat_history_list"></div>
</div>
</div>

<div id="scroll_button">
&uarr; Scroll Back Up
</div>                   