<?php
include "config.php";
include "util.php";
$recent_files = [];
$UPLOAD_DIR = $config["upload_dir"];
$FODLER = "";
if(isset($_POST['folder'])){
    $FODLER = getFolder($_POST['folder']);
    $UPLOAD_DIR = $UPLOAD_DIR.$FODLER.'/';
}
$all_file_list = getAllFiles($UPLOAD_DIR);;
usort($all_file_list,function($a,$b){
    return filemtime($a) < filemtime($b);
});
foreach ($all_file_list as $filename) {
    $recent_files[] = basename($filename);
}

$list = [];
$protocol = "http://";
if( isset($_SERVER['HTTPS'] ) ) {
     $protocol = "https://";   
}
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

    $port = "";
    if (in_array($_SERVER['SERVER_PORT'],["80","443"])==false){
        $port = ":".$_SERVER['SERVER_PORT'];
    }
    $download_file = $_SERVER['SERVER_NAME'].$port."/download.php?file=".$FODLER.'/'.$file;
    
    $list[] = [
        "path"=>$FODLER.'/'.$file,
        "name"=>$file_name,
        "size"=>$file_size,
        "date"=>[
            "time"=>$file_time,
            "time_full"=>date ("F d Y H:i:s.", $file_time)
        ],
        "download"=>$protocol.$_SERVER['SERVER_NAME'].$port."/download.php?file=".$FODLER.'/'.$file,
        "curl"=>[
            "web"=> "curl -o  \"$file_name\" \"".$protocol.$download_file."\"",
        ]
    ];
}
$result = ["code"=>1,"message"=>"Got List"];
$result["list"] = $list;
echo json_encode($result);
