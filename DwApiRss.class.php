<?php
class DwApiRss extends DwApiBase
{
    /**
     * Returns the RSS feed, optionally filtered by forum ID and/or article type.
     *
     * @param int|null $forumId Forum ID (optional).
     * @param null|string $articleType Article type to filter on (optional).
     * @return bool|string RSS feed, false on error.
     */ 
    public function GetFeed($forumId, $articleType = null)
    {
        $urlParts = array ();
        $urlParts[] = 'http://www.daniweb.com/rss/pull';

        if ($this->IsValidId($forumId))
        {
            $urlParts[] = $forumId;
        }

        if ($this->IsArticleType($articleType))
        {
            $urlParts[] = $articleType;
        }

        return $this->GetUrl(implode('/', $urlParts));
    }
}
?>