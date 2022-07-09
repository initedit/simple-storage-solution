<?php
if(!isset($config)){
    exit;
}

function getFolder($path){
    $arr = explode('/',$path);
    if(count($arr)>=2){
        $val = $arr[1];
        if(strpos($val,'..')===0){
            return '';
        }
        return $val;
    }
    return '';
}

function getAllFiles($dir){
    $files = glob($dir.'*');
    $result = [];
    foreach($files as $file)
    {
        if(is_file($file)){
            $result[] = $file;
        }
    }
    return $result;
}
