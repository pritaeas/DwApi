<?php
namespace DwArticleWatch;

use DwApi\DwApiException;
use DwApi\DwApiToken;
use DwShared\Decorator;
use DwShared\Parser;

include '../../DwApiToken.class.php';
include '../DwShared/Decorator.class.php';
include '../DwShared/Parser.class.php';

$token = (isset($_GET['token']) and !empty($_GET['token'])) ? $_GET['token'] : null;
$id = (isset($_GET['id']) and !empty($_GET['id'])) ? $_GET['id'] : 0;
$forumId = (isset($_GET['forum']) and !empty($_GET['forum'])) ? (int)$_GET['forum'] : null;

$dwapi = new DwApiToken($token);
$parser = new Parser();

$result = array ();
try
{
    $forums = $dwapi->GetForums();
    if ($forums)
    {
        $forumList = array ();
        $forums = json_decode($forums);
        foreach ($forums->data as $mainForum)
        {
            $forumList = $forumList + $parser->FlattenForumsResult($mainForum);
        }

        if ($forumId > 0)
        {
            $articles = $dwapi->GetForumArticles($forumId, null, 'firstpost');
        }
        else
        {
            $articles = $dwapi->GetArticles(null, null, 'firstpost');
        }

        if ($articles)
        {
            $articles = json_decode($articles);
            foreach ($articles->data as $article)
            {
                if ($article->id > $id)
                {
                    $result['new'][] = array
                    (
                        'div' => Decorator::ArticleDiv($article, $forumList, true),
                        'username' => $article->first_post->poster->username
                    );
                }
            }
        }

        if ($forumId > 0)
        {
            $articles = $dwapi->GetForumArticles($forumId, null, 'lastpost');
        }
        else
        {
            $articles = $dwapi->GetArticles(null, null, 'lastpost');
        }

        if ($articles)
        {
            $articles = json_decode($articles);
            foreach ($articles->data as $article)
            {
                if ($article->first_post->id != $article->last_post->id)
                {
                    $result['updated'][] = array
                    (
                        'id' => $article->id,
                        'postId' => $article->last_post->id,
                        'span' => Decorator::LastReply($article->last_post->id, $article->last_post->timestamp),
                        'username' => $article->last_post->poster->username
                    );
                }
            }
        }
    }
}
catch (DwApiException $exception)
{
    $result['new'][] = array
    (
        'div' => "<div class=\"error\">{$exception->getMessage()}</div>",
        'username' => ''
    );
}

header('content-type: application/json');
echo json_encode($result);
?>