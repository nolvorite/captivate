for(i = 0;i <= chat_sync.messages.length - 1;i++){

postid = chat_sync.messages[i].postid;
message = chat_sync.messages[i].content; 
user = chat_sync.messages[i].bywhom; 

time = chat_sync.messages[i].stamptime; 
image = chat_sync.messages[i].image_embed;
j = i - 1;  

last_id = postid;

if(/^none$/g.test(image) === false){  //image display duh
format = chat_sync.messages[i].img_format;      
image_url = set_var.img_url_suffix + image + '.' + format;
dimensions = chat_sync.messages[i].dimensions;                                    
img_adjust_call = (width_adjust === "chat") ? "chat_img" : "message_img";
               
message = '<a href="'+image_url+'" target="_blank"><img src="'+ image_url +'" alt="uploaded image" class="image_prop '+ img_adjust_call +'" size="' + dimensions + '"></a>'; 
}

//first_msg_in_queue = (dyn_fx.chat_data.retrieve(set_var.your_username,user).prototype.length === 1) ? true : false;
                                 
r_format = (((j < 0 && i === 0) || (i > 0 && chat_sync.messages[j].bywhom !== user)) || (i === 0 && dyn_fx.chat_data.user_dt("last_poster",user_plop) === false || (dyn_fx.chat_data.user_dt("last_poster",user_plop) !== false && dyn_fx.chat_data.user_dt("last_poster",user_plop) !== user))) ? "<div>"+user+"</div><p num='"+postid+"' time_posted='"+time+"'>"+message+"</p>" : "<p num='"+postid+"' time_posted='"+time+"'>"+message+"</p>"; //check whether previous user was the same one so I won't have to spell the username for each reply
//count the very first message

messages_p = (typeof messages_p === "undefined") ? r_format : messages_p + r_format;   

}
console.log(dyn_fx.chat_data.user_dt("last_poster",user_plop));
k = chat_sync.messages.length - 1;
last_post_id = chat_sync.messages[k].user;
dyn_fx.chat_data.user_dt("save",user_plop,last_post_id);
