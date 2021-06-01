<style>
    #smileyContainer li {
        font-size: 0.75em;
    }
    #smileyContentLoader {
        background-color: white;
        opacity: 0.75;
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
        /* display: block; */
    }
    #gifContainer {
        width: 100%;
        height: 150px;
        overflow-x: hidden;
        overflow-y: auto;
        position: relative;
        margin-top: 20px;
        display: none;
    }
    

</style>
<div id="smileyContainer">
    <div id="smileysHeader">
        <?=$traductions['Gifs and upload'];?>
        <div class="pull-right"><i id="closeSmileysBtn" class="fa fa-close"></i></div>
    </div>
    <div>
        <input type="text" id="searchGifInput" placeholder="<?=$traductions['Search']?>..." class="input-sm">
        <div id="progressUploadPicture"></div>
        
            <label id="uploadImageBtnLabel" for="uploadImageFile"><i class="fa fa-upload" title="upload Image"></i></label>
            <input type="file" name="uploadImageFile" id="uploadImageFile" style = 'display:none;'>
        
    </div>

    <div id="gifContainer" >
        <div id="gifsContent"></div>
    </div>


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