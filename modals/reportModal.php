<!-- Modal -->
<div class="modal fade dark-modal light-modal" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close'];?></span></button>
        <h4 id="reportModalTitle" class="modal-title"><?=$traductions['Report'];?></h4>

      </div>
      <div class="modal-body">

        <div class="form-group">
          <label for="reportEmail"><?=$traductions['enterYourEmail'];?></label>
          <input id="reportEmail" type="text" required class="form-control" value="<?php echo (@$myuser['email'])?$myuser['email']:'' ?>">
        </div>

          <div class="form-group">
              <label for="reportReason"><?=$traductions['reason'];?></label>
              <select name="reportReason" id="reportReason" class="form-control">
                  <option value="<?=$traductions['userReason1'];?>"><?=$traductions['userReason1'];?></option>
                  <option value="<?=$traductions['userReason2'];?>"><?=$traductions['userReason2'];?></option>
                  <option value="<?=$traductions['userReason3'];?>"><?=$traductions['userReason3'];?></option>
                  <option value="<?=$traductions['userReason4'];?>"><?=$traductions['userReason4'];?></option>
                  <option value="<?=$traductions['userReason5'];?>"><?=$traductions['userReason5'];?></option>
              </select>
          </div>

        <div class="form-group">
        	<label for="reportDescription"></label>
	        <textarea id="reportDescription" required placeholder="<?=$traductions['Report Description'];?>" class="form-control"></textarea>
        </div>



      </div>
      <div class="modal-footer">
	    <button type="button" id="reportBtn" class="btn btn-danger" data-dismiss="modal"><?=$traductions['Report'];?></button>
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

$('#reportBtn').click(function(e) {
    var email = $('#reportEmail').val();
    var description = $('#reportDescription').val();
    if (description.length<5 || !validateEmail(email))  {
        return false;
    }
    var reportReason = $('#reportReason').val();
    chatHTML5.reportUser(chatHTML5.reportUsername, email, description, reportReason);
});
</script>
