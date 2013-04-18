<?php
include 'DwApiBase.class.php';

class DwApiRss extends DwApiBase
{
    /**
     * Returns the RSS feed, optionally filtered by forum ID and/or article type.
     * Invalid forum ID's and article types not accepted.
     * Items in the feed will be no older than 90 days, and no more than 100 items.
     *
     * @param int|null $forumId Forum ID (optional).
     * @param null|string $articleType RSS article type to filter on (optional).
     * @return bool|string RSS feed, false on error.
     */ 
    public function GetFeed($forumId = null, $articleType = null)
    {
        if (($forumId != null) and !$this->IsValidId($forumId))
        {
            return false;
        }

        if (($articleType != null) and !$this->IsRssArticleType($articleType))
        {
            return false;
        }

        $url = '/rss/pull';

        if ($forumId != null)
        {
            $url .= "/{$forumId}";
        }

        if ($articleType != null)
        {
            $url .= "/{$articleType}";
        }

        return $this->GetUrl($url, null, null, false);
    }
}
?>