<?php
class Rss
{
    /**
     * @var array List of supported article types.
     */
    private $articleTypes = array 
    (
        'unanswered', 'solved', 'news', 'reviews',
        'interviews', 'tutorials', 'code', 'whitepapers'
    );

    /**
     * Returns the list of supported article types.
     *
     * @param bool $sorted Sort the list alphabetically, default true.
     * @return array List of article types.
     */
    public function GetArticleTypes($sorted = true)
    {
        $result = $this->articleTypes;
        if ($sorted)
        {
            sort($result);
        }
        return $result;
    }

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

    /**
     * Get an URL and return the page contents as a string.
     *
     * @param string $url URL to get.
     * @return mixed URL page contents, false on error.
     */
    private function GetUrl($url)
    {
        $result = false;
        if (extension_loaded('curl'))
        {
            $ch = curl_init();
            if ($ch)
            {
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curlResult = curl_exec($ch);
                if ($curlResult)
                {
                    $result = $curlResult;
                }
                curl_close($ch);
            }
        }
        else if (ini_get('allow_url_fopen'))
        {
            $result = file_get_contents($url);
        }
        return $result;
    }
}
?>