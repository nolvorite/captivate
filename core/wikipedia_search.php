<?php

//for later reference

  //incidentally I need to make a database here for school records        
  //i'm gonna go ahead and make it look in the first 3 pages or so                
    
  
  //first off, this is soooooo bandwidth-intensive
  //not gonna lie this is a pathetic way to search for schools, I could have just used Wikipedia's sandbox JSON API
  //but then again, I don't think it could get side information
  //nevertheless, this is by far the TACKIEST thing i've ever coded to date.
  
  $search_query["bulk"] = [file_get_html("http://www.google.com/search?q=". rawurlencode($_FILTERED['data'][0] . ' ' . $_FILTERED['data'][1]) . "%20Wikipedia")];
  foreach($search_query["bulk"] as $html_bulk){ //   $i = isset($i) ? $i+1 : 0;      //stupid logic dyslexia
  $html_composite = isset($html_composite) ? $html_composite .= $html_bulk->find("div#ires",0) : $html_bulk->find("div#ires",0);
  


  
  }

  $html_composite = str_get_html($html_composite);
  $search_query["results"] = $html_composite->find("li.g");
  foreach($search_query["results"] as $iterations){
  //get all possible search parameters that can include a school from the links
  //then filter the search results to only include schools
  
  //incidentally, html-dom-search is fun to use
  
  //cross-reference all wikipedia pages
  //reference its sidebar to check if its a high school or college, etc
  //initially I was going to use Wikipedia's API to get results but decided against
  
  //i'm going to use these for a search feature whenever I start coding that     
       
  
  $parsed_1 = str_get_html($iterations);
  $parsed_2 = [$parsed_1->find(".s .kv",0),$parsed_1->find("h3", 0)];  //get URLs and page titles of search results, respectively       
//  $keywords = preg_replace("#^(.+)[\057]wiki[\057](.+)[ ]+(cached(.+))+#","$2",strtolower(preg_replace("#[_]#"," ",$parsed_2[0]->plaintext))); //wee
    //check that they both exist, check that the link's name has the word "school" or a synonym of it, and check that the title has a search query word

    
  if(
  is_object($parsed_2[0]) && is_object($parsed_2[1]) &&    
  preg_match("#en[.]wikipedia[.]org[\057]wiki[\057](.{0,})(school|college|academy|university|education|grade)(.{0,})#",strtolower($parsed_2[0]->plaintext))  //checking if link has a school tag
)      //check to see that the typed school name has any of the school keywords
  {          
                                             
  //then what
  //uh
  //reference the Wikipedia URL which is $parsed_2[0]
 // $search_query["check_sidebar"] = file_get_html("http://" .$parsed_2[0]->plaintext);
  //look for sidebar
  

  
  $links = str_get_html($parsed_2[0])->find(".kv",0);
  

   
 $urlencode = "http://" . preg_replace("#[ ]+(.){0,}$#","",$links->plaintext);    //remove all the side text  
 
 //links the sidebar on these links for data if they even exist
 $refer_to_url = file_get_html($urlencode)->find("#mw-content-text table.infobox",0);
 if(is_object($refer_to_url)){
 //get data on each row in an array
 $get_properties = str_get_html($refer_to_url->outertext);   
 $get_data = $get_properties->find("tr");   //get only the ones with td's (ergo, data rows)

 $data_screened = str_get_html($get_properties->innertext)->find("th[!colspan],td[!colspan]");

 
 foreach($data_screened as $sheet){
 if(preg_match("#^<th#",$sheet)){$name = strtolower(preg_replace("#[^A-Za-z]#","",$sheet->plaintext));
             //criteria evaluation

 }
 else{$value = $sheet->plaintext;
 //i'm lazy so i'll do all the modifying for this later
 }     
 
 if(isset($name)){$properties[$name] = isset($value) ? preg_replace("#([\133](.+)[\135][ ]+)$#","",$value) : "";  }
 
 }      
         //finally, phase 2
         $page_title = preg_replace("#(.+)- Wikipedia(.+)$#","$1",$parsed_2[1]->plaintext);
         if(isset($properties['enrollment']) || isset($properties['students']) || isset($properties['numberofstudents']) && !preg_match("/district/i",$properties['name'])){   //eugh
         $properties['numberofstudents'] = isset($properties['numberofstudents']) ? $properties['numberofstudents'] : "";
         $counts = isset($properties['students']) ? $properties['students'] : $properties['numberofstudents'];
         $student_count = (isset($properties['enrollment'])) ? $properties['enrollment'] : $counts;
     
        $properties['link'] = $urlencode;  //link for later reference  
          $properties['name'] = $page_title;
          
         $toformat = json_encode($properties);
  echo "<div class='school_shell'><a href='select-school' class='prompt school_box rad' datamine='$toformat'><h3>".$page_title." <span>";      
      $enrolled = preg_replace("#^(([0-9]+[,]?)+)(.{0,})$#","$1",$student_count);
        echo "<em>".$enrolled."</em> enrolled as of last census";
      echo (isset($properties['established'])) ? " &middot; Established " . $properties['established'] : "";
  
  echo"</span></h3> (Confirm as your school)</a>";
  

      
  echo " <span class='link'><strong>Link: </strong> <a href='".$urlencode."'>".$urlencode."</a></span></div><br>";
       
  
    }       
  
       unset($properties); //duh
  
     
  
$refer_to_url->clear();}

//we have to disable error displays here
//if the school's name was never listed before in the education database, we have to send the data to the school table, but first have the user clear up details for himself
//if a data name was never entered before as a column, alter the education table to make an entry for it
//then set the user's education to the respective recently posted school(s)
//and copying this entire set of comments back to simcheck.php
 
  
   

     }

//   echo $search_query["get_data"];
$parsed_1->clear();       //I was told this reduces searching time
  }
    foreach($search_query["bulk"] as $html_bulk){ $i = isset($i) ? $i+1 : 0; 
   $search_query["bulk"][$i]->clear();
  }


?>