<?php
class DwApiBase {
    /**
     * @var array List of supported article types.
     * False indicates supported in open API, true for OAuth API.
     */
    protected $articleTypes = array
    (
        'unanswered' => false,
        'solved' => false,
        'threads' => false,
        'news' => false,
        'reviews' => false,
        'interviews' => false,
        'tutorials' => false,
        'code' => false,
        'whitepapers' => false,
        'recommended' => true,
        'viewed' => true,
        'watching' => true
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
     * @param bool $openOnly Show only valid types for the open API (optional), default true.
     * @param bool $sorted Sort alphabetically (optional), default true.
     * @return array List of article types.
     */
    public function GetArticleTypes($openOnly = true, $sorted = true)
    {
        $result = array();

        if ($openOnly)
        {
            foreach ($this->articleTypes as $articleType => $articleOpen)
            {
                if ($articleOpen)
                {
                    $result[] = $articleType;
                }
            }
        }
        else
        {
            $result = array_keys($this->articleTypes);
        }

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
     * Converts an array of ID's to a separated string.
     *
     * @param mixed $ids ID as int, or array of int.
     * @param string $separator Separator (optional), default ';'.
     * @return string Separated string of ID's, or empty string.
     */
    protected function IdsToString($ids, $separator = ';')
    {
        $idList = array();

        if ($this->IsValidId($ids))
        {
            $idList[] = $ids;
        }
        else if (is_array($ids))
        {
            foreach ($ids as $id)
            {
                if ($this->IsValidId($id))
                {
                    $idList[] = $id;
                }
            }
        }

        return (count($idList) > 0) ? implode($separator, $idList) : '';
    }

    /**
     * Check if article type is valid.
     *
     * @param string $articleType Article type.
     * @param bool $openOnly Use only types for the open API (optional), default true.
     * @return bool True when valid, false otherwise.
     */
    protected function IsArticleType($articleType, $openOnly = true)
    {
        $articleTypes = $this->GetArticleTypes($openOnly);
        return in_array($articleType, $articleTypes);
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