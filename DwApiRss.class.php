<?php
include 'DwApiBase.class.php';

class DwApiRss extends DwApiBase
{
    /**
     * Returns the RSS feed, optionally filtered by forum ID and/or article type.
     * Invalid forum ID's and article types not accepted.
     * Items in the feed will be no older than 90 days, and no more than 100 items.
     *
     * @param null|int $forumId Forum ID (optional).
     * @param null|string $rssArticleType RSS article type to filter on (optional).
     * @throws DwApiException 2011 on invalid forum ID.
     * @throws DwApiException 2021 on invalid RSS article type.
     * @return bool|string RSS feed, false on error.
     */ 
    public function GetFeed($forumId = null, $rssArticleType = null)
    {
        if (($forumId != null) and !$this->IsValidId($forumId))
        {
            throw new DwApiException(null, 2011);
        }

        if (($rssArticleType != null) and !$this->IsRssArticleType($rssArticleType))
        {
            throw new DwApiException(null, 2021);
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