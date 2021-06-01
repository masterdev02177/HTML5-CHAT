<div class="modal fade dark-modal light-modal" id="banModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close'];?></span></button>
        <h4 class="modal-title" id="banChatTitle"><?=$traductions['banUser'];?></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="minutes"><?=$traductions['chooseDurationIfTheBanInMinutes'];?></label>
            <input type="hidden"  value="60"  id="minutes" class="form-control">
        </div>
        <div>
          <button class="banClass btn btn-default" data-hour="1">1 <?=$traductions['hour'];?></button>
          <button class="banClass btn btn-default" data-hour="4">4 <?=$traductions['hours'];?></button>
          <button class="banClass btn btn-default" data-hour="8">8 <?=$traductions['hours'];?></button>
          <button class="banClass btn btn-default" data-hour="12">12 <?=$traductions['hours'];?></button>
          <button class="banClass btn btn-default" data-hour="24">1 <?=$traductions['day'];?></button>
          <button class="banClass btn btn-default" data-hour="168">1 <?=$traductions['week'];?></button>
          <button class="banClass btn btn-default" data-hour="672">1 <?=$traductions['month'];?></button>
        </div>

        <div class="form-group">
        	<label for="banDescription"></label>
	        <textarea id="banDescription" placeholder="<?=$traductions['explainWhyYouBan'];?>" class="form-control"></textarea>
        </div>

        <label class="control-label"><?=$traductions['BanUntil']?></label>
        <div class='input-group date' id='banUntilPicker' >
          <input type='text' class="form-control" />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>

      </div>
      <div class="modal-footer">
	    <button type="button" id="banBtn" class="btn btn-danger" data-dismiss="modal"><?=$traductions['ban'];?></button>
        <button type="button" class="btn btn-success" data-dismiss="modal"><?=$traductions['close'];?></button>
      </div>
    </div>
  </div>
</div>
<script>
  var dateNow = new Date();
  var newDateObj = moment(dateNow).add(60, 'm').toDate();
  $('#banUntilPicker').datetimepicker({
    format: 'LLLL',
    locale: '<?=$config->langue?>',
    defaultDate:newDateObj,
  }).on('dp.change', function(e){
    var newDate = e.date;
    var dateNow = new Date();
    var duration = moment.duration(newDate.diff(dateNow));
    var minutes = parseInt(duration.asMinutes());
    $('#minutes').val(minutes);
    console.log('minutes', minutes);
  })


  $('#banModal button[data-hour]').click(function() {
    var hours = parseInt($(this).data('hour'));
    var minutes = hours * 60;
    var dateNow = new Date();
    var newDateObj = moment(dateNow).add(minutes, 'm').toDate();
    $('#banUntilPicker').data("DateTimePicker").date(newDateObj);
  })
</script>