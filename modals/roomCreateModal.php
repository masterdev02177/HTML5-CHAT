<div class="modal fade" id="roomCreateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close']?></span></button>
        <h4 class="modal-title"><?=$traductions['createNewRoom']?></h4>
        
      </div>
      <div class="modal-body">
      	<div >
            <label for="roomNewNameInput"><?=$traductions['enterNameOfRoom']?></label>
        	<input id="roomNewNameInput" pattern="[A-Za-z]{3,25}"  maxlength="25" required placeholder="<?=$traductions['enterNameOfRoom']?>" class="form-control">

          <div class="form-group" style="margin-top: 20px">
            <label for="reservedToGenderid"><?=$traductions['Reserved to gender']?></label>
            <select class="form-control" id="reservedToGenderid">
              <option value="0"><?=$traductions['NotReserverToGender'];?></option>
              <?php foreach($genders as $gender): ?>
                <option value="<?=$gender->id?>"><?=$gender->gender?></option>
              <?php endforeach?>
            </select>
          </div>

          <div class="form-group" style="margin-top: 20px">
            <label for="reservedToRoles"><?=$traductions['Reserved to role']?></label>
            <select class="form-control" id="reservedToRoles">
              <option value="0"><?=$traductions['NotReserverToRole'];?></option>
              <?php foreach($roles as $role): ?>
                <option value="<?=$role->id?>"><?=$role->role?></option>
              <?php endforeach?>
            </select>
          </div>



          <div style="margin:5px;padding:20px">
                <label class="radio-inline">
                  <input name="inlineRadioOptions" type="radio" id="roomPublicRadio" checked="checked" > <?=$traductions['publicRoom']?>
              </label>
                <label class="radio-inline">
                  <input type="radio" name="inlineRadioOptions" id="roomPrivateRadio" > <?=$traductions['privateRoom']?>
                </label>
                <div id="divPasswordRoomCreate" style="display:none; margin-top: 10px;">
                  <input id="roomNewPassword" type="password"  placeholder="<?=$traductions['enterYourPassword']?>" class="form-control">
                </div>
            </div>
                        
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close']?></button>
        <button type="button" id="createNowRoomBtn" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> <?=$traductions['createRoom']?></button>
      </div>

    </div>
  </div>
</div>

