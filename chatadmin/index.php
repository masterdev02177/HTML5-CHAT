<?php
session_start();
include_once '../classes/DB.php';
if (isset($_REQUEST['webmasterid']) && isset($_REQUEST['password'])) {
	$admin = DB::getOne('chat_webmaster', "WHERE id={$_REQUEST['webmasterid']} and password='{$_REQUEST['password']}'",false);
	if ($admin) {
		$_SESSION['admin'] = $admin->id;
		exit;
	}

}

$email = isset($_REQUEST['email']) && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)?$_REQUEST['email']:'';
if ($email) {
	$refererer = $_SERVER['HTTP_REFERER'];
	$parts = parse_url($refererer);
	$res = User::loginWPAdmin($email, $parts['host']);
	//print_r($res);exit();
	if ($res) {
		$_SESSION['admin'] = $res->id;
		$_SESSION['role'] = 'admin';

	}
	//die("$email {$parts['host']}");//yarekc@gmail.com server2.buychatroom.com


}


if (isset($_SESSION['admin'])) {
	header('Location:loggedon.php');
}
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/login.min.css">

	<style>
		.forgot-password {
			font-size:0.9em;
		}
		#loginBtn {
			cursor: pointer;
		}
	</style>

	<?php include 'js.php'; ?>

	<title>Chat Admin panel </title>

</head>

<body>

    <div class="container">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="./img/avatars/admin.svg" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="email" id="email" autocomplete="off" class="form-control" placeholder="Your email address" required autofocus value="<?=$email?>">
                <input type="password" id="password" autocomplete="off" class="form-control" placeholder="Password" required>

                <button class="btn btn-lg btn-primary btn-block btn-signin" id="loginBtn" type="button">Login</button>
            </form>
            <!-- /form -->
                       
        </div>
    </div>
<script>

	$('#password').on('keypress',  function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			$('#loginBtn').click();
		}
	});

	$('#loginBtn').click(function(e) {
    var email = $('#email').val();
	if (!email) {
		return;
	}

	var password = $('#password').val();
	$.post('../ajax.php', {a:'loginPanel', email:email, password:password}, function(res) {
		if (res==='ko') {
			bootbox.alert('Username or password are incorrect !');
			return;
		}
		if (res==='notConfirmed') {
			bootbox.alert('Your account has not been confirmed yet : please check your email box.');
			return;
		}
		
		window.location = 'loggedon.php';
	});
});

	$('#forgottenLink').click(function(e) {
    bootbox.prompt({
		title:'Enter your email',
		value:'<?=$email?>',
		callback:function(email) {
			if (!email) {
				return;
			}
			$.post('../ajax.php', {a:'forgottenWebmaster', email:email}, function(res) {
				console.log(res);
				if (res==='ko') {
					bootbox.alert('Sorry, no such email in our database');
				} else {
					bootbox.alert('An email has been sento to ' + email);
				}
			});
		}
	});
});

	$('#resendConfirmation').click(function(e) {
    bootbox.prompt('Enter your email', function(email) {
		if (!email) {
			return;
		}
		$.post('../ajax.php', {a:'resendConfirmation', email:email}, function(res) {
			if (res==='ko') {
				bootbox.alert('Sorry, no such email in our database');
			} else {
				bootbox.alert('An email has been sento to ' + email);
			}
		});
	});
});
</script>


</body>
</html>