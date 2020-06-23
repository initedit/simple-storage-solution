<div class="app">
    <div class="brand-name">
        <a href="https://github.com/initedit/">SSD</a>  
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 padding-none">
                <div class="file-upload-box">
                    <input type="text" id="input-dummy" class="input-dummy">
                    <div class="center-add">
                        <i class="fa fa-plus" id="upload-add-btn"></i>
                        <div class="upload-progress display-none" id="upload-progress">
                            <div>Uploading...</div>
                            <div class="progress-bar">
                                <div class="progress-active"></div>
                            </div>
                            <button class="btn btn-default btn-sm cancel">close</button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-sm-6 padding-none">
                <div class="list-group" id="file-list-group"></div>
                <div id="file-list-template" class="display-none">
                    <div class="item list-group-item list-group-item-action">
                        <span class="name"></span>
                        <span class="ext badge badge-secondary"></span>    
                        <i class="fa fa-curl curl-copy badge badge-secondary"> CURL </i>
                        <i class="fa fa-download float-right ml-2"></i>                    
                        <i class="fa fa-trash float-right"></i>                    
                        <br/>
                        <span class="small time-ago"></span>
                        <span class="float-right size"></span>

                    </div>    
                </div>
                <div class="toast-message display-none"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid overlay-upload">
        <div class="row">
            <div class="col-sm-12">
                <input type="file" id="upload-input-element" class="upload-input"/>
            </div>
        </div>
    </div>
</div>