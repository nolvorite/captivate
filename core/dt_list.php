<?php 
switch($_GET['dt']){  
    case "new_class_sg":  
        $table_name = "snowglobes";
        $sg_url = "s". substr(sha1(microtime()),0,24);
        $initial_search_test = "SELECT * FROM school WHERE name='$_DATA[sg_name]' AND EXISTS(SELECT * FROM snowglobes WHERE sg_url != '$sg_url')";
        $successful_entry_msg = "Successfully posted new snowglobe!";
        $message = "There was an error with the data processing. Please try it again.";
        $type = "fail";     
        
        $checks = 
            [
                [preg_match("#.{4,30}#",$_DATA['sg_name']),"Your classroom name is too short/too long. Please modify it.",'sg_name'],
                [preg_match("#.{0,1000}#",$_DATA['sg_desc']),"Your description is too long (over 1,000 characters). Please modify it.",'sg_desc'],
                [preg_match("#[0-9]+#",$_DATA['sg_school_id']),"What do you think you're doing?",'sg_school_id']
            ];
        $misc_sql_queries = "INSERT INTO sg_permissions(access_type,towhom,granted_by,posting_rights) 
        VALUES('root admin','$_MONITORED[login_q]','$sg_url','both')";
        $data_fields = "sg_name,description,root_admin_id,sg_privacy,sg_url,special_settings,reference_num";
        @$field_values = "'$_DATA[sg_name]','$_DATA[sg_desc]','$logged_dt[userid]','public','$sg_url','school','$_DATA[sg_school_id]'";
        $redir = main_dir . "sg/" . $sg_url;
    break;
    case "new_thread_type":    
        $table_name = "post_types";
        $initial_search_test = "SELECT * FROM post_types WHERE call_name='$_DATA[pt_name]'";
        $successful_entry_msg = "Successfully posted new thread type!";
        $lolclone = $_DATA['pt_name'];
        $index_str = strtolower(preg_replace("#-{2,}#","-",preg_replace("#[    A-Za-z0-9_]#","-",$lolclone)));

        $data_fields = "name,description,for_which_sg,call_name";
        @$field_values = "'$index_str','$_DATA[pt_desc]','$_DATA[pt_sg_apply]','$_DATA[pt_name]'";

        //initial fail messages
        $message = "Unfortunately, that post type name is already taken."; 
        $type = "fail"; 
        $checks = 
        [
            [preg_match("#^[A-Za-z 0-9_-]{4,25}$#",$_DATA['pt_name']),"Your post type's name has some invalid characters.",'pt_name'],
            [preg_match("#^.{0,100}$#",$_DATA['pt_desc']),"Description is too long. 100 characters maximum.",'pt_desc',$nx['58']],
            [preg_match("#^([0-9]|[a-z0-9A-Z_-]{4,25},?){0,9}([a-z0-9A-Z_-]{4,25})?$#",$_DATA['pt_sg_apply']),
            "Please correct which snowglobes this thread type applies to. Remember that you have to input the snowglobe's URL nickname (and separate each one by commas). Maximum 10 snowglobes",
            'pt_sg_apply',$nx['57']]
        ];
    break;
}
?>