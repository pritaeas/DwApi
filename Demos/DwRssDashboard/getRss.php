<?php
namespace DwRssDashboard;

use DwApi\DwApiException;
use DwApi\DwApiToken;
use DwShared\Decorator;
use DwShared\Parser;

include '../../DwApiToken.class.php';

include '../DwShared/Decorator.class.php';
include '../DwShared/Parser.class.php';

$forum = isset($_GET['forum']) ? $_GET['forum'] : 0;
if ($forum > 0)
{
    $rss = new DwApiToken();
    $parser = new Parser();

    $result = json_encode(array
        (
            'forum' => $forum,
            'url' => "<a href=\"http://www.daniweb.com/rss/pull/{$forum}\">{$forum}</a>",
            'links' => $parser->ParseForumFeed($rss->GetFeed((int)$forum), array ('DwShared\Decorator', 'ArticleLink'))
        ));

    echo $result;
}
else
{
    echo json_encode(array ());
}
?>