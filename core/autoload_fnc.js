 //this is soooo tacky      


$.fn.loadingtext = function(text,dot_nums,add_class){
    settings = $.extend({
        add_class: (typeof add_class === "string") ? add_class : "loading_text",
        text: (typeof text === "string") ? text : "Loading",
        dot_nums: (typeof dot_nums === "number") ? dot_nums : 6    
    });
    function dots($this){   
         switch(typeof $this){                                                   
             case "object":                    
                 mn = settings.dot_nums +1;    
                 textchop = setInterval(function(){      
                     dotsx = (typeof dotsx === "undefined") ? "" : dotsx;    
                     dotsx = "." + dotsx;
                     dotsx = (dotsx.length === mn) ? "" : dotsx;
                     $this.html(settings.text + dotsx)
                 },200);
                 delete dotsx; //for other uses of dots
             break;
             case "string":  //no object inheritance land oooh
                 if($this === "clear"){
                     clearInterval(textchop);
                 }
             break;
         }
    }
    if(arguments.length === 1 && settings.text == "[clear]"){  
        dots("clear");
        this.removeClass(settings.add_class);   
        return this.each(function(){
        $(this).html("");
        });   
    }
    else{   
        var agent = this;
        return this.each(function(){ 
            var chap = $(this);   
            chap.addClass(settings.add_class).html(settings.text);
            dots(chap);
        });            
    }
} 
    //my first jquery function

