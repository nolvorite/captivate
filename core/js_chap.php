<?php  
$loc = "jsapp";
require_once("headers_for_files.php");
unset($loc);
?>







                                            

$("head").ready(function(){   //gets executed faster than $(document).ready()





//no background image on last child
$(".quick_links a:last-child").css("background-image","none");


$("#user_menu .uplink").append("<img src='<?php echo image_dir; ?>9.png' alt='\(Options\)'>");
$(".posts_t1:first").addClass("nobg");
//everything that needs 3-part backgrounds
$(".profile_main,h3.content_q").wrapInner("<div class='part_1'><div class='part_2'><div class='part_3'></div></div></div>"); 

//lazy dropdowns
//this time I hope to make it even more fluid, making a parent span element the trigger to open the dropdown box
//can't have margins inbetween dropdown boxes and their respective menus, otherwise it'll trigger the mouseleave. span.drop's encapsulation in the area space is limited to objects that are connected, and margins separate the connection.

$("body").on('mouseover',".drop",function(){

var nina = $(this).find(".uplink");
var nina2 = nina.offset().left;
var nina3 = nina.offset().top + nina.height();
$(this).find(".dropdown_content").attr("style","left: "+nina2+"px; top: "+nina3+"px;display:block;position:absolute");

}).on('mouseleave',".drop",function(){

$(this).find(".dropdown_content").attr("style","display:none");
if($(this).is("#notifs_drop")){     //set notifications to read                                      

confirm_notifs();

}else{

$("#notifs_bar .spacer .note").empty();

zen3 = $("title").html();
zen3 = zen3.replace(/[\050]([0-9]{1,3})[\051][ ](.+)/,"$2");
$("title").html(zen3);


}
});



$("*[message]").each(function(){

class_inserts = (typeof $(this).attr("dd_class") !== "undefined") ? " "+ $(this).attr("dd_class") : "";


$(this).wrap("<span class='drop"+ class_inserts +"'><span class='uplink'></span></span>").parent().after("<div class='dropdown_content'><div class='spacer'>"+$(this).attr('message')+"</div></div>");
});

$("#user_menu .left,#user_menu .right,#user_menu .dropdown_content,#left1 div.box").wrapInner("<div class='spacer' />");


$("#post_content").hide();                  


            
                                  
//a whitespace, just for design's sake
$("#user_menu .left,#user_menu .right").each(function(){
if($(this).is(".left:first")){ //left will probably always be the first div but still
$(this).css("border-right","1px solid #26303F");
if($(this).next("div").is(":not('.left')") || $(this).is(":only-child")){
$(this).after("<div class='left boxxy'>&nbsp;</div>");
$(this).next("div.left").css("border-left","1px solid #38475D");
}  
}
if($(this).is(".right:last")){
$(this).css("border-left","1px solid #38475D");
if($(this).prev("div").is(":not('.right')") || $(this).is(":only-child")){
$(this).prev("<div class='right'>&nbsp;</div>");
$(this).prev("div.right").css("border-left","1px solid #26303F");
}  
}

if($(this).is(".left:not(':first')")){
$(this).css("border-left","1px solid #38475D");
if($(this).is(":not('.boxxy')")){
if($(this).parent().is("span.drop")){
$(this).parent().prev().find(".boxxy").detach();
}

}
}
                                      

}); 

//$("*[submit]").each(function(){   });
//might have to use it later

        
                                            
$("body").on('focus','.flick',function(){
if($(this).val()==$(this).prop("defaultValue")){
$(this).attr("extra",$(this).val()).val("");       
}}).on('blur','.flick',function(){if($(this).val().length === 0){
$(this).val($(this).prop("defaultValue"));
} }).on('change','.flick',function(){  

if($(this).val().length === 0 || $(this).val() === $(this).prop("defaultValue")){$(this).removeClass("named")}else{$(this).addClass("named")}
if($(this).val().length == 0){
$(this).val($(this).prop("defaultValue")); //had to change it to this for textareas
}}).on('keyup','.flick',function(event){
             
/* where school listing data is supposed to be*/
   

//add em'
});                
                
$("#first1").attr("extra",$("#first1").height()).addClass("lolbuffer").animate({height:$("#first1").attr('extra')},2200).removeClass("lolbuffer");
$("#close_button1").click(function(event){event.preventDefault();
$(this).parents("td#left1 div").detach();});  

$("span.signup input[type=text]").each(function(){
$(this).attr("title", " ");
});       

$("#admin_panel").on('drag',function(event){
positions = [$("#admin_panel").offset().top,$("#admin_panel").offset().left];

$(this).css("bottom","inherit");


         
}).on('dragstop',function(){$.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"drag_box","offsets":[positions[1],positions[0]]});  });
 
 
$("body").on('change', "select.largeform:has(.current)", function(){
if($(this).next().attr("disabled") !== undefined){
$(this).next().removeAttr("disabled");
}else{
$(this).next().attr("disabled","disabled").val("").trigger("change");
}
});     

$(".pt_toggle:last-child").filter(function(){
    if($(this).index() !== 0){return true;}
    else{return false;}
}
).addClass("no_ass");
      
<?php if(isset($_GET['snowglobe']) && (mysqli_num_rows($sg_data[1]) > 0)): ?>

original_pt_c = $("#pt_drop").html();  
$(".pt_toggle.selected").each(function(){    
    selected_pt_text = (typeof selected_pt_text === "string") ? selected_pt_text + ", " : ""; 
            selected_pt_text = ($(this).find("span").length === 0) ? selected_pt_text + "" + $(this).text() : selected_pt_text + "" + $(this).find("span").text();
});                                     
$("#pt_drop").html(original_pt_c + " " +selected_pt_text);
<?php endif; ?>
                  
$("body").on('click',".prompt",function(event){event.preventDefault();       //dynamic client/client+server interaction with a click

<?php if(isset($_SESSION['login_q'])): ?>    //IT'S HERE   

   /* all school-stuff
if($(this).attr("value") == "SEARCH SCHOOL"){  
$values = [[$(this).prev("input").prop("defaultValue"),$(this).prev("input").val()],[$(this).prev().prev("input").prop("defaultValue"),$(this).prev().prev("input").val()]]; 
$zenx = [($values[0][0] == $values[0][1]) ? " " : $values[0][1],($values[1][0] == $values[1][1]) ? " " : $values[1][1]];
  $(this).after(function(){if($(this).next().is("div") == true){return false;}else{return "<div id='school_search' class='contentbox'>Loading...</div>"}});
  $(this).next("div").html("Loading...").addClass("contentbox");
$.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"wikipedia_search","data":[$zenx[1],$zenx[0]]},function(data){
$(".prompt[value='SEARCH SCHOOL']").next("div").removeClass("contentbox").html(data);
});
}

if($(this).attr("value") == "Complete Details"){       i = -1;
s_layer = $(this).attr("id");   zeus = new Array();        
$("."+s_layer).each(function(){ i++;//isn't that fun 
zeus[i] = $(this).attr("name") + " : " + $(this).val();     
});   i = 0;   
$.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"complete_details","data":zeus}).done(function(msg){ alert(msg); 
if(msg == "<?php echo $nx['38']; ?>"){
window.location.assign("<?php echo main_dir;?>profile_nuise/<?php echo isset($_SESSION['login_q']) ? $_SESSION['login_q'] : ''; ?>");         
}
});
} */       

switch($(this).attr("href")){
    case "show-comments":   
        t_id = $(this).attr("postid");
        if($(this).hasClass("opened_pandora")){
            $("#r_" + t_id).empty().detach();
        }
        else{
            $(this).parentsUntil(".content").addClass("ch_"+t_id);
            $(".ch_"+t_id).removeClass(function(){return ($(this).is("table")) ? false : "ch_"+t_id});  
            $table_rec = $(".ch_"+t_id).parent().parent();
            fixed_span = $table_rec.children("td").length - 3;
            colspan_toggle = (fixed_span > 1) ? " colspan='"+fixed_span+"'" : "";    
            $table_rec.find(".left_side,.right_side,.profile_disp").attr("rowspan","2");
            $table_rec.after("<tr id='r_"+t_id+"'><td class='comments'"+colspan_toggle+"><div class='c_box noselect' t_id='"+t_id+"'>Loading...</div></td></tr>");
        }
        $(this).toggleClass("opened_pandora").html(function(){
            t_val = $(this).html(); 
            t_val = !(/[\133]Close[\135]$/.test($(this).html())) ? t_val + " [Close]" : t_val.replace(/ [\133]Close[\135]$/,"");      
            return t_val;
        });
        $(".c_box[t_id="+t_id+"]").loadingtext().end();   //for dramatic pause
        setTimeout(function(){ 
            $(".c_box[t_id="+t_id+"]").loadingtext("[clear]").end().removeClass("noselect"); 
            $.get("<?php echo main_dir;?>core/simcheck.php",{fetch:"comments",postid:t_id},function(htmlx){
                $(".c_box[t_id="+t_id+"]").html(htmlx);
                $(".c_box[t_id="+t_id+"]").addClass("indent");
            });    
        },1000);  
                 
    break;
    
    <?php if(isset($_GET['query']) && $_GET['query'] === $_SESSION['login_q']):?> /*
    case "find-classes":
        if($("#black_overlay").length < 1){
            school_id = $(this).attr("school_id");                
            $("body").prepend($black_layer[0] + $black_layer[1]);
            $(".contentbox[s_id="+school_id+"]").appendTo("#widthfix");
            $.get("<?php echo main_dir;?>core/simcheck.php",{"fetch":"class_sg","school_id":school_id},function(list_disp){
                $(".load_classes[school_id='"+school_id+"']").html(list_disp);
            });
        }
    break;                                      */
    <?php endif; ?>
    case "sg_scha":
    if(typeof $(this).attr("sg") === "string"){
        $.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"sg_scha","sg":$(this).attr("sg")}).done(function(message){
                alert(message);
            });
        }
    break;
    case "select-school":
        $("#select4").removeAttr("id");
        $(this).attr("id","select4");
        if($(this).attr("datamine").length){
            $.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"confirm_school","data":$(this).attr("datamine")}).done(function(msg){
                $("#select4").parent().append(msg);
            });
        }
        else{
            $.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"confirm_school"}).done(function(msg){
                $("#select4").after(msg);
            });
        }
    break;
    case "confirm_school":
        $("#select4").removeAttr("id");
        $(this).attr("id","select4");
        $.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"confirm_school","name":$(this).attr("name")}).done(function(msg){
            $("#select4").after(msg);
        });
    break;
 /*   case "one-liner":
        $("#post_k input[name=tcha1]").val("<?php echo $nx['27'] ?>");
        $("#post_k textarea[name=tcha2]").toggle("200",function(){$(this).attr("style","display:block;box-shadow:0 0 6px #556378")})
        .removeClass("flick").val("<?php echo $nx['28'] ?>").end();  
        $("#pt_post_op").appendTo("#pt_opts");
    break;       */
    case "add-more-choices":
        if($("input[name=poll_choice]").length < 21 && $("input[name=poll_choice]").eq(-1).val() !== "Add a choice here"){
            $(this).before("<input type='text' class='largeform flick' value='Add a choice here' name='poll_choice'>");
        }
    break;
    case "add-friend":  
        $(this).addClass("greened");
        $.get("<?php echo main_dir; ?>core/simcheck.php",
        {"friend_status[]":[$("#check_friend_status").attr("ref1"),$("#check_friend_status").attr("ref2")],"action":"sg_req"},
        function(data){
            $("#check_friend_status").html(data);
        });   
    break;
    case "finished-poll-q":
        refs = "pass";
        for(i=0;i<=$("input[name=poll_choice]").length;i++){
            if(/^[ ]+$/.test($("input[name=poll_choice]").eq(i).val()) || $("input[name=poll_choice]").eq(i).val() == "Add a choice here"){
                refs = "fail";
            }
        }
        if(refs == "fail"){
            alert("One or more of the poll choices were left blank. Please correct them.");
        }
        else{
            //convert all form inputs to hidden type
            //append to post_k
            //close form
            //then i'm gonna have to do put it back here in case they decide to add/delete some stuff
            $("#content").insertBefore("form#post_k");
            if($("#input_save").html().length > 0){
                $("#input_save").empty();
            }
            $("#content input[type=text],#content input[type=checkbox]").each(function(){
                if($(this).attr("type") == "text"){
                    var order = ($(this).attr('name') == "poll_choice") ? $(this).attr('name') + ($(this).index()-2) : $(this).attr('name');
                    $("#input_save").prepend("<input type='hidden' name='"+ order +"' value='"+$(this).val()+"'>");
                }
                if($(this).attr("type") == "checkbox"){
                    $("#input_save").prepend("<input type='hidden' name='"+$(this).attr('name')+"' value='"+ $(this).prop('checked') +"'>");
                }
            });
            $("#black_overlay").empty().detach();
            $("#attach_poll_q").html("MANAGE ATTACHED POLL");
        }
    break;
    case "toggle":
        if($(this).parent().next().is(".spoiler")){$(this).parent().next(".spoiler").toggle();}
        if($(this).parent().parent().is("#first1")){
            $("#first1").toggle(); 
        }
    break;
    <?php if(isset($_GET['snowglobe'])): ?>
    case "select_of_p_type":
        sexy_conditional = (($(this).attr("id") == "0" && $(this).hasClass("selected") != true) || $(this).attr("id") != "0");
        if(sexy_conditional){
            //can't unselect the default option that way
            $(this).toggleClass("selected");
            //if a person unselects any post type toggle that's not "All Posts" and there's no other post type selected, 
            //revert it back to the default display
            set_values = {
                "pt_id": ($(this).attr("id") != "0" && $(".pt_toggle.selected").length == 0) ? "0" : parseInt($(this).attr("id"),0),
                "sg_id": parseInt($("#pt_drop").attr("sg_id"),0)                                                
            };
            $.get("<?php echo main_dir; ?>core/simcheck.php",
            {
                "action":"sg_view_save",
                "pt_id": set_values.pt_id,
                "sg_id": set_values.sg_id
            });
        }
        //parsing here turns out to be useless as PHP interprets them as strings anyway
        if($(this).attr("id") == "0" || ($(this).attr("id") != "0" && $(".pt_toggle.selected").length == 0)){
            $(".pt_toggle:not(.all_posts_q)").each(function(){
                $(this).removeClass("selected");
            });
        }else{
            $(".all_posts_q").removeClass("selected");
        }
        delete selected_pt_text; //reset the text
        $(".pt_toggle.selected").each(function(){
            selected_pt_text = (typeof selected_pt_text === "string") ? selected_pt_text+ ", " : "";   
            //append the post type titles
            selected_pt_text = ($(this).find("span").length === 0) ? selected_pt_text + "" + $(this).text() :
            selected_pt_text + "" + $(this).find("span").text();
        });
        if(sexy_conditional){   //return results
            $.get("<?php echo main_dir; ?>core/news_feed.php",
            {"get_more":"special","sg_id": parseInt($("#pt_drop").attr("sg_id"),0)},
            function(feed){
                $("#nf_encapsulated").html(feed);        
            }); 
        }                                    
        $("#pt_drop").html(original_pt_c + " " +selected_pt_text);        
    break;
    <?php endif; ?>
    
}



<?php if($swiss_army->compare_dz($logged_dt['salt'],$_SESSION['salt_q']) && $logged_dt['userid'] === "1"): ?>   //admin panel stuff
    if(/^opts[:]/.test($(this).attr("href"))){
        opt = $(this).attr("href").replace(/^opts:(.+)$/,"$1");   
        $(this).attr("id",opt);
        console.log("wtf");
        switch(opt){
            case "minimize":
                
                if(typeof $("#admin_panel").attr("minimized") === "string") {    
                    $("#admin_panel").removeAttr("minimized");
                    $("#"+opt).html("<?php echo $nx[81]; ?>");
                    $(".open").hide();
                    true_width = $("#panels").outerWidth() -10;
                    $(".open").attr('style',"width:"+true_width+"px");
                }
                else {
                    $("#admin_panel").attr("minimized","a");
                    $("#"+opt).html("<?php echo $nx[82]; ?>");
                }
                $.get("<?php echo main_dir;?>core/simcheck.php",{"action":"admin_opts","req":opt});
            break;
            case "drag":
                toggle = (typeof toggle === "undefined" || toggle == "1") ?  0 : 1;
                if(toggle == 0){
                    $("#admin_panel").draggable("enable"); $("#admin_panel").addClass("draggy");
                    $(this).addClass("selected_plink");
                }
                else{$("#admin_panel").draggable("disable");$("#admin_panel").removeClass("draggy"); 
                    $(this).removeClass("selected_plink");
                }
                z = $(this).html();
                $(this).html(function(){  
                    return_value = ($("#admin_panel").hasClass("draggy")) ? "<?php echo $nx[84]; ?>" : "<?php echo $nx[83]; ?>";
                    return return_value;
                });
            break;
        }                               
    }
    if(/^edit[:]/g.test($(this).attr("href"))){    //murder murder murder murrdurrrrrrrrrrrrrrrrr murrrrrrdurrrrrrrrr
        //FINALLY. holy shit mother of god tweaking batman
        $zen_0 = $(this).attr('href');     
        $zen_1 = $(this).attr('href');                
        $zen_1 = $zen_1.replace(/^edit[:](.+)$/,"$1");
        $.get($zen_1).done(function(css){
            $("link[href='" +$zen_1+"']").remove();
            //modify directory file at url('')'s
            $snick = css;
            if($("head style[title='"+$zen_1+"']").length === 0){     
                $("head").append("<style title='"+$zen_1+"'>"+ $snick +"</style>");  
            }
            if($("#jones").length === 0){  
            $("a[href='"+$zen_0+"']").addClass("selected2")
            .after("<a href='submit-edit' class='link_view link_view2 rad prompt finish_button' submit-type='css'>Finish editing</a><textarea id='jones' title='"+$zen_1+"' style='height:500px;margin-top:5px'></textarea>");
            //hmm...     
            $("#jones").val($("style[title='"+$zen_1+"']").html());              
            }
        }); 
    }
switch($(this).attr("href")){
    case "submit-edit":
        switch($(this).attr("submit-type")){
             case "css":
                  $.post("<?php echo main_dir; ?>core/simcheck.php?action=css_edit",
                  {"data":$("#jones").val(),"file":$("#jones").attr("title")},
                  function(data){
                      if(data.notice == "success"){alert("Successfully edited file!");}                                                                       
                  },
                  "json");  
             break; 
             case "admin_notes":
                  $.post("<?php echo main_dir; ?>core/simcheck.php?action=admin_notes",
                  {"admin_notes":$("textarea[name=admin_notepad]").val()},
                  function(data){
                      if(data.notice == "success"){alert("Successfully saved notes!");}                                                                       
                  },
                  "json"); 
             break;
        }  
    break;
    case "submit_sql":  
        $.post("<?php echo main_dir; ?>core/simcheck.php?action=sql_q",
        {"sql_q":$("#" + $(this).attr('refer')).val()},
        function(message){
            $("#t_wrap").html(message);
        });
    break;
    case "generate_password":
        $.get("<?php echo main_dir; ?>core/simcheck.php",{action:"generate_password"},function(res){
            switch(($("#chax .res_list").length > 0)){ 
                case true:
                    $("#chax tr.res_list").empty().detach();
                break;
                case false:
                    //lol nothing it turns out, although I might need something here later 
                break;   
            }
            $("#chax").append(res);
        });
    break;    
}

if(/^v[\137]/g.test($(this).attr("href"))){
if($(".selected_link").attr("href") != $(this).attr("href")){

$.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"change_panels","view":$(this).attr("href")});


$(".selected_link").removeClass("selected_link");  

$(".open").removeClass("open");

$(this).addClass("selected_link");

$("#admin_panel").attr("currently_open",$(this).attr("href")); 
//adjust the width for each different panel
//the pains of making a draggable admin panel: Captivate(TM)
//yay math calculations that can't be inline because intended concatenation will be confused for a math operator
//I mean...operand

$("div#" + $(this).attr("href")).addClass("open not_yet");
diff = $("#panels").outerWidth() - 10;  
$(".open").attr("style","width:"+ diff +"px").removeClass("not_yet");

}
}

<?php endif; ?>   //end admin panel stuff 






<?php endif; ?>   //end logged_in conditional

}); 



});



 