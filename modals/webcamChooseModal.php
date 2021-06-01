<div class="modal fade" id="webcamChooseModal" data-keyboard="false" data-keyboard="false"  data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close']?></span></button>
                <h4 class="modal-title rooms-modal"><?=$traductions['chooseWebcam']?></h4>
            </div>
            <div class="modal-body">
                <div>
                    <div>
                        <label for="videoSelect"><?=$traductions['Video']?></label>
                        <select name="videoSelect" id="videoSelect" class="form-control"></select>
                    </div>

                    <div style="margin-top: 40px">
                        <label for="audioSelect"><?=$traductions['Audio']?></label>
                        <select name="audioSelect" id="audioSelect" class="form-control"></select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="chooseWebcamBtn" type="button" class="btn btn-success" ><i class="fa fa-check"></i> <?=$traductions['chooseWebcam']?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close']?></button>
            </div>
        </div>
    </div>
</div>
