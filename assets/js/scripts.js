"use strict";
var config = {
    api:null,
    getApiPath:function(uri){
        if(this.api==null){
            var baseURI = $("head base").attr("href");
            this.api = baseURI;
        }
        return this.api + uri;
    }
}
$(document).ready(initApp);
var timeSinceInterval = null;
var dragCounter = 0;
var uploadAjaxCall = null;
var toastTimeout = null;
function initApp(){
$(document).on("dragenter",dragStarted);
$(document).on("dragleave",dragEnded);
$(document).on("drop",droppedFile);
$("#upload-input-element").on("change",userSelectedFile);
$(".file-upload-box").on("click",function(){
    if(uploadAjaxCall===null){
        $("#upload-input-element").click();
    }
});
$("#upload-progress .cancel").on("click",function(e){
    e.stopPropagation();
    if(uploadAjaxCall){
        uploadAjaxCall.abort();
    }
});
updateRecentList();
}

function dragStarted(e) {
    e.preventDefault();
    e.stopPropagation();
    
    if (dragCounter++ === 0) {
        console.log("Drag Started")
        $(".overlay-upload").show();
    }
        
}

function dragEnded(e) {
    e.preventDefault();
    e.stopPropagation();
    if (--dragCounter === 0) {
        console.log("Drag Ended")
        $(".overlay-upload").hide()
    }     
}

function dragLeave(e){
    console.log("Drag Left")
    e.preventDefault();
    e.stopPropagation();
    
}


function droppedFile(e){
    console.log("Called",e);
    $(".overlay-upload").hide();
    dragCounter=0;
    if(e.originalEvent.dataTransfer){
        if(e.originalEvent.dataTransfer.files.length) {
            e.preventDefault();
            e.stopPropagation();
            /*UPLOAD FILES HERE*/
            uploadStart(e.originalEvent.dataTransfer.files);
        }   
    }
}

function userSelectedFile(){
    
    if(this.files.length>0){
        uploadStart(this.files);
        $(this).val(null);
    }
}

function showUploadProgress(show){
    if(show){
        $("#upload-progress").show();
        $("#upload-add-btn").hide();        
        
    }else{
        $("#upload-progress").hide();
        $("#upload-add-btn").show();
        uploadAjaxCall = null;
    }
}


function uploadStart(files){

    showUploadProgress(true);


    var formData = new FormData();

    // add assoc key values, this will be posts values
    formData.append("file", files[0]);
    
    uploadAjaxCall = $.ajax({
        type: "POST",
        url: config.getApiPath("uploads.php"),
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', uploadProgressHandling, false);
            }
            return myXhr;
        },
        success: function (data) {
            console.log(data);
            if(data.code===1){
                refreshRecentUploadList();
            }
            showToastMessage(data.message);
            showUploadProgress(false);
        },
        error: function (error) {
            console.log("error",error);
            showUploadProgress(false);
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 15*60000,
        dataType:"json"
    });
}

function uploadProgressHandling(progress){
    var progressPercentage = progress.loaded/progress.total*100;
    $(".progress-active").width(progressPercentage+"%");
}

function refreshRecentUploadList(){
    updateRecentList();
}

function updateRecentList(){
    $.ajax({
        type: "POST",
        url: config.getApiPath("recentlist.php"),
        dataType:"json",
        success: function (data) {
            updateRecentListUI(data);
        },
        error: function (error) {
            console.log("error",error)
        },
        async: true
    });
    
}

function updateRecentListUI(data){
    $("#file-list-group").html("");
    clearInterval(timeSinceInterval);
    for(var i=0;i<data.list.length;i++){
        var item = data.list[i];
        var $itemUI = getItemUI(item);    
        $("#file-list-group").append($itemUI);
    }
    timeSinceUpdater();
    timeSinceInterval = setInterval(timeSinceUpdater,4000);
}

function getItemUI(item){
    var extention = getFileExtention(item.name);
    var $template = $($("#file-list-template").html());
    $template.find(".name").html(item.name);
    $template.find(".ext").html(extention);
    $template.find(".size").html(formatBytes(item.size));
    $template.find(".time-ago").data("time",item.date.time);
    $template.find(".fa-download").click(function(){
        showToastMessage("Downloading...");
        window.location.href = item.download;
    });
    $template.find(".fa-trash").click(function(){
        deleteItem(item);
    });
    $template.find(".name").click(function(){
        $("#input-dummy").val(item.download);
        $("#input-dummy").select();
        document.execCommand("Copy");
        showToastMessage("Copied URL");
    });
    return $template;
}

function deleteItem(item){
    $.ajax({
        type: "POST",
        url: "/delete.php",
        data:item,
        dataType:"json",
        success: function (data) {
            showToastMessage(data.message);
            updateRecentList();
        },
        error: function (error) {
            console.log("error",error)
        },
        async: true
    });
    
}

function timeSinceUpdater(){
    $(".time-ago").each(function(){
        var $this = $(this);
        var timeInt = parseInt($this.data("time"))*1000;
        $this.html(timeSince(timeInt));
    })
}

function getFileExtention(name){
    var ext = "other";
    if(name){
        ext = name.split(".").pop();
    }

    return ext.toUpperCase();
}
/*
 *
 * @author sky-sanders
 * @authorurl https://stackoverflow.com/users/242897/sky-sanders
 * @see https://stackoverflow.com/a/3177838/3091574
 */
function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);
  
    var interval = Math.floor(seconds / 31536000);
  
    if (interval > 1) {
      return interval + " years";
    }
    interval = Math.floor(seconds / 2592000);
    if (interval >= 1) {
      return interval + " months";
    }
    interval = Math.floor(seconds / 86400);
    if (interval >= 1) {
      return interval + " days";
    }
    interval = Math.floor(seconds / 3600);
    if (interval >= 1) {
      return interval + " hours";
    }
    interval = Math.floor(seconds / 60);
    if (interval >= 1) {
      return interval + " minutes";
    }
    return "just now";
  }
  /*
   * @author aliceljm
   * @authorurl https://stackoverflow.com/users/1596799/aliceljm
   * @see https://stackoverflow.com/a/18650828/3091574
   */
  function formatBytes(bytes,decimals) {
    if(bytes == 0) return '0 Bytes';
    var k = 1024,
        dm = decimals || 2,
        sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
 }

 function showToastMessage(msg){
    clearTimeout(toastTimeout);
    $(".toast-message").html(msg).show();
    toastTimeout = setTimeout(function(){
        $(".toast-message").hide();   
    },3000);
 }