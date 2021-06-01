<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>html5 chat, 404 error !</title>
    <?php include('css.php'); ?>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            background-color: #006CFF;
            color: white;
            overflow: hidden;
        }

        .error404 {
            text-align: center;
            font-size: 10em;
            text-shadow: 2px 2px 2px #000;
        }

        .ghost {
            position: absolute;
            background-image: url(img/ghost.svg);
            background-repeat: no-repeat;
            background-size: cover;
            width: 320px;
            height: 320px;
            bottom: 0px;
            transition-duration: 0.3s;
        }

        .ghost:hover {
            transform: scale(3, 3);
            transition-duration: 0.3s;
        }
    </style>

</head>

<body>
<div class="ghost">
</div>
<div class="error404"><i class="fa fa-exclamation-triangle"></i> Hello chatter ?<i
        class="fa fa-exclamation-triangle"></i><br> We have a <br>404 error !
</div>
<meta http-equiv="refresh" content="0; url=/" />
</body>
</html>