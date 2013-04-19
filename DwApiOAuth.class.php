<?php
include 'DwApiOpen.class.php';

class DwApiOAuth extends DwApiOpen
{
    /**
     * @var array List of supported mail box types.
     */
    protected $mailBoxTypes = array
    (
        'inbox', 'outbox'
    );

    /**
     * @var array List of supported vote types.
     */
    protected $voteTypes = array
    (
        -1 => 'downvote',
         1 => 'upvote'
    );

    /**
     * @var array List of supported watch types.
     */
    protected $watchTypes = array
    (
        false => 'unwatch',
        true  => 'watch'
    );

    /**
     * @param string $accessToken Access token or null for open API only.
     */
    function __construct($accessToken = null)
    {
        $this->accessToken = (is_string($accessToken) and !empty($accessToken)) ? $accessToken : null;
    }

    /**
     * Get the list of supported mail box types.
     *
     * @return array List of mail box types.
     */
    public function GetMailBoxTypes()
    {
        return $this->mailBoxTypes;
    }

    /**
     * Get private messages for the logged in user.
     *
     * @param string $mailBoxType Mail box type (required).
     * @throws DwApiException 4001 thrown on empty access token.
     * @throws DwApiException 4021 thrown on invalid mail box type.
     * @return bool|string JSON result, false on error.
     */
    public function GetPrivateMessages($mailBoxType)
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, 4001);
        }

        if (!$this->IsMailBoxType($mailBoxType))
        {
            throw new DwApiException(null, 4021);
        }

        return $this->GetUrl("/api/me/{$mailBoxType}");
    }

    /**
     * Get the list of supported vote types.
     *
     * @return array List of vote types.
     */
    public function GetVoteTypes()
    {
        return array_values($this->voteTypes);
    }

    /**
     * Get the list of supported watch types.
     *
     * @return array List of watch types.
     */
    public function GetWatchTypes()
    {
        return array_values($this->watchTypes);
    }

    /**
     * Upvote or downvote a post.
     *
     * @param int $postId Post ID (required).
     * @param string $voteType Vote type (required).
     * @throws DwApiException 4001 thrown on empty access token.
     * @throws DwApiException 4012 thrown on invalid post ID.
     * @throws DwApiException 4022 thrown on invalid vote type.
     * @return bool|string JSON result, false on error.
     */
    public function VotePost($postId, $voteType)
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, 4001);
        }

        if (!$this->IsValidId($postId))
        {
            throw new DwApiException(null, 4012);
        }

        if (!$this->IsVoteType($voteType))
        {
            throw new DwApiException(null, 4022);
        }

        $vote = array_search($voteType, $this->voteTypes);

        return $this->GetUrl("/api/posts/{$postId}/vote", array ('vote' => $vote));
    }

    /**
     * Watch or unwatch an article.
     *
     * @param int $articleId Article ID.
     * @param bool $watchType Watch type.
     * @throws DwApiException 4001 thrown on empty access token.
     * @throws DwApiException 4011 thrown on invalid article ID.
     * @throws DwApiException 4023 thrown on invalid watch type.
     * @return bool|string JSON result, false on error.
     */
    public function WatchArticle($articleId, $watchType)
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, 4001);
        }

        if (!$this->IsValidId($articleId))
        {
            throw new DwApiException(null, 4011);
        }

        if (!$this->IsWatchType($watchType))
        {
            throw new DwApiException(null, 4023);
        }

        $watch = array_search($watchType, $this->watchTypes);

        return $this->GetUrl("/api/articles/{$articleId}/watch", array ('remove' => $watch));
    }

    /**
     * Get logged in user details.
     *
     * @throws DwApiException If access token is not set.
     * @return bool|string JSON result, false on error.
     */
    public function WhoAmI()
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, 4001);
        }

        return $this->GetUrl('/api/me');
    }

    /**
     * Get the REST path contents as a string.
     * Sets additional access token.
     *
     * @param string $path REST path to use (required).
     * @param null|array $getParameters GET parameters (optional).
     * @param null|array $postParameters POST parameters (optional).
     * @param bool $jsonCheck Check for JSON false result (optional), default true.
     * @return bool|string URL page contents, false on error.
     */
    protected function GetUrl($path, $getParameters = null, $postParameters = null, $jsonCheck = true)
    {
        if ($this->accessToken != null)
        {
            if (!is_array($getParameters))
            {
                $getParameters = array ('access_token' => $this->accessToken);
            }
            else if (!isset($getParameters['access_token']))
            {
                $getParameters['access_token'] = $this->accessToken;
            }
        }

        return parent::GetUrl($path, $getParameters, $postParameters, $jsonCheck);
    }

    /**
     * Check if mail box type is valid.
     *
     * @param string $mailBoxType Mail box type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsMailBoxType($mailBoxType)
    {
        return in_array($mailBoxType, $this->mailBoxTypes);
    }

    /**
     * Check if vote type is valid.
     *
     * @param string $voteType Vote type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsVoteType($voteType)
    {
        return in_array($voteType, $this->voteTypes);
    }

    /**
     * Check if watch type is valid.
     *
     * @param string $watchType Watch type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsWatchType($watchType)
    {
        return in_array($watchType, $this->watchTypes);
    }
}
?>