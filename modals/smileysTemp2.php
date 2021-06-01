<style>
    #smileyContainer li {
        font-size: 0.75em;
    }
    #smileyContentLoader {
        background-color: white;
        opacity: 0.9;
        position: absolute;
        top: 60px;
        left: 0px;
        width: 100%;
        height: calc(100% - 60px);
        padding: 60px;
        display: none;
    }
    #smileyContentSpinner {

    }
    #uploadImageBtnLabel {
        cursor: pointer;
    }

    #uploadImageFile {
        display: none;
    }

</style>
<div id="smileyContainer">
    <div id="smileysHeader">
        Gifs and Upload
        <div class="pull-right"><i id="closeSmileysBtn" class="fa fa-close"></i></div>
    </div>
    <div>
        <input type="text" id="searchGifInput" placeholder="<?=$traductions['Search'];?>..." class="input-sm">
        <?php if ($config->uploadImages=='1'): ?>
            <label id="uploadImageBtnLabel" for="uploadImageFile"><i class="fa fa-upload" title="upload Image"></i></label>
            <input type="file" name="uploadImageFile" id="uploadImageFile" >
        <?php endif?>

    </div>



    <ul class="nav nav-tabs">
        <li class="active"><a href="#smileysContent" data-toggle="tab">Sticker</a></li>
        <li><a href="#gifsContent" data-toggle="tab">Gifs</a></li>
    </ul>

    <div class="tab-content" style="width: 100%;height: 150px;">
        <div class="tab-pane fade in active" id="smileysContent"></div>
        <div class="tab-pane fade" id="gifsContent"></div>
    </div>

    <!--<ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#smileysContent">Stickers</a></li>
        <li><a data-toggle="tab" href="#gifsContent">Gifs</a></li>
    </ul>

    <div class="tab-content" style="width: 100%;height: 150px;">
        <div id="smileysContent" class="tab-pane fade in active" style="height: 100%;overflow-y: auto">
        </div>
        <div id="gifsContent" class="tab-pane fade" style="height: 100%;overflow-y: auto">
        </div>
    </div>

-->


    <div id="smileyContentLoader">
        <div id="smileyContentSpinner">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
            <span class="sr-only">Loading...</span>

        </div>
    </div>

</div>
<script>
$('#closeSmileysBtn').click(function(e) {
    $('#smileyContainer').slideToggle(100);
});
</script>