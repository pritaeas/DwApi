<?php
class DwApiBase {
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
     * @var array List of forum relation types.
     */
    protected $relationTypes = array
    (
        'ancestors', 'children', 'descendants'
    );
    
    /**
     * Get the list of supported article types.
     *
     * @param bool $sorted Sort alphabetically (optional), default true.
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
     * @param bool $sorted Sort alphabetically (optional), default true.
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
     * Get the list of supported forum relation types.
     *
     * @param bool $sorted Sort alphabetically (optional), default true.
     * @return array List of forum relation types.
     */
    public function GetRelationTypes($sorted = true)
    {
        $result = $this->relationTypes;
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
     * @return bool|string URL page contents, false on error.
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

    /**
     * Checks if the ID is a valid positive integer.
     *
     * @param mixed $id ID.
     * @return bool True if positive integer, false otherwise.
     */
    protected function IsValidId($id)
    {
        return is_int($id) and ($id > 0);
    }
}
?>