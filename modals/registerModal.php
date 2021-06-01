<div class="modal fade" id="registerModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$traductions['close'];?></span></button>
        <h4 class="modal-title"><?=$traductions['register'];?></h4>
        
      </div>
        <form>
            <div class="modal-body">
          <div class="form-group">
                <label for="usernameRegister"><?=$traductions['username'];?></label>
                <input id="usernameRegister" pattern="[a-zA-Z][a-zA-Z0-9_\-\.]*" required maxlength="50"  placeholder="<?=$traductions['enterYourUsername'];?>" class="form-control">
          </div>
          
          <div class="form-group">
                <label for="emailRegister"><?=$traductions['email'];?></label>
                <input id="emailRegister"type="email" required maxlength="50"  placeholder="<?=$traductions['enterYourEmail'];?>" class="form-control"> 
          </div>          
      
          <div class="form-group">
                <label for="passwordRegister"><?=$traductions['password'];?></label>
                <input id="passwordRegister" type="password" maxlength="50" required placeholder="<?=$traductions['enterYourPassword'];?>" class="form-control">  
          </div>

          <div class="form-group">
                <label for="passwordRegister2"><?=$traductions['confirmPassword'];?></label>
                <input id="passwordRegister2" type="password" maxlength="50" required placeholder="<?=$traductions['confirmPassword'];?>" class="form-control">  
          </div>

                <div class="form-group">
                    <label for="ageRegister"><?=$traductions['yourAge'];?></label>
                    <input id="ageRegister" type="number" min="13" max="99" required placeholder="age" class="form-control" value="30">
                </div>


           	<div class="form-group">
            <?php //print_r($genders); ?>
           	<?php $i=0; ?>
				<?php foreach($genders as $gender): ?>
					<div class="radio radio-primary radio-inline">
                        <input type="radio" id="registerGender<?=$gender->gender?>" value="<?=$gender->id?>" <?php if(!$i) echo 'checked';?> name="registerGender" >
                        <label for="registerGender<?=$gender->gender?>"> 
                        	
                            <?php if($gender->image): ?>
                            	<img src="/upload/genders/<?=$gender->image?>"/>
                            <?php endif?>
                        	<span style="color:<?=$gender->color?>"><?=$gender->gender?></span>
                        </label>
                    </div>
				<?php $i++; ?>                    
              	<?php endforeach; ?>
           	</div>        
                  
        
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$traductions['close'];?></button>
        <button type="button" id="registerBtn" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span><?=$traductions['register'];?></button>
      </div>

    </div>
  </div>
</div>

<script>

$('#registerBtn').click(function(e) {
    var username = $('#usernameRegister').val().trim();
	var email = $('#emailRegister').val();
	var password = $('#passwordRegister').val();
	var password2 = $('#passwordRegister2').val();
	var gender = $('input[name=registerGender]:checked').val();
    var age = $('#ageRegister').val();

    if (!username.match(/^[^<\/\/'"\?\!\,\%\*\$\`\@\#\)\(\=\+\;&>]+$/) || username.length<3 || username.length>20) {
        bootbox.alert(chatHTML5.traductions.invalidUsername);
        return;
    }

    /*if (!username.match(/^([a-zA-Z0-9\s_\-\.]{3,20})$/)) {
		bootbox.alert(chatHTML5.traductions.invalidUsername);
		return;
	}*/

	if (!password.match(/^([a-zA-Z0-9\s_\-\.]{3,20})$/)) {
		bootbox.alert(chatHTML5.traductions.invalidPassword);
		return;
	}		
	if (!email.match(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i)) {
		bootbox.alert(chatHTML5.traductions.invalidEmail);
		return;
	}
	if (password!==password2) {
		bootbox.alert(chatHTML5.traductions.passwordDoNotMatch);
		return;		
	}
    if (!age) {
        bootbox.alert(chatHTML5.traductions.invalidAge);
        return;
    }

	$.post('/ajax.php', {a:'registerUser', username:username, email:email, password:password, gender:gender, age:age}, function(res) {
		res = JSON.parse(res);
		bootbox.alert(res.message);
		if (res.result==='ok') {
			$('#registerModal').modal('hide');
		}
	});
});

</script>
