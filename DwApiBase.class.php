<?php
class DwApiBase {
    /**
     * @var null|string Access token.
     */
    protected $accessToken = null;

    /**
     * @var array List of supported article types.
     * True indicates supported in open API, false for OAuth API.
     */
    protected $articleTypes = array
    (
        'unanswered' => true,
        'solved' => true,
        'threads' => true,
        'news' => true,
        'reviews' => true,
        'interviews' => true,
        'tutorials' => true,
        'code' => true,
        'whitepapers' => true,
        'recommended' => false,
        'viewed' => false,
        'watching' => false
    );

    protected $baseUrl = 'http://www.daniweb.com';

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
        if (!is_bool($openOnly) or !is_bool($sorted))
        {
            return false;
        }

        $result = array();

        if (is_bool($openOnly) and $openOnly)
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

        if (is_bool($sorted) and $sorted)
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
        if (!is_bool($sorted))
        {
            return false;
        }

        $result = $this->postTypes;

        if (is_bool($sorted) and $sorted)
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
        if (!is_bool($sorted))
        {
            return false;
        }

        $result = $this->relationTypes;

        if (is_bool($sorted) and $sorted)
        {
            sort($result);
        }

        return $result;
    }

    /**
     * Builds the query parameter for a specific page.
     *
     * @param int $page Page number.
     * @return array Query parameter, or empty array.
     */
    protected function GetPageParameter($page)
    {
        $result = array();

        if ($this->IsValidId($page))
        {
            $result = array ('page' => $page);
        }

        return $result;
    }

    /**
     * Get the REST path contents as a string.
     *
     * @param string $path REST path to use.
     * @param array|null $getParameters GET parameters (optional).
     * @param array|null $postParameters POST parameters (optional).
     * @return bool|string URL page contents, false on error.
     */
    protected function GetUrl($path, $getParameters = null, $postParameters = null)
    {
        $result = false;
        $url = $this->baseUrl . $path;

        if (is_array($getParameters) and (count($getParameters) > 0))
        {
            $url .= '?' . http_build_query($getParameters);
        }

        if (extension_loaded('curl'))
        {
            $ch = curl_init();
            if ($ch)
            {
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                if (is_array($postParameters) and (count($postParameters) > 0))
                {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postParameters);
                }

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