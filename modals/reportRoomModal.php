<!-- Modal -->
<div class="modal fade dark-modal light-modal" id="reportRoomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close'];?></span></button>
        <h4 id="reportModalTitle" class="modal-title"><?=$traductions['Report'];?></h4>

      </div>
      <div class="modal-body">

        <div class="form-group">
          <label for="reportRoomEmail"><?=$traductions['enterYourEmail'];?></label>
          <input id="reportRoomEmail" type="text" required class="form-control" value="<?php echo (@$myuser['email'])?$myuser['email']:'' ?>">
        </div>

          <div class="form-group">
              <label for="reportRoomReason"><?=$traductions['reason'];?></label>
              <select name="reportRoomReason" id="reportRoomReason" class="form-control">
                  <option value="<?=$traductions['roomReason1'];?>"><?=$traductions['roomReason1'];?></option>
                  <option value="<?=$traductions['roomReason2'];?>"><?=$traductions['roomReason2'];?></option>
                  <option value="<?=$traductions['roomReason3'];?>"><?=$traductions['roomReason3'];?></option>
                  <option value="<?=$traductions['roomReason4'];?>"><?=$traductions['roomReason4'];?></option>
                  <option value="<?=$traductions['roomReason5'];?>"><?=$traductions['roomReason5'];?></option>
              </select>
          </div>

        <div class="form-group">
        	<label for="reportRoomDescription"></label>
	        <textarea id="reportRoomDescription" required placeholder="<?=$traductions['Report Description'];?>" class="form-control"></textarea>
        </div>


      </div>
      <div class="modal-footer">
	    <button type="button" id="reportRoomBtn" class="btn btn-danger" data-dismiss="modal"><?=$traductions['Report'];?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close'];?></button>

      </div>

    </div>
  </div>
</div>
<script>
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
</script>
