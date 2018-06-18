<?php

include "config.php";

$result = ["code" => 100, "message" => "Unknown Error"];


$path = $_POST["path"];

$file = $config["upload_dir"] . $path;
if (strpos($path, "..") === 0) {
    $result["message"] = "Invalid path.";
} else if (file_exists($file) && !is_dir($file)) {
    unlink($file);
    $result["message"] = "File deleted";
} else {
    $result["message"] = "File not found";
}
$result["list"] = $path;
echo json_encode($result);
