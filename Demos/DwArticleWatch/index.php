<?php
namespace DwArticleWatch;

use DwApi\DwApiException;
use DwApi\DwApiToken;
use DwShared\Decorator;
use DwShared\Parser;

session_start();

include '../../DwApiToken.class.php';
include '../DwShared/Decorator.class.php';
include '../DwShared/Parser.class.php';

$token = isset($_SESSION['DwApiAccessToken']) ? $_SESSION['DwApiAccessToken'] : null;

$dwapi = new DwApiToken($token);
$parser = new Parser();

$divs = array ();
$forumList = array ();

try
{
    $forums = $dwapi->GetForums();
}
catch (DwApiException $exception)
{
    $forums = false;
}

$options = '<option value="0">All</option>';
if ($forums)
{
    $forums = json_decode($forums);
    foreach ($forums->data as $mainForum)
    {
        $forumList = $forumList + $parser->FlattenForumsResult($mainForum);
    }

    foreach ($forumList as $forum)
    {
        $options .= "<option value=\"{$forum->id}\">{$forum->title}</option>";
    }

    try
    {
        $articles = $dwapi->GetArticles(null, null, 'firstpost');
    }
    catch (DwApiException $exception)
    {
        $articles = false;
    }

    if ($articles)
    {
    	$count = 0;
        $articles = json_decode($articles);
        foreach ($articles->data as $article)
        {
            $divs[] = Decorator::ArticleDiv($article, $forumList);
		    $count++;
            if ($count >= 10)
            {
                break;
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>DaniWeb API Demo - Article Watch</title>
    <link rel="stylesheet" href="../DwShared/default.css" />
</head>
<body>
    <h1>DaniWeb API Demo - Article Watch</h1>
    <label for="forum">Forum filter:</label>
    <select id="forum">
        <?php echo $options; ?>
    </select>
    <?php
    foreach ($divs as $div)
    {
        echo $div;
    }
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        function load(forumId) {
            $('body > div').remove();
            $('<div class="message"><img src="http://libs.pritaeas.net/gfx/ajax-loader.gif" title="Loading.." alt="Loading.." /></div>').insertAfter('select');
            $.ajax({
                url: 'load.php?token=<?php echo $token; ?>&forum=' + forumId,
                dataType: 'json'
            })
            .done(function (data) {
                $('body > div').remove();
                if (data.new) {
                    for (var i = data.new.length - 1; i >= 0; i--) {
                        $(data.new[i].div).insertAfter('select');
                        $('body > div > p > span.op').first().text(data.new[i].username);
                    }
                    $('div').removeClass('highlight');
                }
                else {
                    $('<div class="message">No results</div>').insertAfter('select');
                }
            });
        }

        function update() {
            var id = $('body > div').first().attr('id');
            $.ajax({
                url: 'getUpdates.php?token=<?php echo $token; ?>&id=' + id + '&forum=' + $('#forum').val(),
                cache: false,
                dataType: 'json'
            }).done(function (data) {
                $('div').removeClass('highlight');
                if (data.new) {
                    for (var i = data.new.length - 1; i >= 0; i--) {
                        $(data.new[i].div).insertAfter('select');
                        $('body > div > p > span.op').first().text(data.new[i].username);
                    }
                    $('body > div:gt(9)').remove();
                }

                $('div > p > span').removeClass('highlight');
                if (data.updated) {
                    for (var j = 0; j < data.updated.length - 1; j++) {
                        var $parent = $('#' + data.updated[j].id);
                        var $node = $('#' + data.updated[j].id + ' > p > span.post');
                        if ($node.attr('id') < data.updated[j].postId) {
                            $node.attr('id', data.updated[j].postId);
                            $node.html(data.updated[j].span);
                            $node.find('span.lr').text(data.updated[j].username);
                            $parent.addClass('highlight');
                        }
                    }
                }
            });
        }

        $(function() {
            setInterval(update, 30000);

            $('#forum').on('change', function () {
                load($(this).val());
            });
        });
    </script>
</body>
</html>