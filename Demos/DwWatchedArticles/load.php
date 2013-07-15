<?php
namespace DwWatchedArticles;

use DwApi\DwApiException;
use DwApi\DwApiToken;
use DwShared\Decorator;
use DwShared\Parser;

session_start();

include '../../DwApiToken.class.php';
include '../DwShared/Decorator.class.php';
include '../DwShared/Parser.class.php';

$token = (isset($_GET['token']) and !empty($_GET['token'])) ? $_GET['token'] : null;
$forumId = (isset($_GET['forum']) and !empty($_GET['forum'])) ? (int)$_GET['forum'] : null;

$dwapi = new DwApiToken($token);
$parser = new Parser();

$result = array ();

try
{
    $forums = $dwapi->GetForums();
}
catch (DwApiException $exception)
{
    $forums = false;
}

if ($forums)
{
    $forumList = array ();
    $forums = json_decode($forums);
    foreach ($forums->data as $mainForum)
    {
        $forumList = $forumList + $parser->FlattenForumsResult($mainForum);
    }

    try
    {
        if ($forumId > 0)
        {
            $articles = $dwapi->GetForumArticles($forumId, 'watching');
        }
        else
        {
            $articles = $dwapi->GetArticles(null, 'watching');
        }
    }
    catch (DwApiException $exception)
    {
        $articles = false;
    }

    if ($articles)
    {
        $page = 1;
        $perPage = 30;
        $max = 30;
        $more = true;
        while ($more)
        {
            $articles = json_decode($articles);
            foreach ($articles->data as $article)
            {
                if (empty($forumId) or ($forumId == $article->forum->id))
                {
                    $result['new'][] = array
                    (
                        'div' => Decorator::ArticleDiv($article, $forumList, true),
                        'username' => $article->first_post->poster->username
                    );
                }
            }

            if ($articles->pagination->total > $max)
            {
                try
                {
                    $page++;
                    if ($forumId > 0)
                    {
                        $articles = $dwapi->GetForumArticles($forumId, 'watching', null, $page);
                    }
                    else
                    {
                        $articles = $dwapi->GetArticles(null, 'watching', false, $page);
                    }
                    $max += $perPage;
                }
                catch (DwApiException $exception)
                {
                    $articles = false;
                }
            }
            else
            {
                $more = false;
            }
        }
    }
}

header('content-type: application/json');
echo json_encode($result);
?>