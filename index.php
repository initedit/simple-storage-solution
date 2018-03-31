<?php
session_start();
global $token;
$token = md5(time() . "&HSGb@4asdhjds*&6");
$_SESSION["token"] = $token;

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SSD - Simple Storage Solution - Initedit</title>
        <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="script.js?v=<?php echo time(); ?>"></script>

    </head>
    <body >
        <div class="Desktop">
            <div class="Bg-Message Background" style="background-image:url(down.png)">Drop your files here...</div>
            <div class="UploadBox " 
                 >
                <input class="FileUploadInput" type="file" name="file" id="file"/>
                <div class="progressBar" contenteditable="true"></div>
                <input type="text" class="ClipboardCopy"/>
                <div class="Alert">URL Copied</div>
            </div>
            <div class="FileBoxContainerScroll">
                <div class="FileBoxContainer">
                    <div class="FilesBox">
                        <?php

                        function file_ui($data) {
                            global $token;
                            extract($data);
                            ?>
                            <div class="FileBox " 
                                 data-name="<?php echo $name; ?>"
                                 data-file="<?php echo $savedName; ?>"
                                 data-path='<?php echo "https://" . $_SERVER["SERVER_NAME"] . "/download.php?file=" . urlencode($savedName) . "&name=" . $name; ?>'
                                 >
                                <span class="Extension">
                                    <?php
                                        $names_arry = explode(".", $savedName);
                                        echo array_pop($names_arry);
                                    ?>
                                </span>
                                <a href="/delete.php?file=<?php echo $savedName; ?>&token=<?php echo $token; ?>" class="close">
                                    &times;
                                </a>
                                <img 

                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/File_alt_font_awesome.svg/512px-File_alt_font_awesome.svg.png"/><br/>
                                <div class="Title" title="<?php echo $name; ?>"><?php echo $name; ?></div>
                                <div class="Size"><?php echo formatBytes(filesize("upload/" . $savedName)); ?></div>
                                <a href="/download.php?file=<?php echo $savedName; ?>&name=<?php echo $name; ?>">
                                    <button class="btn btn-primary Download">
                                        Download</button>
                                </a>
                            </div>
                            <?php
                        }

                        $files = isset($_SESSION["files"]) ? $_SESSION["files"] : null;
                        if ($files) {
                            $files = array_reverse($files);
//                    array_walk($files, "file_ui");
                        }

                        function getlatestfiles($count) {
                            $files = array();
                            foreach (glob("upload/*", GLOB_BRACE) as $filename) {
                                $files[$filename] = filemtime($filename);
                            }
                            arsort($files);

                            $newest = array_slice($files, 0, $count);
                            return $newest;
                        }

                        $files = getlatestfiles(20);

                        foreach ($files as $key => $value) {
                            $k = str_replace("upload/", "", $key);
                            file_ui([
                                "name" => $k,
                                "savedName" => $k
                            ]);
                        }
                        ?> 
                    </div>
                </div>
            </div>
        </div>
        <a class="PoweredBy" href="https://github.com/initedit-project/">Powered by Initedit</a>
        <script>
<?php
if (isset($_SESSION["msg"])) {
    echo "showAlert('" . $_SESSION["msg"] . "')";
    unset($_SESSION["msg"]);
}
?>
        </script>
    </body>
</html>
