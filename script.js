$(document).ready(InitApplication);

function InitApplication() {
    InitFileDownload();
    InitUpload();
    InitDragAndDrop();
}

function InitDragAndDrop() {

}


function InitFileDownload() {
    $(".close,.Download").click(function (e) {
        e.stopPropagation();
    })
    $(".FileBox").click(function (e) {

//        e.preventDefault();
//        e.stopPropagation();
        $(".ClipboardCopy").val($(this).attr("data-path"))
        $(".ClipboardCopy").get(0).select();
        showAlert("URL Copied");
        document.execCommand("Copy");

    });
    $(".PoweredBy").click(function (e) {
//        e.preventDefault();
        e.stopPropagation();
    });
}

function showAlert(msg) {
    $(".Alert").html(msg).show();
    setTimeout(function () {
        $(".Alert").hide();
    }, 2000);
}

function InitUpload() {
    var $file = $("#file");
    $file.on("change", function () {
        var formdata = new FormData();
        formdata.append("file", $file.prop("files")[0]);
        $.ajax({
            url: "/upload.php",
            method: 'POST',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        console.log(percentComplete);
                        $(".progressBar").width(percentComplete + "%");
                        if (percentComplete === 100) {

                        }

                    }
                }, false);

                return xhr;
            },
            success: function (data) {
                $(".progressBar").width(100 + "%");
                $(".progressBar").html(data.path);
                console.log(data, "TEST");
//                refreshPage();
            }
        });


    });
}


function refreshPage() {
    window.location.href = window.location.href;
}