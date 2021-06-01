<?php
session_start();
include ("../Config.php");
include_once('protect.php');
checkCanEnter('adminpanelQuiz');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>question editor</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">

    <script src="/js/jquery-1.11.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/sprintf.min.js"></script>
    <script src="/js/bootbox.min.js"></script>


    <style>
        #questionDiv,#answerDiv {
            font-size: 2em;
            padding: 10px;
            border:1px solid #eee;
        }

        #modal {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.38);
        }
            #modal #modalInner {
                background: #fff;
                width: 700px;
                margin: 30px auto;
                padding: 10px;
                border-radius: 10px;
            }

            #modal #modalInner img {
                margin: 5px;
                display: inline-block;
                vertical-align: middle;
            }
    </style>
</head>
<body>
<button id="insertImageBtn" class="btn btn-info"><i class="fa fa-image"></i> Insert Image</button>
<div id="questionDiv" contenteditable="">Write your question here : content is editable: ex: Whois that ...? </div>
<br><br>
<div id="answerDiv" contenteditable="">Write your Answer here</div>

<div id="modal" style="display: none;">

    <div id="modalInner"></div>
</div>

<script>
    $('#insertImageBtn').click(function() {
        $('#questionDiv').focus();
        bootbox.prompt('Enter keyword', function(keyword) {
            if (!keyword) {
                return;
            }
            $.post('/classes/ImageClass.php', {'a': 'getJson', keyword:keyword}, function (images) {
                if(images) {
                    try {
                        var a = JSON.parse(images);
                    } catch(e) {
                        console.log(images);
                        alert(e);
                    }
                }
                images = JSON.parse(images);
                if (!images.length) {
                    bootbox.alert('Sorry, no images found with keyword:' + keyword);
                    return;
                }

                insetImages(images);
                $('#modal').show();
        });
            $('#answerDiv').text(keyword);
        });

        //pasteHtmlAtCaret(sprintf('<b>'+keyword + '</b>', keyword));

    });

    function insetImages(images) {
        $('#modalInner').empty();
        var header = '<div class="header"><button id="closeButton" class="btn btn-xs pull-right"><i class="fa fa-close"></i>Close</button></div>'
        $('#modalInner').append(header);
        $.each(images, function(index, image) {
            var el = sprintf('<img src="%s" />', image.thumb);
            $('#modalInner').append(el);
        });


    }

    function pasteHtmlAtCaret(html) {
        var sel, range;
        if (window.getSelection) {
            // IE9 and non-IE
            sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0);
                range.deleteContents();

                // Range.createContextualFragment() would be useful here but is
                // non-standard and not supported in all browsers (IE9, for one)
                var el = document.createElement("div");
                el.innerHTML = html;
                var frag = document.createDocumentFragment(), node, lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);

                // Preserve the selection
                if (lastNode) {
                    range = range.cloneRange();
                    range.setStartAfter(lastNode);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            }
        } else if (document.selection && document.selection.type != "Control") {
            // IE < 9
            document.selection.createRange().pasteHTML(html);
        }
    }
    $(document).on('click', '#closeButton', function() {
        $('#modal').hide();
    })

    document.getElementById('modalInner').addEventListener("click", function(event) {
        console.log(event.target);
        console.log(event.target.tagName);
        if (event.target.tagName == 'IMG') {
            document.getElementById('questionDiv').appendChild(event.target);
            $('#modal').hide();
        }
    });
    
</script>

</body>
</html>