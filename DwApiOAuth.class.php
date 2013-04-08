<?php
// todo override DwApiOpen
// todo override GetUrl to append access token
class DwApiOAuth extends DwApiBase
{
    public function GetArticles($articleIds = null, $page = null)
    {
        return false;
    }

    public function GetForumArticles($forumIds, $page = null)
    {
        return false;
    }

    public function GetMemberArticles($memberIds, $page = null)
    {
        return false;
    }

    public function GetPrivateMessages($inbox = true)
    {
        // http://www.daniweb.com/api/me/inbox?access_token={ACCESS_TOKEN}
        // http://www.daniweb.com/api/me/outbox?access_token={ACCESS_TOKEN}
        return false;
    }

    public function VotePost($postId, $upVote = true)
    {
        // http://www.daniweb.com/api/posts/vote
        // http://www.daniweb.com/api/posts/{:ID}/vote?access_token={ACCESS_TOKEN}
        return false;
    }

    public function WatchArticle($articleId, $watch = true)
    {
        // http://www.daniweb.com/api/articles/watch
        // http://www.daniweb.com/api/articles/{:ID}/watch?access_token={ACCESS_TOKEN}
        return false;
    }

    public function WhoAmI()
    {
        // http://www.daniweb.com/api/me?access_token={ACCESS_TOKEN}
        return false;
    }
}
?>