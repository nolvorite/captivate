for(i = 0;i <= chat_sync.messages.length - 1;i++){

message = chat_sync.messages[i].content; 
user = chat_sync.messages[i].bywhom; 
postid = chat_sync.messages[i].postid;
time = chat_sync.messages[i].stamptime; 
image = chat_sync.messages[i].image_embed;
j = i - 1;  

last_id = postid;

if(/^none$/g.test(image) === false){
format = chat_sync.messages[i].img_format;      
image_url = set_var.img_url_suffix + image + '.' + format;
dimensions = chat_sync.messages[i].dimensions;
//dimensions = dimensions.split("x");
//dimensions = {width: parseInt(dimensions[0]),height: parseInt(dimensions[1])};
//console.log(set_var.size_fix);


//resize_d = (dimensions[0] > set_var.fsize_fix) ? dimensions : {width: set_var.fsize_fix, height: parseInt(dimensions.height) * (set_var.fsize_fix / dimensions.width)};                 
message = '<a href="'+image_url+'" target="_blank"><img src="'+ image_url +'" alt="uploaded image" class="image_prop message_img" size="'+ dimensions+'"></a>'; 
}
                                 
r_format = ((j < 0 && i === 0) || (i > 0 && chat_sync.messages[j].bywhom != user) && !(i === 0 && user === your_username)) ? "<div>"+user+"</div><p num='"+postid+"' time_posted='"+time+"'>"+message+"</p>" : "<p num='"+postid+"' time_posted='"+time+"'>"+message+"</p>"; //check whether previous user was the same one so I won't have to spell the username for each reply
//count the very first message

messages_p = (typeof messages_p === "undefined") ? r_format : messages_p + r_format;   

}
