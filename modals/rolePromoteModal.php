<div class="modal fade" id="rolePromoteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close']?></span></button>
        <h4 class="modal-title"><?=$traductions['promoteUser']?></h4>
      </div>
      <div class="modal-body">

      	<div id="promoteUserUsername" ></div>
        <div class="form-group" style="margin-top: 20px">
            <label for="promoteUserRoleid"><?=$traductions['promoteUser']?></label>
            <select class="form-control" id="promoteUserRoleSelect">
              <?php foreach($roles as $role): ?>
                <option value="<?=$role->id?>"><?=$role->role?></option>
              <?php endforeach?>
            </select>
          </div>

        <div class="form-group">
          <label for="permanentPromoteCheckbox"><input type="checkbox" id="permanentPromoteCheckbox" value="1"><?=$traductions['PermanentPromote']?></label>
        </div>

      </div>
        

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close']?></button>
        <button type="button" id="rolePromoteBtn" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> <?=$traductions['promoteUser']?></button>
      </div>

    </div>
  </div>
</div>
<script>


$('#rolePromoteBtn').click(function() {
  var roleid = $('#promoteUserRoleSelect').val();
  var role = $("#promoteUserRoleSelect option:selected").text();
  var userid = $('#rolePromoteModal').data('userid');
  var roomid = chatHTML5.myUser.room.id;
  var permanent = $('#permanentPromoteCheckbox').prop('checked');
  chatHTML5.promoteRole({userid:userid,  roomid:roomid, roleid:roleid, role:role, permanent:permanent});
  $('#rolePromoteModal').modal('hide');
})

</script>
