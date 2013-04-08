<?php
class DwApiOAuth extends DwApiOpen
{
    protected $accessToken;

    function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

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

    /**
     * Get logged in user details.
     *
     * @return string JSON result, false on error.
     */
    public function WhoAmI()
    {
        return $this->GetUrl('api/me');
    }

    /**
     * Get the REST path contents as a string.
     * Sets additional access token.
     *
     * @param string $path REST path to use.
     * @param array|null $getParameters GET parameters (optional).
     * @param array|null $postParameters POST parameters (optional).
     * @return bool|string URL page contents, false on error.
     */
    protected function GetUrl($path, $getParameters = null, $postParameters = null)
    {
        if (!is_array($getParameters))
        {
            $getParameters = array ('access_token' => $this->accessToken);
        }
        else if (!isset($getParameters['access_token']))
        {
            $getParameters['access_token'] = $this->accessToken;
        }

        parent::GetUrl($path, $getParameters, $postParameters);
    }
}
?>