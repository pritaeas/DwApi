<?php
include 'DwApiBase.class.php';

class DwApiRss extends DwApiBase
{
    /**
     * Returns the RSS feed, optionally filtered by forum ID and/or article type.
     * Invalid forum ID's and article types will be ignored.
     * Items in the feed will be no older than 90 days, and no more than 100 items.
     *
     * @param int|null $forumId Forum ID (optional).
     * @param null|string $articleType Article type to filter on (optional).
     * @return bool|string RSS feed, false on error.
     */ 
    public function GetFeed($forumId = null, $articleType = null)
    {
        $url = '/rss/pull';

        if ($this->IsValidId($forumId))
        {
            $url .= "/{$forumId}";
        }

        if ($this->IsArticleType($articleType))
        {
            $url .= "/{$articleType}";
        }

        return $this->GetUrl($url);
    }
}
?>