<style>
.backgroundThumb {
    width: 90px;
    height:auto;
    cursor: pointer;
    border: 1px solid #CCC;
    margin: 2px;
}
.backgroundThumb:hover {
    border: 1px solid #444;
}
    #closeBackhroundsBtn {
        cursor: pointer;
        margin-right: 5px;
    }
</style>
<div id="backgroundsContainer">
    <div id="backgroundsheader">
        <?=$traductions['Backgrounds'];?>
        <div class="pull-right"><i id="closeBackhroundsBtn" class="fa fa-close"></i></div>
    </div>
    <div id="backgroundsContent" style="max-height: 150px;overflow-x: hidden;overflow-y: auto">
        <?php $backgroundImages = Background::getAll();
        foreach($backgroundImages as $backgroundImage):?>
            <img src="<?=$backgroundImage['thumb']?>" data-original="<?=$backgroundImage['image']?>" alt="" class="backgroundThumb">
        <?php endforeach?>
    </div>
    <div>
        <label for="youtubeDisableCheckbox"><?=$traductions["Disable youtube"];?></label>
        <input type="checkbox" name="youtubeDisableCheckbox" id="youtubeDisableCheckbox">
    </div>


</div>
<script>
$('#closeBackhroundsBtn').click(function(e) {
    $('#backgroundsContainer').slideToggle(100);
});
$('#backgroundsContent').on('click', 'img', function(e) {
    var originalSrc = $(this).data('original');
    if (originalSrc=='/backgrounds/0.jpg') {
        localStorage.removeItem('background');
        $('div.tab-pane').css('background-image', '');
        return;
    }

    var temp = sprintf('url(%s)', originalSrc);
    $('div.tab-pane').css('background-image', temp);
    localStorage.setItem('background', temp);
})


</script>