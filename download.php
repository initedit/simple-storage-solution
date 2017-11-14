<?php

ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script

$path = "upload/"; // change the path to fit your websites document structure

$dl_file = $_GET['file']; // simple file name validation
$dl_file_name = $_GET['name']; // simple file name validation
$fullPath = $path . $dl_file;
if (file_exists($fullPath)) {
    if ($fd = fopen($fullPath, "r")) {
        $fsize = filesize($fullPath);
        $path_parts = pathinfo($fullPath);
        $ext = strtolower($path_parts["extension"]);
        switch ($ext) {
            case "pdf":
                header("Content-type: application/pdf");
                header("Content-Disposition: attachment; filename=\"" . $dl_file_name . "\""); // use 'attachment' to force a file download
                break;
            // add more headers for other content types here
            default;
                header("Content-type: application/octet-stream");
                header("Content-Disposition: filename=\"" . $dl_file_name . "\"");
                break;
        }
        header("Content-length: $fsize");
        header("Cache-control: private"); //use this to open files directly
        while (!feof($fd)) {
            $buffer = fread($fd, 2048);
            echo $buffer;
        }
    }
    fclose($fd);
}