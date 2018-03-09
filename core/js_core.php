<?php  $loc = "jsapp";
require_once("headers_for_files.php");              unset($loc);
?>


// what goes here:
//    any functionality requiring AJAX
//    but NOT necessarily the whole function is necessary, just the specific function(s) that execute the server request
//    dynamic functions that help with the site's UI

// what doesn't go here (and over at js_chap.php)
//    any functionality that specifically focuses on the website's style
//    but anything that helps readability/UI goes here



var set_var = {
your_username : "<?php echo isset($_SESSION['login_q']) ? $_MONITORED['login_q'] : ""; ?>",
img_url_suffix : "http://localhost/captivate/images/",
microtime: function (get_as_float)
{
var unixtime_ms = new Date().getTime();
    var sec = parseInt(unixtime_ms / 1000);
    return (get_as_float) ? unixtime_ms/1000 : sec + (unixtime_ms - (sec * 1000))/1000 ;
},
is_chat_opened: false
};

var swiss_army = {


stringify_search: function(array,name,return_value){
    name_list = JSON.stringify(array);
    regex_queue = new RegExp(name,"i");
    if(arguments.length === 2){
        return_val = "boolean";
    }
    if(arguments.length === 3){
        return_val = return_value;
    }
    switch(return_val){
        case "boolean":
            return regex_queue.test(name_list);
        break;
    }
},

hs_dig : function (obj, query) {

    for (var key in obj) {
        var value = obj[key];    //wait this doesnt work on mobile eugh

        if (typeof value === 'object') {
            this.hs_dig(value, query);
        }

        if (value === query) {
            return value;
        }
    }
},
ch_check : function(i){
    //j = new RegExp("^("+ i +"([,][ ])?|"+ i +"([,][ ])?$|([,][ ])"+ i +"([,][ ]))","i");
    j = new RegExp("(^"+ i +"([,][ ])?|([,][ ])"+ i +"?$|([,][ ])"+ i +"([,][ ]))","i");
    return j; 
}
};   

var $black_layer = ["<div id='black_overlay'><div id='widthfix'>","</div></div>"];  

var dyn_fx = {

chat_data: {
chat_list: function(c,a,t,s){                               
switch(c){
case "opch_panel":
    identifier = swiss_army.ch_check(t);
    if(typeof t === "string"){
    open_ch_list = (typeof sessionStorage.getItem("open_chats") === "undefined") ? t : sessionStorage.getItem("open_chats");
    switch(a){
        case "unread_counter": //get # of unreads from each user
            num = (typeof sessionStorage.getItem("unread_" + t) === "undefined") ? s : sessionStorage.getItem("unread_" + t) + s;
            sessionStorage.setItem("unread_" + t,num);
        break;
        case "save":
            sessionStorage.setItem("open_chats",t + ", " + open_ch_list);
        break;         
        case "check":              
            return identifier.test(open_ch_list);
        break;
        case "remove":
            updated_list = identifier.replace(open_ch_list,"");
            sessionStorage.setItem("open_chats",updated_list);
        break;
    }        
        }else{console.log("you need a 3rd argument with a username defined");}
break;
case "save":       
//variables::::::
//sessionStorage.getItem("chat_p") = the variable where we're storing the chat list
// swiss_army_ch_check({}) = regex test

get_count = sessionStorage.getItem("chat_count");
                                                                                          
count = (sessionStorage.getItem("chat_count") === null) ? 1 : parseInt(sessionStorage.getItem("chat_count")) + 1;
sessionStorage.setItem("chat_count",count);
console.log(sessionStorage.getItem("chat_count"));

chat_list = sessionStorage.getItem("chat_p");

if(sessionStorage.getItem("chat_p") === null){
    sessionStorage.setItem("chat_p",a);
    
}
else{
    console.log(typeof sessionStorage.getItem("chat_p"));
    regex_test = swiss_army.ch_check(a);
    if(regex_test.test(chat_list) === false){
        sessionStorage.setItem("chat_p", sessionStorage.getItem("chat_p") + ", " +  a);
        
    }
}
console.log(sessionStorage.getItem("chat_p"));
break;
case "remove":    
list = sessionStorage.getItem("chat_p");
console.log(dyn_fx.chat_data.chat_list("retrieve"));
update_code = swiss_army.ch_check(a);
updated_list = list.replace(update_code,"");
console.log(update_code,updated_list);
sessionStorage.setItem("chat_p", updated_list);
break;
case "retrieve":
//get the list
identifier = swiss_army.ch_check(t);
item = (typeof sessionStorage.getItem("chat_p") === "string") ? sessionStorage.getItem("chat_p") : "";

return (arguments.length === 3 && a === "check") ? identifier.test(item) : item;

break;
}
},
bunker: function(x,y){
sessionStorage.setItem(x,y);
},
save : function(user,recipientz,chat_dt){
console.log(typeof user !== "undefined",typeof recipientz !== "undefined",typeof chat_dt !== "undefined",sessionStorage.getItem("chat_p"));
    if(typeof user !== "undefined" && typeof recipientz !== "undefined" && typeof chat_dt !== "undefined"){ console.log("this succeeded");      
        this.chat_list("save",recipientz);
        console.log("chat list -----> ",this.chat_list("retrieve"));                    
        chat_dt_prototype = JSON.stringify(chat_dt.messages);      
        this.bunker(user + "_" + recipientz,chat_dt_prototype);
    }
},
retrieve : function(user,recipientz){
    return JSON.parse(sessionStorage.getItem(user + "_" + recipientz));
},
append : function(user,recipientz,chat_dt){
    chat_dt_prototype = this.retrieve(user,recipientz);
    if(typeof chat_dt_prototype ===  "array"){
        p = chat_dt_prototype.length;
        for(i = 0;i <= chat_dt.messages.length - 1;i++){
             n = p + i;
             chat_dt_prototype[n] = chat_dt.messages[i];
        }
        chat_dt_prototype = JSON.stringify(chat_dt_prototype);
        this.bunker(user+"_"+recipientz,chat_dt_prototype);
    }
},
user_dt: function(action,user,variable){ //create a timeline for the last post lol
    
    if(typeof user === "string"){
        switch(action){
            case "last_poster":
                if(typeof localStorage.getItem("user_dt" +user) !== "string"){
                    return false;
                }
                if(typeof localStorage.getItem("user_dt" +user) === "string"){
                    return localStorage.getItem("user_dt" +user);
                }
            break;
            case "save":
                if(typeof variable === "string"){
                    localStorage.setItem("user_dt" +user,variable);
                }
            break;
        }
    }       
},
remove : function(user,recipientz){ //I have no idea why this is necessary but oh well
    console.log(this.chat_list("retrieve"),sessionStorage.getItem("chat_p"));
    this.chat_list("remove",recipientz);
    chat_dt_prototype = this.chat_list("retrieve");
    console.log(chat_dt_prototype);
    if(typeof chat_dt_prototype ===  "array"){
        sessionStorage.deleteItem(user + "_" + recipientz);
    }
}
    
},
msg_panel: function(s){

switch(s){
case "check":
    return set_var.is_chat_opened;
break;
case "toggle":
    $("#message_panel").toggleClass("clear");
    $("body").toggleClass("noscroll");   
    $("a[href='message_panel']").toggleClass("focused_link1"); 
    switch(set_var.is_chat_opened){
        case false:
            $("#message_panel").css("height",window.innerHeight + "px");  
            set_var.is_chat_opened = true;             
        break;
        case true:
            $("#message_panel").removeAttr("style");
            set_var.is_chat_opened = false;        
        break;
    }         
break;
}
}

}; 



function confirm_notifs(){
$.get("<?php echo main_dir; ?>core/simcheck.php",{"nm_time":"notifs","action":"clearnotifs"},function(data){if(data.notifs_left > 0){$("#notifs_bar .spacer .note").html("("+data.notifs_left+" new)");}}, "json");
}





dyn_fx.chat_data.img_adjust = function(element){
if(typeof element === "string"){

}
else{// alert("a");   
// $(".image_prop[0]").hide();


    
}  
};


                       
$("head").ready(function(){ //anything with a jquery query goes here lolz

set_var.size_fix = $("#left_menu").width() * .68;  

dyn_fx.load_overlay = function(c,a,s,t){
    if(arguments.length >= 2 && typeof c === "string"){
        switch(a){
            case "open":
                $("body").prepend($black_layer[0] + $black_layer[1]);
                $(c).removeClass("clear").appendTo("#widthfix");  
            break;
            case "close":
                $("#widthfix .hide").addClass("clear").appendTo(c);
                $("#black_overlay").remove();
            break;
        }
    }
}                 

///////////////////////////chat replies////////

dyn_fx.load_replies = function(user_in_call,append_last,jq_append,msg_append,misc){  

call_recipient = (typeof call_recipient === "undefined" && (typeof user_in_call === "string" || typeof user_in_call === "integer")) ? user_in_call : call_recipient;

update_check = (typeof msg_append === "string" && msg_append === "append") ? true : false;

server_call = (typeof append_last !== "undefined" && append_last === "load") ? {"action":"chat_with_user","user":call_recipient,"new_only":true} : {"action":"chat_with_user","user":call_recipient}

//save data



console.log(server_call);

if(typeof append_last === "string" && append_last === "open_box"){
server_call.box_action = "open";

}
                                                                                    
$.get("<?php echo main_dir; ?>core/simcheck.php",server_call,function(chat_sync){     //start em' here
                                                          
if(chat_sync.message === "success"){ 
//connection does exist                  
//console.log(chat_sync.messages);
                                                                         
if(typeof chat_sync.messages === "object"){ 

//console.log(chat_sync.messages);

user_plop = server_call.user;
<?php include("post_list.js"); ?>

}else{                                  
messages_p = "<p id='no_messages_yet'><em>" + chat_sync.messages + "</em></p>";  //no messages in conversation yet
}
switch(typeof server_call.box_action){
case "string":
    if(server_call.box_action === "open"){  //loading new chat boxes
        dyn_fx.chat_data.save(set_var.your_username,server_call.user,(typeof chat_sync.messages === "object") ? chat_sync.messages : "");
        switch(/^[0-9]+$/.test(server_call.user)){
            case true:
                chat_header = chat_sync.cg_name;
            break;
            case false:
                chat_header = "<?php echo $nx['40']; ?>" + chat_sync.user ;
            break;
        }
    } 
break;
/* 
case "undefined": 
    
break;      
*/
}                                                             




if(typeof append_last !== "undefined" && append_last === "load"){
    if(typeof jq_append === "string"){
        switch(jq_append){
            case "none":    
                if(typeof msg_append === "string" && msg_append === "append"){  
                    //load the new posts in the chat box after the user replies to a conversation 
                    place = [$(".chat-b[ref='"+other_user+"'] .spacer .chat_box").prop("scrollHeight"),($(".chat-b[ref='"+other_user+"'] .spacer .chat_box").prop("scrollTop") < 150) ? $(".chat-b[ref='"+other_user+"'] .spacer .chat_box").prop("scrollTop") : 203422];    
 
                    place[1] = (y === 0) ? 2934923 : place[1];  
                    $(".chat-b[ref='" + chat_sync.user + "'] .spacer .chat_box").find(".loading_text").remove().end().append(messages_p).scrollTop(place[1]);  //don't forget to set the scrolldown!
                    console.log("scrolled here");
                    dyn_fx.chat_data.append(set_var.your_username,server_call.user,chat_sync);
                }
                else{
                    $(".chat-b[ref='" + chat_sync.user + "'] .spacer .chat_box").empty().html(messages_p);
                    
                }
            break;
            default:      
                if(typeof msg_append === "string" && msg_append === "append"){
                    $(jq_append).find(".loading_text").remove().end().append(messages_p);
                }
                else{
                    $(jq_append).empty().html(messages_p)
                    
                }
            break;
        }    
    } 
}
else{ //load a new chat box.

//lol can't have variables multiline #javascript  

chat_box = "<div class='box chat-b' ref='" + chat_sync.user + "' postloaded='true'><div class='spacer'><h3>"+chat_header+"</h3><div class='cb_wrap'><div class='chat_box'>"+messages_p+"</div></div><div class='chat_panel'><textarea></textarea></div><form ref='" + chat_sync.user + "'><input type='file' name='file_upload' class='upload inline2' title='' rel='thread'><a class='button_samp prompt rad chat-buttons greened placeholder image_upload_btn' href='upload-chat-img'>&nbsp;</a></form><a class='prompt button_samp rad chat-buttons greened' href='submit-chat-msg'>SUBMIT</a><a class='prompt button_samp rad chat-buttons' href='close-chat'>Close</a></div></div></div>";                                                                 
if($(".chat-b[ref='"+chat_sync.user+"']").length < 1){ //check to see that it hasn't existed yet
    $("#chat_list").before(chat_box);
}

zen = $(".chat-b[ref='" + chat_sync.user + "'] .spacer .chat_box").prop("scrollHeight");
$(".chat-b[ref='" + chat_sync.user + "'] .spacer .chat_box").scrollTop(zen);

} 

delete messages_p;
delete server_call;

} //end "success"ful message



},"json"); 
//delete call_recipient;
}  



$("#title_trigger").on('focus',function(){
 /*   if($("#pt_post_op #pt_opts").length < 1 && /^[\091][^\093]+[\093][ ]{0,}[\040].+[\041][ ]{0,}$/.test($(this).val()) == false){
        $("#pt_post_op").appendTo("#pt_opts");
        pto_w = $("#pt_post_op .drop .uplink").outerWidth();
        $("#pt_opts").css("width",pto_w + "px").addClass("pads");
    }        */
    if($("#post_content").css("display") === "none"){
        $("#post_content").toggle("400", function(){
            $(this).attr("style","display:block");
        }); 
    }
}).on("blur",function(){
    if(($(this).val() == $(this).prop("defaultValue") || $(this).val().length == 0) 
    && /^[\091][^\093]+[\093][ ]{0,}[\040].+[\041][ ]{0,}$/.test($(this).val()) == false){
    //    $("#pt_post_op").insertBefore("#post_k");
    //    $("#pt_opts").removeAttr("style class");          
    }
});

$(document).on('scroll',function(){     set = "x";

scroll_point = (/Chrome/g.test(navigator.userAgent)) ? $("body").prop("scrollTop") : $("html").prop("scrollTop");  //damn it Chrome 
//console.log(scroll_point);

//get cell width to adjust for default size


  
                                          
if(scroll_point > screen.availHeight*.5 + 200){

$("#scroll_button").css({"z-index":20,"opacity":1});
}else{
$("#scroll_button").attr("style","");
}                                                

});


$("#scroll_button").on('click',function(){

$("html,body").scrollTop("0");

});


if($(this).is(".left:not(':first')")){
$(this).css("border-left","1px solid #38475D");
if($(this).is(":not('.boxxy')")){
if($(this).parent().is("span.drop")){
$(this).parent().prev().find(".boxxy").detach();
}

}
}


$("body").on('click','*[submit]',function(event){  
    //quick documentation for our little angular-esque framework ?? eugh
    //maybe angular-esque framework isn't the most appropriate name but nevertheless it's a way to more quickly 
    //also, wow I never thought formatting would look this good

    //necessary parameters-- 
    //submit: for the submission path
    //submit_call: takes all the form data of the input with names matched with the pattern defined at submit_call to be added as formdata  
    //OR (well i'll program this later)
    //form_id: as the name suggests, the ID wherein all form data will be taken for submission
    //form_id has priority
    //needs a workaround for all mobile browsers since they don't work there(and lol IE)
    //still curious as to how I would get image upload data without it...


    event.preventDefault();

    if(typeof $(this).attr("submit_call") != "undefined"){
        debug_test = (typeof $(this).attr("testing") === "undefined") ? "json" : "html";     
        form_dt = {};       
        $("input[name^='"+$(this).attr('submit_call')+"'],textarea[name^='"+$(this).attr('submit_call')+"']").each(function(){
            prop_n = $(this).attr('name');
            form_dt[prop_n] = $(this).val();
        });
        y = typeof $(this).attr("testing");        
        $.ajax({                                                     
            url: "<?php echo main_dir;?>core/simcheck.php?action=submit_dt&dt=" + $(this).attr("submit"),
            type: "POST",
            data: form_dt,
            success: function(json){ 
                if(y != "undefined"){
                    alert(json);
                }
                else{
                    alert(json.message);
                    if(json.redir != "none"){
                         window.location.replace = json.redir;
                    }
                }         
            },
            dataType: debug_test 
        });
    }    
    else{
        alert("Need to set a submit_call on button to recognize all data to include!");
    }
    //$("input[name^='"+$(this).attr('submit_call')+"']")
});



//uploaderrrrrrrr durr

//image upload placeholder, but maybe I could set it up for other CSS hacks in the future
//I have to use setinterval because apparently the positioning of the placeholder might be changed

//I have to switch the position and have placeholder be the one on front instead of the one in the back, and on a click trigger the placeholder's respective input to open the file upload thing 
//On Chrome the cursor isn't consistent for the whole area.

$(".placeholder").each(function(){$(this).prev().toggle();});

setInterval(function(){

$(".placeholder").each(function(){
if($(this).prev().length == 0){$(this).before("<div></div>");}


dimensions = [$(this).outerWidth(),$(this).outerHeight()];
positions = [$(this).offset().top,$(this).offset().left];  


if(($(this).prev().attr("style") == "display: none;") || ($(this).prev().offset().top - positions[0] > 2) || ($(this).prev().offset().top - positions[0] < -2) || ($(this).prev().offset().left - positions[1] > 2) || ($(this).prev().offset().left - positions[1] < -2)){
$(this).css("z-index","3").prev().toggle().css({"width":dimensions[0] + "px","height": dimensions[1] + "px","top":positions[0] + "px","left":positions[1] + "px","position":"absolute","z-index":"-1","opacity":"0"});
}

if($(this).prev().is("input")){
    $(this).off().on("click",function(){
        $(this).prev().trigger("click");
    })
}
});                                                                                                        
}, 1000);   

<?php if(logged_in_check): ?>
$("body").on('click',"a.comment_opts",function(event){
    event.preventDefault();
    attribution_id = $(this).parent('.opts_block').attr('alt'); 
    $cane =  $("div[alt='"+attribution_id+"']");
    switch($(this).attr("href")){
        case "delete":
            $(this).attr("id","k_"+attribution_id);
            $.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"alter_post","typeof_alter":"delete","postid":attribution_id},
                function(notif){
                    alert(notif);
                    if(notif === "<?php echo $nx['68']; ?>"){
                        $("#k_"+attribution_id).html(notif);
                    }
                    if(notif === "<?php echo $nx['69']; ?>"){  //DELETED
                        $cane.parent().find(".post_text").html("<?php echo $nx['70']; ?>");
                        $cane.parent().parent().prev().html("<h4>-------</h4>");
                        $cane.remove();
                    }    
                }
            );
        break;
        case "comment_q-u":
            if($(this).parent().has("form").length){
                $(this).parent().find("form").eq(0).detach();
            }
            else{
                $(this).parent().append("<?php $fill_data->js_parsed('reply_form');?>");
            }
        break;
        case "edit":
            parent_check = ($(this).parent().is(".reply_m")) ? true : false;      
            $("div[alt='"+attribution_id+"']").find(".edit").wrap("<p id='d_"+attribution_id+"' />");
            $cane.prev().find(".side_info").wrap("<p id='f_"+attribution_id+"' />");   
            $cane.data("save_4_later", {
                edit_button : $("#d_"+attribution_id).html(),  
                posted_date_html : $("#f_"+attribution_id).html(),
                original_post : $("#f_"+attribution_id).parent().html()   
            });
            $("#d_" + attribution_id +",#f_" + attribution_id).detach();
            $.get("<?php echo main_dir; ?>core/simcheck.php",{"fetch":"postedit_q","postid":attribution_id},function(returned){                                      
                c_msg = "<?php echo $fill_data->js_parsed('edit_button'); ?>";
                j_html = "<textarea class='largeform' id='e_"+attribution_id+"'>"+returned+"</textarea>";
                if(parent_check && $("#e_" + attribution_id).length === 0){                                                
                    $cane.parent().find(".post_text").html(j_html).addClass("relaxed");                
                    $cane.find(".edit").detach();
                    $cane.find(".comment_q-u").after(function(){return ($(this).parent().find(".finish-edit_q").length < 1) ? c_msg : false});    
                }
                else{
                    $("div[alt='"+attribution_id+"']").parent().parent().find("p").html(j_html);
                }          
            });
        break;
        case "finish-edit":
            edit_button = $cane.data("save_4_later").edit_button;
            posted_date_html = $cane.data("save_4_later").posted_date_html;
            original_post = $cane.data("save_4_later").original_post;
            $(this).parent().prev().attr("id","h_" +attribution_id).removeClass("relaxed");
            $(this).after(edit_button).detach();  
            $.post("<?php echo main_dir; ?>core/simcheck.php?action=alter_post&typeof_alter=edit&postid=" + attribution_id,
            {"dt": $("#e_" + attribution_id).val()},
            function(returned){
                if(returned.status == "success"){
                    $("#h_" + attribution_id).html(returned.html + posted_date_html);      
                }
                else{
                    alert(returned.message);
                    $("#h_" + attribution_id).html(original_post);
                }
                $("#h_" + attribution_id).removeAttr("id");               
            },"json");
        break;
    }
});
<?php endif;
//end logged in javascript shit conditional
 ?> 

$("input[type=checkbox]").each(function(){
    $(this).before("<button class='dc'>").addClass("clear");
});  

$("button.dc").each(function(){     //better-looking checkboxes
if($(this).next("input").is(":checked")){$(this).addClass("a2")}else{$(this).addClass("a1")}
if($(this).next("input[id]")){
$(this).attr("id","axe1" + $(this).next("input").attr("id"))
}
 //check initial checkbox value
$(this).click(function(mod){      mod.preventDefault();
if($(this).hasClass("a1") && $(this).next("input").prop("checked",false)){$(this).removeClass("a1").addClass("a2"); $(this).next("input").prop("checked",true)}else{$(this).removeClass("a2").addClass("a1"); $(this).next("input").prop("checked",false)}
});
}); 



$(".pt_opt_in").each(function(){

    $(this).on("click",function(){ 
        z = $(this).html();
        a = (typeof $(this).attr("desc") != "string") ? "" : $(this).attr("desc");
        if($(this).attr("pt_id") != "0"){   
            y = (typeof $(this).attr("post_help") === "string") ? $(this).attr("post_help") : "<?php echo $nx['62'];?>";
            $("#title_trigger").val("["+z+"] (<?php echo $nx['63']; ?>)");
            $("#post_content").val(a + " " + y).removeClass("flick");
        }
        else{     
            $("#title_trigger").val($("#title_trigger").prop("defaultValue"));
            $("#post_content").val($("#post_content").prop("defaultValue"));
            $("#pt_post_op").insertBefore("#post_k");
            $("#pt_opts").removeAttr("style class");
        }
    });
    
});


$("input.upload").each(function(){  //IMAGE UPLOAD
$(this).on("change",function(){

if(typeof set_limbo_images !== "string"){ //only one upload per post.     

this_loc = $(this).attr("rel")

switch(this_loc){

case "thread":
var formData = new FormData($('#post_k')[0]);

break;

case "chat":
var recipient = $(this).parent().attr("ref");   console.log(recipient);
var formData = new FormData($("form[ref="+recipient+"]")[0]);
formData.append("recipient",recipient);
break;

}
formData.append("loc", this_loc);

if(formData.loc === "chat"){
var img_recipient = $(this).parent().parent().attr("ref");
}

$.ajax({
url: "<?php echo main_dir;?>core/simcheck.php?action=file_test",
data: formData,
async: false,
contentType: false,
processData: false,
type: 'POST', 
cache: false, 
dataType: "json", 
success: function(image_sync)             
{ //  alert(image_sync.message);

switch(typeof image_sync){

    case "object":

    if(image_sync.result === "success"){

    url = "<?php echo main_dir; ?>images/"+image_sync.hash+"."+image_sync.file_type+"";

    switch(this_loc){

    case "thread":
    $("#main_new_post").append("<div class='image_embed'><a href='"+url+"' target='_blank'><img src='"+url+"' alt='"+image_sync.hash+"'></a></div><input type='hidden' name='image_data' value='"+image_sync.hash+"'>");

    break;

    case "chat":
    width_adjust = "chat";
    dyn_fx.load_replies(recipient,"load","none","append");
    break;




    set_limbo_images = "";
    }    

    }

    break;

    case "string": //debug
    alert(image_sync);
    break;

}//end switch conditional

}//end function 

})


}else{
alert("You have already uploaded an image for this post you are about to make.");
}
   




});      
//$.get("<?php echo main_dir;?>core/simcheck.php",{"action":"file_test","file_src":$(this).val()},function(messages){ alert(messages);  });

set_limbo_images = undefined;
});



<?php if(isset($_SESSION['login_q'])):
//begin login javascript shit conditional
 ?>

$("input[name='sg_name']").tooltip({position: { my: "left+10 center", at: "right center-1" }, content: "<span id='checkn1'><?php echo $nx['87']; ?></span>"});                                  

$("body").on('mouseenter',"p[time_posted]", function(){

$(this).addClass("get_time").append("<span class='time'>"+$(this).attr('time_posted')+"</span>");

if(last_id === $(this).attr("num")){$(this).parent().scrollTop("234234");
console.log("scrolled here");
} //scroll the chat box all the way down on the last message to see the timestamp without having to do it manually

}).on('mouseleave',"p[time_posted]",function(){  $(this).removeClass("get_time"); $(this).find(".time").remove();    });

$(document).on("keyup","#u_search",function(){  console.log("a");  
$.get("<?php echo main_dir;?>core/simcheck.php",{"action":"user_gc_search","user_query":$(this).val()},function(content){
$("#gc_u_list").css("display","block").html(content);
});   
});    

$("#user_search,#gc_search").on("keyup",function(){
    id = $(this).attr("id");
switch(id){
    case "user_search":
        cl_vars = {"case":"one","href":"chat-with-user","id":"chat_list"};
    break;
    case "gc_search":
        cl_vars = {"case":"two","href":"chat-with-group","id":"gc_list"};
    break;
}
    conditi = {"action":"check_list","check_list":cl_vars.case,"user":$(this).val()}; 
    $.get("<?php echo main_dir;?>core/simcheck.php",conditi,function(user_listz){
            recipient = ""; user_list = ""; clinch_html = "";
            content = user_listz.content;      
            if(content.length !== 0){    
                for(i = 0;i <= content.length - 1; i++){
                    recipient = cl_vars.case === "one" ? {"id" : content[i].granted_by, "name" : content[i].granted_by} : {"id" : content[i].sg_id,"name" : content[i].sg_name};
                    clinch_html = "<a href='"+cl_vars.href+":"+ recipient.id +"' class='prompt chat-option'>"+ recipient.name +"</a>";
                    user_list = (i === 0) ? clinch_html : user_list +""+ clinch_html;
                }
            }
            console.log(user_list, id);   
            
            $("#"+cl_vars.id+" .cl").html(user_list);      
    },"json");
});



$("body").on('click',".prompt",function(event){event.preventDefault();

if(/^chat-with-user[:]/.test($(this).attr("href")) || /^chat-with-group[:]/.test($(this).attr("href"))){ 
call_type = $(this).attr("href").replace(/^chat-with-(user|group):(.+)$/,"$1"); 
call_recipient = $(this).attr("href").replace(/^chat-with-(user|group):(.+)$/,"$2");
call_recipient = /^[0-9]+$/.test(call_recipient) ? parseInt(call_recipient) : call_recipient; //usernames cant start with a number so... yeah. lol
if($(".chat-b").length < 5){   
width_adjust = "chat";
dyn_fx.load_replies(call_recipient,"open_box");   
dyn_fx.chat_data.chat_list("save",call_recipient);
console.log(dyn_fx.chat_data.chat_list("retrieve"));
$(this).addClass("selectd");
} //end check on number of tabs
else{//alert if too many tabs
//alternatively, I could have just checked the number of chat boxes on... :/
alert("You have too many chat tabs on. Please close at least one of them, or open the message panel.");
}

delete call_recipient;
} 
else{

switch($(this).attr("href")){
    case "new_groupchat":
        $.get("<?php echo main_dir; ?>core/simcheck.php?action=new_groupchat",function(html_content){
            dyn_fx.load_overlay("#gc_msg","open");
            $("#gc_msg").html(html_content);
        });
    break;
    case "add-poll":
        dyn_fx.load_overlay("#content","open");
    break;
    case "close-poll": //return_to='#vc2'
        dyn_fx.load_overlay("#vc2","close");
    break;
    case "select_user":
        if(typeof $(this).attr("name") === "string"){
            switch($("tr#selected_users_list").length === 0){
                case true:
                    $("#submit_row").before("<tr id='selected_users_list'><th><h4><?php echo $nx['selected_users_txt']; ?></h4></th><td id='selected_users_cnt'></td></tr>");
                    
                break;
                case false:
                    //
                break;
            }
            if($("input.gc_hid[value='"+$(this).attr("name")+"']").length === 0){
                $("#selected_users_cnt").prepend("<a href='delete_user' user='" + $(this).attr("name") + "' class='selected_for_gc prompt'>" + $(this).attr("name") + "</a><input type='hidden' class='gc_hid' name='selected_for_gc[]' value='" + $(this).attr("name") + "'>");
            }    
        }
    break;
    case "delete_user":
        if(typeof $(this).attr("name") === "string"){
            $("input.gc_hid[value='"+$(this).attr('name')+"']").detach();
            $("a.selected_for_gc[user='"+$(this).attr('name')+"']").empty().detach();        
        }
    break;
    case "submit-chat-msg":
        //submit a chat message     
        chat_msg = $(this).parent().find(".chat_panel textarea").val();
        recipient_call = $(this).parent().parent().attr("ref");
        if(/^[ \n\t\h]{0,}$/g.test(chat_msg) === false){
        $.post("<?php echo main_dir; ?>core/simcheck.php?action=submit-chat-msg",
        {"towhom":recipient_call,"message":chat_msg},function(msg2){}
        );
        $(this).parent().find(".chat_panel textarea").val("");
        width_adjust = "chat";
               
        dyn_fx.load_replies(recipient_call,"load","none","append");
       // width_adjust = "msg_panel";
       // dyn_fx.load_replies(recipient_call,"load",".message_list[ref2='"+recipient_call+"']","append","cb_only");
        }
        else{
            alert("Post something before pressing enter lol")
        }
    break;
    case "cancel_gc":
    case "finish_gc":
        if($(this).attr("href") === "finish_gc"){                      
           selected_list = new Array();
            $("input.gc_hid").each(function(){
                if(/[A-Za-z][A-Za-z0-9_-]{2,19}/.test($(this).val())){
                     selected_list.push($(this).val());
                }
            });
            console.log($("input[name='cg_name']").val());
            $.post("<?php echo main_dir; ?>core/simcheck.php?action=submit_new_gc",{"values[]":selected_list,"name":$("input[name='gc_name']").val(),"type":$("input[name='gc_type']").val()},function(msg){
                console.log(msg);
            });
        }                                                                                                                           
        dyn_fx.load_overlay("#gc_list","close");
    break;
    case "close-chat":
        focused_cb_ref = $(this).parent().parent().attr("ref");
        $.get("<?php echo main_dir; ?>core/simcheck.php",
        {"action":"chat_with_user","user":focused_cb_ref,"box_action":"close"},
        function(message22){     
        console.log(focused_cb_ref);
             $(".chat-b[ref="+focused_cb_ref+"]").empty().detach();   
             dyn_fx.chat_data.remove(set_var.your_username,focused_cb_ref);
             var co_ref = (/^[0-9]+$/.test(focused_cb_ref)) ? "chat-with-group:" + focused_cb_ref : "chat-with-user:" + focused_cb_ref;
             $("a.chat-option[href='"+co_ref+"']").removeClass("selectd");
             console.log(dyn_fx.chat_data.chat_list("retrieve"));

        }); 
        //close it in the array
    break;
    case "load-more":
        $.get("<?php echo main_dir; ?>core/news_feed.php",
        {"get_more":"true"<?php if(isset($_GET['snowglobe'])): ?>, "load_option": <?php echo '"sg_'.$_FILTERED['snowglobe'].'"'; endif;?>},
        function(older_msgs){
            $("#news_feed").append(older_msgs);
        });
        $(this).remove();
    break;
    case "open_cb":
        ref = $(this).attr("ref");
        ref2 = $(this).attr("ref2");
        chat_sync = {messages : dyn_fx.chat_data.retrieve(set_var.your_username,ref2)};
        width_adjust = "msg_panel";
        user_plop = ref2;
        <?php include("post_list.js"); 
        ?>
        $(this).children(".chat_user").unwrap().attr({"ref":ref,"ref2":ref2}).append(function(){
        if($(".message_list[ref2='"+ref2+"']").length === 0){
            return "<div class='message_list' ref='"+ref+"' ref2='"+ref2+"'>"+messages_p+"</div>";   
        }
        });
        
        if($(".message_list[ref2='"+ref2+"']").length){
            $(".message_list[ref2='"+ref2+"']").html(messages_p);
        } 
        
        set_var.fsize_fix = $(".message_list[ref2='"+ref2+"']").width() - 30;  
        delete messages_p;
        $(".image_prop").filter(":not('.adjusted')").each(function(){  
      
        dimensions = $(this).attr("size");
        dimensions = dimensions.split("x");
        dimensions = {width: parseInt(dimensions[0]),height: parseInt(dimensions[1])};         
    if($(this).hasClass("message_img")){
//console.log(set_var.size_fix);

        resize_d = (dimensions[0] > set_var.fsize_fix) ? dimensions : {width: set_var.fsize_fix, height: parseInt(dimensions.height) * (set_var.fsize_fix / dimensions.width)};  
    }
        $(this).attr("style","width:"+resize_d.width+"px; height:"+resize_d.height+"px;").addClass("adjusted");   
    });
    break;
    case "message_panel":
        pre_check = dyn_fx.msg_panel("check");
        dyn_fx.msg_panel("toggle");
        if(pre_check === false){
        $.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"get_msgs_dt"},
        function(dtx){ msg_sync = {};                           
        for(z = 0; z <= dtx.length - 1; z++){
            msg_sync[z] = {};
            msg_sync[z].forwhom = dtx[z].forwhom;  //apparently I have to declare this beforehand or something. What am I missing
            msg_sync[z].recipient = (msg_sync[z].forwhom === set_var.your_username) ? dtx[z].bywhom : msg_sync[z].forwhom; 
            msg_sync[z].bywhom = dtx[z].bywhom;
            msg_sync[z].last_msg = dtx[z].ch_ts;    
            msg_sync[z].true_u = (dtx[z].sg_name !== null) ? dtx[z].sg_name : msg_sync[z].recipient ;
            
         //   console.log(msg_sync[z]);    
            
        //    console.log(dyn_fx.chat_data.chat_list("retrieve"),dyn_fx.chat_data.chat_list("retrieve","check",msg_sync[z].recipient));          
            
            if(dyn_fx.chat_data.chat_list("retrieve","check",msg_sync[z].recipient)){
            console.log("first in");
       //     if($(".chat_user[ref2='"+msg_sync[z].recipient+"']").length){
      //          continue;
       //    }
            
            //ik it's weird but that's how the chat history's generated in the database
//            console.log(dyn_fx.chat_data.chat_list("retrieve","check",msg_sync[z].recipient));
            
            // if data is already there, then no need to make another server request for data
            // retrieve the chat data            
//            alert("heh");            
            msg_sync[z].log = dyn_fx.chat_data.retrieve(set_var.your_username,msg_sync[z].recipient); 
            console.log(msg_sync[z].log);
            }
            else{

            $.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"chat_with_user","user":msg_sync[z].recipient,"box_action":"open"},
                function(chat_sync){
                    chat_cache = {};
                    for(i = 0;i <= chat_sync.messages.length - 1;i++){                    
                        chat_cache[i] = chat_sync.messages[i];
                    }
                    msg_sync[z].log = chat_cache;
                    delete chat_cache;
                }, "json");
            }      
    //        console.log(dtx[z]);
    //      console.log("WTFFF");
            
            
            
            var temply = " <a href='open_cb' ref='"+ z +"' class='prompt cb_users' ref2='"+ msg_sync[z].recipient +"'><div class='chat_user'><table><tr><td width='1%'></td><td><h4>"+ msg_sync[z].true_u +"</h4><span class='date'>"+ msg_sync[z].last_msg +"</span></td></tr></table></div></a>";
            
            var user_listz = (typeof user_listz === "undefined" || user_listz === "") ? temply : temply + "" + user_listz ;    

        }    
        
        $("#chat_history_list").html(user_listz);

        }, "json");
        }
    break;

}

}

});    



<?php require_once("autorelays.js"); endif;  ?>   

                                         
<?php 
 include("profile_sync.js");
?>           

});

 


          

