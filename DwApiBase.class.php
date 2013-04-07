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
     * Builds the query parameter for a specific page.
     *
     * @param int $page Page number.
     * @param string $separator Parameter separator (optional), default '?'.
     * @return string Query parameter, or empty string.
     */
    protected function GetPageParameter($page, $separator = '?')
    {
        $result = '';

        if ($this->IsValidId($page))
        {
            $result .= "{$separator}page={$page}";
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
     * Check if article type is valid.
     *
     * @param string $articleType Article type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsArticleType($articleType)
    {
        return in_array($articleType, $this->articleTypes);
    }

    /**
     * Check if post type is valid.
     *
     * @param string $postType Post type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsPostType($postType)
    {
        return in_array($postType, $this->postTypes);
    }

    /**
     * Check if relation type is valid.
     *
     * @param string $relationType Relation type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsRelationType($relationType)
    {
        return in_array($relationType, $this->relationTypes);
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