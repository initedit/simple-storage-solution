<?php
/*
 * Its an upload script which stores users file
 * 
 * @author Ashish Maurya
 * @company Initedit
 * @companyurl http://initedit.com
 * @since 07-Apr-2018
 * @version 0.0.1
 */

include "config.php";
include "util.php";
$UPLOAD_DIR = $config["upload_dir"];

// Make Upload Directary if it does not exists already
if(!file_exists($UPLOAD_DIR)){
    mkdir($UPLOAD_DIR,0700);
    file_put_contents($UPLOAD_DIR.'.htaccess', 'Deny from all');
}
if(isset($_POST['folder'])){
    $FODLER = getFolder($_POST['folder']);
    if(!file_exists($UPLOAD_DIR.$FODLER)){
        mkdir($UPLOAD_DIR.$FODLER, 0700);
        $UPLOAD_DIR = $UPLOAD_DIR.$FODLER.'/';
    }
}
// Check if user uploaded or not
$USER_UPLOADED_FILE = isset($_FILES["file"])?$_FILES["file"]:null;

$result = ["code"=>100,"message"=>"Something broken..."];

if($USER_UPLOADED_FILE!=null){
    $USER_UPLOADED_FILE_NAME = $USER_UPLOADED_FILE["name"];
    $USER_UPLOADED_FILE_TMP_NAME = $USER_UPLOADED_FILE["tmp_name"];
    
    $USER_UPLOADED_FILE_NAME_SAVE = $USER_UPLOADED_FILE_NAME;
    $FILE_NAME_PREFIX = null;
    $PREFIX_SEPERATER = $config["prefix_seperator"];
    $POSTFIX_SEPERATER = $config["postfix_seperator"];
    if(file_exists($UPLOAD_DIR.$FILE_NAME_PREFIX.$USER_UPLOADED_FILE_NAME.$POSTFIX_SEPERATER)){
        $FILE_NAME_PREFIX .= time().rand(0,100);
    }
    while(file_exists($UPLOAD_DIR.$FILE_NAME_PREFIX.$PREFIX_SEPERATER.$USER_UPLOADED_FILE_NAME.$POSTFIX_SEPERATER)){
        $FILE_NAME_PREFIX .= time().rand(0,100);
    }

    if($FILE_NAME_PREFIX!=null){
        $USER_UPLOADED_FILE_NAME_SAVE = $FILE_NAME_PREFIX.$PREFIX_SEPERATER.$USER_UPLOADED_FILE_NAME;
    }else{
        $USER_UPLOADED_FILE_NAME_SAVE = $PREFIX_SEPERATER.$USER_UPLOADED_FILE_NAME;
    }
    $USER_UPLOADED_FILE_NAME_SAVE = $USER_UPLOADED_FILE_NAME_SAVE.$POSTFIX_SEPERATER;

    $is_saved = move_uploaded_file($USER_UPLOADED_FILE_TMP_NAME,$UPLOAD_DIR.$USER_UPLOADED_FILE_NAME_SAVE);

    if($is_saved){
        $result["message"] = "uploaded";
        $result["code"] = 1;
        if($config["upload_clamp"]){
            
            $recent_files = [];
            $all_file_list = getAllFiles($UPLOAD_DIR);
            usort($all_file_list,function($a,$b){
                return filemtime($a) < filemtime($b);
            });
            foreach ($all_file_list as $filename) {
                $recent_files[] = basename($filename);
            }
            if($config["recent_count"]<count($recent_files)-2){
                    $remove_file_name = end($recent_files);
                    unlink($UPLOAD_DIR.$remove_file_name);
                    $result["dev"]="Removed Last Element as Clamp was enabled";
                    $result["devfile"]=$remove_file_name;
            }
        }
    }else{
        $result["message"] = "Unable to upload";
        $result["dev"] ="move_uploaded_file operation failed. Could be cause of file permission or disk size";
    }
}else{
    $result["message"] = "No File Found";
}
echo json_encode($result);
