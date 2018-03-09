//load notifications, online status, etc
//hmmm                   

setInterval(function(){


$.ajax({
    url: "<?php echo main_dir; ?>core/simcheck.php",
    data: {"action":"sync_all"},
    async: false,
    success: function(sync){
        sync_in = sync;        
    },
    dataType: "json"
});
                   
if(sync_in.chat > 0 || $(".chat-b[preloaded]").length > 0){


$.get("<?php echo main_dir; ?>core/simcheck.php",{"action":"chat_with_user","format":"sync_in"},function(asyncd){

asynco_dt = asyncd;                  
  
if(asyncd.new_messages > 0){   //get users who most recently sent currently logged in user a message

console.log("new senders list",asyncd);

for(i = 0;i <= asyncd.senders.length-1;i++){   //check to see if respective chat box is not opened and if there's less than 5 senders
//actually for the latter conditional I will set up the deletion in previous 




if(msg_panel.check()){      /*
    //check to see if this conversation is currently opened in the message panel
    if(dyn_fx.chat_data.chat_list("opch_panel","check",asyncd.senders[i])){ //if it's open, update new messages
        width_adjust = "msg_panel";
        dyn_fx.load_replies(asyncd.senders[i],"load",".message_list[ref2='"+asyncd.senders[i]+"']");  
    }
    else{ //if not just put a counter
        dyn_fx.chat_data.chat_list("opch_panel","unread_counter",asyncd.senders[i],asyncd.senders_ea[i][2]);
        counter_html = "("+asyncd.senders_ea[i][2]+" new)";
        if($("a.prompt[ref2='"+asyncd.senders[i]+"'] h4").has(".counter")){
            $(".counter").html(counter_html);
        }
        else{
            $("a.prompt[ref2='"+asyncd.senders[i]+"'] h4").append("<span class='counter'>"+ counter_html +"</span>");
        }
       // $("")
    }                */
}
       

if($(".chat-b[ref='"+asyncd.senders[i]+"']").length === 0 && $(".chat-b[ref]").length <= 5){ // $("#left1").prepend(asyncd.senders[i]);

width_adjust = "chat";
dyn_fx.load_replies(asyncd.senders[i]);  
//this is where a chat box would be loaded if a new message is sent from the other user but this current user doesn't have it opened

//$("#left1").prepend("3");
}
}

                          

}  


},"json");







if(($(".chat-b").length <= 5 && $(".chat-b").length > 0) // || ($(".chat_user[ref]").length <= 2 && $(".chat_user[ref]").length > 0)
){

$(".chat-b").each(function(){ //this is where replies from the other users will be appended, or your messages that haven't been in the post queue will be saved.
other_user = $(this).attr("ref");

var check_for_new = (typeof asynco_dt === "object" && asynco_dt.new_messages > 0) ? {"action":"chat_with_user","user":other_user,"new_only":true} : {"action":"chat_with_user","user":other_user};

//load the chat boxes L
$.ajax({url:"<?php echo main_dir; ?>core/simcheck.php",data:check_for_new,success:function(chat_sync){

if(typeof messages_p === "string"){delete messages_p;}  //cringe. yep lol

num = chat_sync.messages.length - 1; 

x = chat_sync.messages[num].postid;   //get the postid of the very last post in a chat comment chain

y = (typeof $(".chat-b[ref='"+ check_for_new.user +"'] .spacer .chat_box p:last").attr("num") !== "string") ? 0 : $(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box p:last").attr("num"); //just a quick fix for those pre-loaded chat boxes
                 
if(typeof chat_sync.messages === "object" && x > y){ //inserting the chat box in  
               
//check to see if there's a new post by checking if the most recent post ID is larger than the smaller one
//check to see that there's actually a conversation between the two, and the newest message is later than the most recent post


width_adjust = "chat";
user_plop = check_for_new.user;
<?php include("post_list.js"); ?>


//console.log(dyn_fx.chat_data.retrieve(set_var.your_username,other_user,chat_sync))
     
 place = [$(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").prop("scrollHeight"),($(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").prop("scrollTop") < 150) ? $(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").prop("scrollTop") : 203422];    
 
 place[1] = (y === 0) ? 2934923 : place[1];  //so the pre-loaded chat boxes would automatically scroll to the bottom when a new message comes
//console.log(dyn_fx.chat_data.chat_list("retrieve"));
                                      
$(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").find(".loading_text").remove().end().append(messages_p).scrollTop(place[1]);

delete messages_p;
if(check_for_new.hasOwnProperty("new_only")){
    //nothing here yet lol
}
else{
    dyn_fx.chat_data.save(set_var.your_username,check_for_new.user,chat_sync);
}    
console.log("newly saved chat list is here --> ",dyn_fx.chat_data.chat_list("retrieve"));                                                                                        
}else{
    if(x !== y){
        $(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").html("<div class='loading_text'><em>"+chat_sync.messages+"</em></div>");  
    }
}

},dataType: "json"});



$(this).removeAttr("preloaded");

});
  
  
}     


}






//saved users on chat


if(sync_in.notifs > 0){


$.ajax({   //notification relay
url: "<?php echo main_dir; ?>core/simcheck.php",
data: {"nm_time":"notifs"},
success: function(data){        $("#notifications").html();

                                 

if(data.result == "new"){    //new as in there are notifications, what counts is if it's read

if($("#notifs_bar .spacer a").next().is(":not('.note')")){

if(data.unread >= 1){$("#notifs_bar .spacer a").after("<span class='note'>("+data.unread+" new)</span>");  
$("title").prepend("("+data.unread+") ");
$("#notifications .spacer").empty();
}}
 

                                    i = 0;
$.each(data.notifs,function(i,v){

i++;
j = data.notifs.length - i;      //reverse it
//because there's no negative incremental index looping for .each()

if($(".notifs").length != data.notifs.length){ 
$("#notifications .spacer").prepend("<div alt='"+ data.notifs[j]['url'] +"' class='notifs unchecked'>"+data.notifs[j]['content']+"<br><em>"+data.notifs[j]['stamptime']+"</em></div>");
//$("<a href='"+ data.notifs[i]['url'] +"'><div class='notifs'>Some arbitrary content "+data.notifs[i]['content']+"</div></a>").prependTo("#notifications .spacer");
}
});


    
$zin = $("#notifications .spacer").html();

                
} //console.log(dyn_fx.chat_data.chat_list("retrieve"));

  $('.notifs').each(function(){
$zen = $(this).attr('alt');    
if($(this).parent().is(":not('a')")){
$(this).wrap("<a href='"+ $zen +"' />");    //that was painful
}
});

},dataType: "json"        
});

                }
                
                $("head").ready(function(){
                
 $(".image_prop").filter(":not('.adjusted')").each(function(){          
        dimensions = $(this).attr("size");
        dimensions = dimensions.split("x");
        dimensions = {width: parseInt(dimensions[0]),height: parseInt(dimensions[1])};         
    if($(this).hasClass("chat_img")){
        
//console.log(set_var.size_fix);

        resize_d = (dimensions[0] > set_var.size_fix) ? dimensions : {width: set_var.size_fix, height: parseInt(dimensions.height) * (set_var.size_fix / dimensions.width)};  
    }
    if($(this).hasClass("message_img")){
//console.log(set_var.size_fix);

        resize_d = (dimensions[0] > set_var.fsize_fix) ? dimensions : {width: set_var.fsize_fix, height: parseInt(dimensions.height) * (set_var.fsize_fix / dimensions.width)};  
        
    }
        $(this).attr("style","width:"+resize_d.width+"px; height:"+resize_d.height+"px;").addClass("adjusted");  
        
        is_set = (typeof is_set !== "undefined") ? true : false;  
        namez = $(this).closest(".chat-b").attr("ref");
        if(typeof references === undefined){
              references = [namez];    
        }
        else{
              references.push(namez);
        }
        
    });
        if(is_set === true){ //adjust scrolling for new images
             place = [$(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").prop("scrollHeight"),($(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").prop("scrollTop") < 150) ? $(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").prop("scrollTop") : 203422];    
 
             place[1] = (y === 0) ? 2934923 : place[1];  //so the pre-loaded chat boxes would automatically scroll to the bottom when a new message comes
//console.log(dyn_fx.chat_data.chat_list("retrieve"));
             $(".chat-b[ref='"+check_for_new.user+"'] .spacer .chat_box").scrollTop(place[1]);
             is_set === false;
        }
    });
               
},2500);     