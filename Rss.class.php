<?php
class Rss extends Base
{
    /**
     * Returns the RSS feed, optionally filtered by forum ID and/or article type.
     *
     * @param int $forumId Id of the forum, default null.
     * @param string $articleType Article type, default null.
     * @return mixed RSS feed, optionally filtered, false on error.
     */ 
    public function GetFeed($forumId, $articleType)
    {
        $urlParts = array ();
        $urlParts[] = 'http://www.daniweb.com/rss/pull';
        if (is_int($forumId) and ($forumId > 0))
        {
            $urlParts[] = $forumId;
        }
        if (in_array($articleType, $this->articleTypes))
        {
            $urlParts[] = $articleType;
        }
        return $this->GetUrl(implode('/', $urlParts));
    }
}
?>