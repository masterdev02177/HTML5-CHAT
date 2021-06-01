<div class="modal fade dark-modal light-modal" id="muteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close'];?></span></button>
        <h4 class="modal-title" id="muteTitle"><?=$traductions['muteUser'];?></h4>

      </div>
      <div class="modal-body">

        <div class="form-group">
          <label for="minutesMute"><?=$traductions['chooseDurationOfMuteInMinutes'];?></label>
          <input type="hidden" value="30" id="minutesMute" class="form-control">
        </div>

        <div>
          <button class="banClass btn btn-default" data-hour="0.5">30 <?=$traductions['minutes'];?></button>
          <button class="banClass btn btn-default" data-hour="1">1 <?=$traductions['hour'];?></button>
          <button class="banClass btn btn-default" data-hour="4">4 <?=$traductions['hours'];?></button>
          <button class="banClass btn btn-default" data-hour="8">8 <?=$traductions['hours'];?></button>
          <button class="banClass btn btn-default" data-hour="12">12 <?=$traductions['hours'];?></button>
          <button class="banClass btn btn-default" data-hour="24">1 <?=$traductions['day'];?></button>
          <button class="banClass btn btn-default" data-hour="168">1 <?=$traductions['week'];?></button>
          <button class="banClass btn btn-default" data-hour="672">1 <?=$traductions['month'];?></button>
        </div>

        <div class="form-group">
          <label for="muteDescription"></label>
          <textarea id="muteDescription" placeholder="<?=$traductions['explainWhyYouMute'];?>" class="form-control"></textarea>
        </div>

        <div class="form-group">
          <label for="warnUserOfMute"><?=$traductions['warnUserOfMute']?></label>
          <input type="checkbox" name="warnUserOfMute" id="warnUserOfMute">
        </div>

        <label class="control-label"><?=$traductions['JailUntil']?></label>
        <div class='input-group date' id='jailUntilPicker' >
          <input type='text' class="form-control" />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="mutePrisonBtn" class="btn btn-danger" data-dismiss="modal"><?=$traductions['mute'];?></button>
        <button type="button" class="btn btn-success" data-dismiss="modal"><?=$traductions['close'];?></button>

      </div>

    </div>
  </div>
</div>
<script>
  var dateNow = new Date();
  var newDateObj = moment(dateNow).add(60, 'm').toDate();
  $('#jailUntilPicker').datetimepicker({
      format: 'LLLL',
    locale: '<?=$config->langue?>',
    defaultDate:newDateObj,
  }).on('dp.change', function(e){
      var newDate = e.date;
      var dateNow = new Date();
      var duration = moment.duration(newDate.diff(dateNow));
      var minutes = parseInt(duration.asMinutes());
      $('#minutesMute').val(minutes);
  })

  $('#muteModal button[data-hour]').click(function() {
    var hours = parseInt($(this).data('hour'));
    var minutes = hours * 60;
    var dateNow = new Date();
    var newDateObj = moment(dateNow).add(minutes, 'm').toDate();
    $('#jailUntilPicker').data("DateTimePicker").date(newDateObj);
  })
</script>

