<?php
include "config.php";

$recent_files = [];
$all_file_list = glob($config["upload_dir"]."*", GLOB_BRACE);
usort($all_file_list,function($a,$b){
    return filemtime($a) < filemtime($b);
});
foreach ($all_file_list as $filename) {
    $recent_files[] = basename($filename);
}

$list = [];

for($i=0;count($list)<$config["recent_count"] && $i<count($recent_files);$i++){
    $file = $recent_files[$i];
    if($file=="." || $file==".."){
        continue;
    }
    $indexof = strpos($file,$config["prefix_seperator"]);
    $file_name = substr($file,$indexof+1);
    $file_name = substr($file_name,0,-1*strlen($config["postfix_seperator"]));
    $file_time = filectime($config["upload_dir"].$file);
    $file_size = filesize($config["upload_dir"].$file);
    $list[] = [
        "path"=>$file,
        "name"=>$file_name,
        "size"=>$file_size,
        "date"=>[
            "time"=>$file_time,
            "time_full"=>date ("F d Y H:i:s.", $file_time)
        ],
        "download"=>"//".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/download.php?file=".$file
    ];
}
$result = ["code"=>1,"message"=>"Got List"];
$result["list"] = $list;
echo json_encode($result);