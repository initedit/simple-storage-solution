<?php
session_start();
if(isset($_SESSION["token"])){
    if(!empty($_GET["file"]) && !empty($_GET["token"])){
        if($_GET["token"]===$_SESSION["token"]){
            unset($_SESSION["token"]);
            $path = "upload/".$_GET["file"];
            if(file_exists($path)){
                $_SESSION["msg"]="Deleted";
                unlink($path);
            }
        }
    }
}
header("Location: /");