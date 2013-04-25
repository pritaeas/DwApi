<?php
namespace DwApi;

include 'DwApiOpen.class.php';

class DwApiToken extends DwApiOpen
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
     * @throws DwApiException EX_ACCESS_TOKEN thrown on empty access token.
     * @throws DwApiException EX_INVALID_TYPE_MAIL_BOX thrown on invalid mail box type.
     * @return string JSON result.
     */
    public function GetPrivateMessages($mailBoxType)
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, DwApiException::EX_ACCESS_TOKEN);
        }

        if (!$this->IsMailBoxType($mailBoxType))
        {
            throw new DwApiException('$mailBoxType', DwApiException::EX_INVALID_TYPE_MAIL_BOX);
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
     * @throws DwApiException EX_ACCESS_TOKEN thrown on empty access token.
     * @throws DwApiException EX_INVALID_INT thrown on invalid post ID.
     * @throws DwApiException EX_INVALID_TYPE_VOTE thrown on invalid vote type.
     * @return string JSON result.
     */
    public function VotePost($postId, $voteType)
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, DwApiException::EX_ACCESS_TOKEN);
        }

        if (!$this->IsValidId($postId))
        {
            throw new DwApiException('$postId', DwApiException::EX_INVALID_INT);
        }

        if (!$this->IsVoteType($voteType))
        {
            throw new DwApiException('$voteType', DwApiException::EX_INVALID_TYPE_VOTE);
        }

        $vote = array_search($voteType, $this->voteTypes);

        return $this->GetUrl("/api/posts/{$postId}/vote", array ('vote' => $vote));
    }

    /**
     * Watch or unwatch an article.
     *
     * @param int $articleId Article ID (required).
     * @param bool $watchType Watch type (required).
     * @throws DwApiException EX_ACCESS_TOKEN thrown on empty access token.
     * @throws DwApiException EX_INVALID_INT thrown on invalid article ID.
     * @throws DwApiException EX_INVALID_TYPE_WATCH thrown on invalid watch type.
     * @return string JSON result.
     */
    public function WatchArticle($articleId, $watchType)
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, DwApiException::EX_ACCESS_TOKEN);
        }

        if (!$this->IsValidId($articleId))
        {
            throw new DwApiException('$articleId', DwApiException::EX_INVALID_INT);
        }

        if (!$this->IsWatchType($watchType))
        {
            throw new DwApiException('$watchType', DwApiException::EX_INVALID_TYPE_WATCH);
        }

        $watch = array_search($watchType, $this->watchTypes);

        return $this->GetUrl("/api/articles/{$articleId}/watch", array ('remove' => $watch));
    }

    /**
     * Get the profile for the logged in user.
     *
     * @throws DwApiException EX_ACCESS_TOKEN thrown on empty access token.
     * @return string JSON result.
     */
    public function WhoAmI()
    {
        if ($this->accessToken == null)
        {
            throw new DwApiException(null, DwApiException::EX_ACCESS_TOKEN);
        }

        return $this->GetUrl('/api/me');
    }

    /**
     * Get the REST path contents as a string.
     * Sets additional access token.
     *
     * @param string $path REST path to use (required).
     * @param array $getParameters GET parameters (optional).
     * @param array $postParameters POST parameters (optional).
     * @param bool $jsonCheck Check for JSON false result (optional), default true.
     * @return string URL page contents.
     */
    protected function GetUrl($path, $getParameters = null, $postParameters = null, $jsonCheck = true)
    {
        if ($this->accessToken != null)
        {
            if ($getParameters == null)
            {
                $getParameters = array ('access_token' => $this->accessToken);
            }
            else if (is_array($getParameters))
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