<?php

define("UPLOAD_DIR", "upload/");
$file = isset($_FILES["file"]) ? $_FILES["file"] : null;
$result = array();
$result["code"] = 100;
$result["message"] = "Unknown Error";
if ($file) {
    $temp_path = $file["tmp_name"];
    $filename = $file["name"];
    $result["message"] = $file;

    $name = UPLOAD_DIR . $filename;
    $newFileName = $filename;
    while (file_exists($name)) {
        $newFileName = time() . rand(0, 100) . $filename;
        $name = UPLOAD_DIR . $newFileName;
    }
    move_uploaded_file($temp_path, $name);
    session_start();
    $res  = [
        "name"=>$filename,
        "filepath"=>$name,
        "savedName"=>$newFileName
    ];
    $_SESSION["files"][] = $res;
    $result["files"] = $result;
    $result["path"] = "https://".$_SERVER["SERVER_NAME"]."/download.php?file=".urlencode($newFileName)."&name=".$filename;
}


echo json_encode($result);

