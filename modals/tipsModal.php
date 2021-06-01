<style>
  div.tips-row {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .small-btn {
    width: 19%;
    font-size: 0.9em;
  }
  #giveCreditsCustomizedBtn {

    margin-left: 10px;
  }
  #tipsModal .modal-body.tips-body button {
    background-color: #ff0142;
    border: none;
    color: white;
    padding: 4px;
    border-radius: 4px;
  }

  @media only screen and (max-width : 600px) {
    .small-btn {
      width: 18%;
      font-size: 0.7em;
    }
  }

</style>
<div class="modal fade" id="tipsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header tips-header">

        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close']?></span></button>
        <h4 class="modal-title" id="subject" >
            <?=$traductions['sendTips']?>
        </h4>

      </div>
      <div class="modal-body tips-body">
      <div><?=$traductions['chooseAmoutOfTips']?>:</div>
        <br>
      <button class="small-btn" data-credits=1><i class="glyphicon glyphicon-gift"></i> 1 <?=$traductions['credits']?></button>
      <button class="small-btn" data-credits=5><i class="glyphicon glyphicon-gift"></i> 5 <?=$traductions['credits']?></button>
      <button class="small-btn" data-credits=10><i class="glyphicon glyphicon-gift"></i> 10 <?=$traductions['credits']?></button>
      <button class="small-btn" data-credits=50><i class="glyphicon glyphicon-gift"></i> 50 <?=$traductions['credits']?></button>
      <button class="small-btn" data-credits=100><i class="glyphicon glyphicon-gift"></i> 100 <?=$traductions['credits']?></button>
      <hr>
      <div class="tips-row">
        <label for="inputAmountTips"><?=$traductions['orGive']?> : </label>
        <input style="width:70px" type="number" value="10" step="10" min=10 max=500 id="inputAmountTips" placeholder="<?=$traductions['sendTips']?>">
        <button id="giveCreditsCustomizedBtn" title=""> <?=$traductions['creditsImmediatly']?></button>
      </div>

      </div>
      <div class="modal-footer tips-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close']?></button>
      </div>
    </div>
  </div>
</div>
<script>


$('button[data-credits]').click(function(e) {
	var credits = $(this).data('credits');
	chatHTML5.sendCredits(credits);
	$('#tipsModal').modal('hide');
});

$('#giveCreditsCustomizedBtn').click(function(e) {
 	var credits = $('#inputAmountTips').val();
	if (credits<10) {
        bootbox.alert("<?=$traductions['youMustGiveMinimumCredits']?>");
		return;
	}
    chatHTML5.sendCredits(credits);
	$('#tipsModal').modal('hide');
});

$('#giveCreditsCustomizedBtn2').click(function(e) {
    var totalCredits = $('#inputAmountTips2').val();
    var givenCredits = 0;
      if (totalCredits<10) {
          bootbox.alert("<?=$traductions['youMustGiveMinimumCredits']?>");
        return;
      }
  $('#tipsModal').modal('hide');
});
</script>