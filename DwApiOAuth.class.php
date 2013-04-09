<?php
include 'DwApiOpen.class.php';

class DwApiOAuth extends DwApiOpen
{
    /**
     * @param string $accessToken Access token.
     */
    function __construct($accessToken)
    {
        $this->accessToken = (is_string($accessToken) and !empty($accessToken)) ? $accessToken : null;
    }

    /**
     * Get private messages for the logged in user.
     *
     * @param bool $inbox Inbox when true, outbox when false.
     * @return bool|string JSON result, false on error.
     */
    public function GetPrivateMessages($inbox = true)
    {
        if (!is_bool($inbox))
        {
            return false;
        }

        return $this->GetUrl('/api/me/' . ((is_bool($inbox) and $inbox) ? 'inbox' : 'outbox'));
    }

    /**
     * Upvote or downvote a post.
     *
     * @param int|null $postId Post ID (optional).
     * @param bool $upVote Upvote when true, downvote when false.
     * @return bool|string JSON result, false on error.
     */
    public function VotePost($postId = null, $upVote = true)
    {
        if ($this->IsValidId($postId))
        {
            $url = "/api/posts/{$postId}/vote";
        }
        else
        {
            $url = '/api/posts/vote';
        }

        $getParameters = array ('vote' => ((is_bool($upVote) and $upVote) ? 1 : -1));

        return $this->GetUrl($url, $getParameters);
    }

    /**
     * Watch or unwatch an article.
     *
     * @param int|null $articleId Article ID (optional).
     * @param bool $watch Watch the article when true, unwatch when false.
     * @return bool|string JSON result, false on error.
     */
    public function WatchArticle($articleId = null, $watch = true)
    {
        if ($this->IsValidId($articleId))
        {
            $url = "/api/articles/{$articleId}/watch";
        }
        else
        {
            $url = '/api/articles/watch';
        }

        $getParameters = (is_bool($watch) and $watch) ? array () : array ('remove' => true);

        return $this->GetUrl($url, $getParameters);
    }

    /**
     * Get logged in user details.
     *
     * @return bool|string JSON result, false on error.
     */
    public function WhoAmI()
    {
        return $this->GetUrl('/api/me');
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

        return parent::GetUrl($path, $getParameters, $postParameters);
    }
}
?>