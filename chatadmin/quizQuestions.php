<?php
session_start();
include ("../Config.php");
include_once('protect.php');
checkCanEnter('adminpanelQuiz');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
include("xcrud/xcrud.php");


$xcrud = Xcrud::get_instance();
$table = 'chat_quiz_questions';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('question, answer, tolerance');
$xcrud->fields('question, answer, tolerance');

$xcrud->field_tooltip('question','question', 'tolerance');
$xcrud->field_tooltip('answer','answer', 'tolerance');



//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include('css.php');?>
    <?php include('js.php');?>

    <title>Quiz questions/answers</title>
</head>
 
<body>
 <div class="panel panel-default" style="max-width: 800px;margin: auto;    box-shadow: 1px 1px 15px #DDD;margin-top:50px">
  <div class="panel-heading">
  	<Button class="btn btn-danger  pull-right" id="logout"><i class="fa fa-sign-out"></i> Logout</Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php">Home</a></li>
        <li class="active">Quiz questions/answers News</li>
    </ul>
    
  </div>
  <div class="panel-body">
      <?php include('freeAccount.php');?>

      <?php echo $xcrud->render($webmasterid); ?>

  </div>
</div>  

<?php include ("footer.php");?>
 <script>


 </script>


</body>
</html>