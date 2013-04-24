<?php
namespace DwApi;

include 'DwApiBase.class.php';

class DwApiRss extends DwApiBase
{
    /**
     * Returns the RSS feed, optionally filtered by forum ID and/or article type.
     * Invalid forum ID's and article types not accepted.
     * Items in the feed will be no older than 90 days, and no more than 100 items.
     *
     * @param int $forumId Forum ID (optional).
     * @param string $rssArticleType RSS article type to filter on (optional).
     * @throws DwApiException EX_INVALID_INT on invalid forum ID.
     * @throws DwApiException EX_INVALID_TYPE_RSS_ARTICLE on invalid RSS article type.
     * @return string RSS feed.
     */ 
    public function GetFeed($forumId = null, $rssArticleType = null)
    {
        if (($forumId != null) and !$this->IsValidId($forumId))
        {
            throw new DwApiException('$forumId', DwApiException::EX_INVALID_INT);
        }

        if (($rssArticleType != null) and !$this->IsRssArticleType($rssArticleType))
        {
            throw new DwApiException('$rssArticleType', DwApiException::EX_INVALID_TYPE_RSS_ARTICLE);
        }

        $url = '/rss/pull';

        if ($forumId != null)
        {
            $url .= "/{$forumId}";
        }

        if ($rssArticleType != null)
        {
            $url .= "/{$rssArticleType}";
        }

        return $this->GetUrl($url, null, null, false);
    }
}
?>