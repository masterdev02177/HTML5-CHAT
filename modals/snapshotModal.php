<script src="./js/webcamRecorder.js?time=<?=time()?>"></script>
<style>
    .recorderButtonContainer img {
        width: 32px;
        height:32px;
    }

    video::-internal-media-controls-download-button {
        display:none;
    }

    video::-webkit-media-controls-enclosure {
        overflow:hidden;
    }

    video::-webkit-media-controls-panel {
        width: calc(100% + 30px); /* Adjust as needed */
    }

    video.videoPlayback {
        width: 100%;
        max-height: 300px;
        padding:5px;
    }

    #videoPlayBack, #videoRecord, #canvasDraw {
        width: 100%;
        height:auto;
        display: block;
        max-width: 320px;
    }
    #canvasDraw {
        display: none;
    }


    .recorderButtonContainer button {
        width: 100%;
        height: 80px;
        margin: 5px 5px;
    }

    #deleteMyVideoBtn {
        margin-bottom: 10px;
    }
    #countDown {
        min-width: 40px;
    }
    #uploadProgress {
        width: 0%;
        height: 10px;
        background: green;
    }
    i.fa-circle.red {
        color:red;
    }
    
</style>

<div class="modal fade" id="snapshotModal" data-keyboard="false" data-keyboard="false"  data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close']?></span></button>
                <h4 class="modal-title rooms-modal"><?=$traductions['Snapshot']?></h4>
                <div id="uploadProgress"></div>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div style="margin: 20px">
                        <label for="videoRadio">Video message</label>
                        <input type="radio" id="videoRadio" name="typeRecord" value="video" checked>&nbsp;&nbsp;&nbsp;

                        <label for="audioRadio">Audio message</label>
                        <input type="radio" id="audioRadio" name="typeRecord" value="audio">
                    </div>

                    <div class="col-xs-6" style="padding: 4px">
                        <video id="videoRecord" autoplay ></video>
                        <canvas id="canvasDraw"></canvas>
                        <br>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="pull-left btn btn-default" id="countDown">00:00</div>
                                <div style="text-align: left" class="pull-right">
                                    <button class="btn btn-info" type="button" id="recordBtn">Start</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <video id="videoPlayBack" autoplay ></video>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="uploadWebcamVideoBtn" type="button" class="btn btn-success" ><i class="fa fa-upload"></i> <?=$traductions['takeSnapshot']?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close']?></button>
            </div>
        </div>
    </div>
</div>
<script>
    var webcamRecorder;

    $('#audioRadio').click(function() {
        $('#videoRecord').hide();
        $('#canvasDraw').show();
        prepareRecorder();
    })

    $('#videoRadio').click(function() {
        $('#videoRecord').show();
        $('#canvasDraw').hide();
        prepareRecorder();
    })

    $('#snapshotModal').on('shown.bs.modal', function (e) {
        $('#uploadWebcamVideoBtn').hide();
        prepareRecorder();

    })

    function prepareRecorder() {
        closeRecorder();
        var video = ($('#audioRadio').prop('checked'))?false:{
            width: {width: 320, ideal: 320},
            height: {height: 240, ideal: 240},

        }
        var constraints = {
            audio: true,
            video: video
        }
        console.log('video', video);
        var canvas = document.querySelector('canvas');
        initRecorder(constraints, canvas);
    }

    $('#snapshotModal').on('hidden.bs.modal', function (e) {
        $('#uploadWebcamVideoBtn').hide();
        closeRecorder();
    })

    function initRecorder(constraints, canvas) {
        if (!webcamRecorder) {
            if (!constraints) {
                constraints = {
                    audio: true,
                    video: {
                        width: {width: 320, ideal: 320},
                        height: {height: 240, ideal: 240},
                        frameRate: {ideal: 15, max: 20}
                    }
                }
            }
            webcamRecorder = new WebcamRecorder(
                'video#videoRecord',
                'video#videoPlayBack',
                'button#recordBtn',
                '<i class="fa fa-circle red"></i> Start',
                '<i class="fa fa-stop"></i> Stop',
                30,
                'div#countDown',
                constraints,
                function() {
                    $('#uploadWebcamVideoBtn').show();
                },
                canvas
            )
        }
    }
    function closeRecorder() {
        if (webcamRecorder) {
            webcamRecorder.stop();
        }
        delete webcamRecorder;
        webcamRecorder = null;
    }

    $('#uploadWebcamVideoBtn').click(function() {
        $(this).hide();
        updloadWebcam('#uploadProgress');
    })
    function updloadWebcam(progressBarElement) {
        var filename = Date.now()+'.webm';
        var blob = webcamRecorder.getBlob();
        var file = new File([blob], filename, {
            type: 'video/webm'
        });

        var extensions = /avi|webm|mp4|mov|flv|wmv|video|video\/quicktime|quicktime|video\/quicktime$/i;
        var maxSize = 50 *1024 * 1024; // 50 megas
        upload(file, extensions , maxSize,
            function(video) {
                $('#snapshotModal').modal('hide');
                closeRecorder();
                $('#smileyContentLoader').hide();
                //$('#smileyContainer').slideToggle(100);
                video = JSON.parse(video);
                chatHTML5.setEndOfContenteditable();
                var html = sprintf('<img class="gif" src="%s" data-video="%s" >',  video.thumb, video.mp4);
                chatHTML5.emojiArea[0].emojioneArea.setHTML(html);
            }, function(evt) {
                console.log('ERROR', evt);
                bootbox.alert('UPLOAD ERROR.');
            },
            {   a:'upload',
                url:'/classes/Video.php',
                userid:chatHTML5.myUser.id,
                audioOnly:$('#audioRadio').prop('checked')
            },
            progressBarElement
        );
    }

    function upload(file, filter, maxSize, successEvent, errorEvent, formdata, progressBarElement) {
        //console.log('file:', file, filter);
        if (!filter.test(file.type) && file.type!='video/quicktime') {
            bootbox.alert("Error: incorrect file type");
            return;
        }
        if (file.size > maxSize) {
            bootbox.alert('Incorrect Max size:' + Math.round(maxSize / 1024) + 'KBytes');
            return;
        }
        var fileReader = new FileReader();
        fileReader.onload = function(){
        };

        fileReader.readAsDataURL(file);
        var fd = new FormData();
        fd.append('file', file);

        $.each(formdata, function(key, value) {
            fd.append(key, value);
        });
        $(progressBarElement).css('width', '0%');

        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', uploadProgress, false);
        xhr.addEventListener('load', uploadComplete, false);
        xhr.addEventListener('error', uploadFailed, false);
        xhr.addEventListener('abort', uploadFailed, false);
        xhr.open('POST', formdata.url);
        xhr.send(fd);

        function uploadFailed(evt) {
            console.log(evt);
            errorEvent(evt);
        }

        function uploadComplete(evt) {
            var data = (evt.target.response);
            $(progressBarElement).css('width', '0%');
            successEvent(data);
        }

        function uploadProgress(e) {
            if (e.lengthComputable) {
                var percentComplete = Math.round(e.loaded * 100 / e.total);
                $(progressBarElement).css('width', percentComplete + '%');
            }
            else {
                errorEvent();
            }
        }
    }

</script>