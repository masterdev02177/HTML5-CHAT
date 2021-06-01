<?php session_start();
//ini_set('display_errors', 1);error_reporting(E_ALL);

include('protect.php');

// print_r($_SESSION['role']);exit;
checkCanEnter('');

include ('../Config.php');
include ('lng/language.php');
if (!empty($_GET['lang'])) {
  $lng = new Language($_GET['lang']);
} else {
  $lng = new Language();
  $_GET['lang'] = 'eng_ENG';
}

$lng = $lng->getData();
$lngPage = $lng['loggedon'];
$_SESSION['webmasterid'] = $_SESSION['admin'];
$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
$config = DB::getOne('chat_config', "WHERE webmasterid=$webmasterid");
$genders = DB::getOne('chat_gender', "WHERE webmasterid=$webmasterid");
//print_r($genders)

$script = Webmaster::generateScript($webmasterid, $webmaster->token);

$sampleUsername = 'John';
$sampleSex = $genders->gender;
$sampleAvatar = '../img/malecostume.svg';

$script2 = Webmaster::generateScriptAutoConnect($webmasterid, $webmaster->token, $sampleUsername, $sampleSex);
$scriptWithAvatar = Webmaster::generateScriptAutoConnectWithAvatar($webmasterid, $webmaster->token, $sampleUsername, $sampleSex, $sampleAvatar);

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name=viewport content="width=device-width, initial-scale=1">
  <?php include 'css.php';?>
  <?php include 'js.php';?>
  <title><?php echo $lng['loggedon']['metaTtitle']; ?></title>

    <link rel="stylesheet" href="../css/common.css?cache=<?=time()?>">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kanit:200,300,400,500,600" rel="stylesheet">

</head>

<body>

 <div class="admin-panel panel panel-default row">
  <div class="panel-heading flex-property">
    <h1 class="admin-panel-title">
      <?php echo $lng['loggedon']['title']; ?>
    </h1>

    <div class="admin-header-btns flex-property">

      <?php include 'lng-menu.php';?>
        <?php //if (!$webmaster->wp_url && isset($_SESSION['admin'])): ?>
          <?php if (isset($_SESSION['admin'])): ?>
            <a class="btn header-btn flex-property" id="chatBtn" href="/" target="new">
                <i class="fa fa-comment"></i> <?php echo $lng['loggedon']['chat']; ?>
            </a>
        <?php endif?>


        <button class="btn header-btn flex-property" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></button>

    </div>
  </div>
  <div class="panel-body admin-body-panel flex-property">

      <div class="row panel-items-wrap">
        <div class="flex-property adition-box">


        </div>

          <div class="box-items flex-property">


              <?php if ($_SESSION['role']['adminpanelChatconfig']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="config.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-cog " ></span> <?php echo $lng['menu']['config']; ?></a>
                  </div>
              <?php endif?>

              <?php if ($_SESSION['role']['adminpanelMydata']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="webmaster.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-edit " ></span> <?php echo $lng['menu']['webmaster']; ?></a>
                  </div>
              <?php endif?>

              <?php if ($_SESSION['role']['adminpanelRooms']): ?>
              <div class="alert alert-info flex-property" >
                  <a href="rooms.php?lang=<?php echo $_GET['lang']; ?>"><span class="glyphicon glyphicon-home" style="font-size:1.4em" ></span> <?php echo $lng['menu']['rooms']; ?></a>
              </div>
              <?php endif?>


              <?php if ($_SESSION['role']['adminpanelUsers']) : ?>
                  <div class="alert alert-info flex-property disabled"  >
                      <a href="users.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-users " ></span> <?php echo $lng['menu']['users']; ?></a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelRoles']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="roles.php"><span class="fa fa-2x fa-user" ></span> User Roles</a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelBanned']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="banned.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-ban " ></span> <?php echo $lng['menu']['banned']; ?></a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelGenders']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="genders.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-mars " ></span> <?php echo $lng['menu']['genders']; ?></a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelNews']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="news.php?lang=<?php echo $_GET['lang']; ?>"><i class="fa fa-2x  fa-newspaper-o" aria-hidden="true"></i> <?php echo $lng['menu']['news']; ?></a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelHistory']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="chatHistory.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-history" ></span> <?php echo $lng['menu']['chatHistory']; ?></a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelQuiz']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="quiz.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-question-circle" ></span> <?php echo $lng['menu']['quiz']; ?></a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelCalendar']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="calendar.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-calendar-check-o" ></span> <?php echo $lng['menu']['calendar']; ?></a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelSounds']): ?>
              <div class="alert alert-info flex-property" >
                  <a href="sounds.php"><span class="fa fa-2x fa-music" ></span> Sounds</a>
              </div>
              <?php endif ?>
              <div class="alert alert-info flex-property" >
                   <a href="background.php"><span class="fa fa-2x fa-image" ></span> Background</a>
               </div> 
              <div class="alert alert-info flex-property" >
                   <a href="banner.php"><span class="fa fa-2x fa-image" ></span> Banner</a>
               </div> 
              
              <?php if ($_SESSION['role']['adminpanelForbiddenWords']): ?>
              <div class="alert alert-info flex-property" >
                  <a href="forbiddenWords.php?lang=<?php echo $_GET['lang']; ?>"><span class="fa fa-2x fa-exclamation " ></span> <?php echo $lng['menu']['forbiddenWords']; ?></a>
              </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelSecurity']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="translate.php"><span class="fa fa-2x fa-globe" ></span> Translate</a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminpanelSecurity']): ?>
              <div class="alert alert-info flex-property" >
                  <a href="security.php"><span class="fa fa-2x fa-shield" ></span> Security</a>
              </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminReports']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="chatContest.php"><span class="fa fa-2x fa-gift" ></span> Chat Contest</a>
                  </div>
              <?php endif ?>

              <?php if ($_SESSION['role']['adminReports']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="cssEditor.php"><span class="fa fa-2x fa-css3" ></span> CSS Chat editor</a>
                  </div>
              <?php endif ?>


              <?php if ($_SESSION['role']['adminReports']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="abuseReports.php"><span class="fa fa-2x fa-flag" ></span> Abuse Reports</a>
                  </div>
              <?php endif ?>


              <?php if ($config->chatType == 'conference' && $_SESSION['role']['adminDevelopers']): ?>
                  <div class="alert alert-info flex-property" >
                      <a href="conferenceReports.php"><span class="fa fa-2x fa-star" ></span> Conference Reports</a>
                  </div>
              <?php endif?>

            




          </div>
          <?php if ($_SESSION['role']['adminDevelopers']): ?>
            <div class="flex-property adition-box">


     <?php if (!$webmaster->wp_url): ?>
            <div class="alert alert-info" style="cursor:pointer" id="scriptDiv">
              <i class="fa fa-code fa-2x "></i></span> <?php echo $lng['loggedon']['scriptDiv']; ?> <span class="badge"><?php echo $lng['loggedon']['scriptDivBadge']; ?></span>




              <div class="scriptJS">
                   

<div align="center"> To include your chat in another websites, simply copy and paste the code below in your webpage:<Br>
  <Br>
  <span style="background-color:#FFFFCC; border-color:#FFFF66; border-style:dotted; border-width:1px; padding:5px; color:#000000;"><?php echo htmlspecialchars('<iframe style="height:560px; width:100%"  src="'.HOME_HTTP.'">allow="camera;microphone" name="cboxmain" id="cboxmain" seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true"></iframe>');?></span><br>
</div>

              </div>

              <div class="scriptJS">
                  <div>
                      <b><?php echo $lng['loggedon']['scriptDivTitle1']; ?></b>
                  </div>
                  <?php echo htmlentities($script) ?>
              </div>
              <div class="scriptJS">
                  <div>
                      <b><?php echo sprintf($lng['loggedon']['scriptDivTitle2'], $sampleUsername, $sampleSex); ?></b>
                  </div>
                  <?php echo htmlentities($script2) ?>
              </div>
              <div class="scriptJS">
                    <div>
                            <b><?php echo sprintf($lng['loggedon']['scriptDivTitle3']); ?></b>
                    </div>
                    <a href="<?=$chatLink?>"><?php echo htmlentities($chatLink) ?></a>
                </div>
              <div class="scriptJS">
                    <div>
                        <b><?php echo $lng['loggedon']['loginToRoom']; ?></b>
                        <div>
                            <a href="<?php echo $chatLink ?>&startRoom=room2" target="_blank"><?php echo $chatLink ?>&startRoom=room2</a>
                        </div>
                    </div>
                </div>
              <div class="scriptJS">
                  <div>
                      <b><?php echo sprintf($lng['loggedon']['scriptDivTitle4'],$sampleUsername, $sampleSex, ($sampleAvatar)); ?></b>
                      <div style="font-size: 0.8em">
                          <?php echo htmlentities($scriptWithAvatar) ?>
                      </div>
                      <div>
                          <br>
                          (The url avatar MUST BE <b>base64 encoded</b>)
                      </div>
                  </div>
              </div>
              <div class="scriptJS">
                  <a href="
                  /blog/jwt-a-quicker-and-simpler-version-using-the-html5-service/" target="_blank"><i class="fa fa-code"></i> <b>Follow tutorial</b></a> to encode user data with JWT as service
              </div>

                <div class="scriptJS">
                    <a href="/jwt" target="_blank"><i class="fa fa-code"></i> <b>Follow tutorial 2</b></a> to encode user data with JWT (no service)
                </div>

          </div>
          <?php endif?>
          </div>
          <?php endif?>

      </div>
   </div>
</div>
<script>
$('#scriptDiv').click(function(e) {
    $('.scriptJS').toggle();

});
$('.scriptJS').click(function(e) {
    e.stopImmediatePropagation();
});
</script>
<?php include ('footer.php');?>
</body>

</html>
