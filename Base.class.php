<?php
class Base {
    /**
     * @var array List of supported article types.
     */
    protected $articleTypes = array
    (
        'unanswered', 'solved', 'threads', 'news', 'reviews',
        'interviews', 'tutorials', 'code', 'whitepapers'
    );

    /**
     * @var array List of supported post types.
     */
    protected $postTypes = array
    (
        'solved', 'upvoted', 'downvoted'
    );
    
    /**
     * Get the list of supported article types.
     *
     * @param bool $sorted Sort alphabetically, default true.
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
     * Get the list of supported post types.
     *
     * @param bool $sorted Sort alphabetically, default true.
     * @return array List of post types.
     */
    public function GetPostTypes($sorted = true)
    {
        $result = $this->postTypes;
        if ($sorted)
        {
            sort($result);
        }
        return $result;
    }

    /**
     * Get an URL's page contents as a string.
     *
     * @param string $url URL to get.
     * @return mixed URL page contents, false on error.
     */
    protected function GetUrl($url)
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