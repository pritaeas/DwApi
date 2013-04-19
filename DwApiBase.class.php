<?php
include 'DwApiException.class.php';

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
        'code' => true,
        'interviews' => true,
        'news' => true,
        'reviews' => true,
        'solved' => true,
        'threads' => true,
        'tutorials' => true,
        'unanswered' => true,
        'whitepapers' => true,
        'recommended' => false,
        'viewed' => false,
        'watching' => false
    );

    /**
     * @var string Base URL for the DaniWeb API.
     */
    protected $baseUrl = 'http://www.daniweb.com';

    /**
     * @var array List of supported post types.
     */
    protected $postTypes = array
    (
        'downvoted', 'solved', 'upvoted'
    );

    /**
     * @var array List of forum relation types.
     */
    protected $relationTypes = array
    (
        'ancestors', 'children', 'descendants'
    );
    
    /**
     * Get the list of supported article types (depends on whether an access token is set).
     *
     * @return array List of article types.
     */
    public function GetArticleTypes()
    {
        if ($this->accessToken == null)
        {
            $result = $this->GetRssArticleTypes();
        }
        else
        {
            $result = array_keys($this->articleTypes);
            sort($result);
        }

        return $result;
    }

    /**
     * Get the list of supported post types.
     *
     * @return array List of post types.
     */
    public function GetPostTypes()
    {
        return $this->postTypes;
    }

    /**
     * Get the list of supported forum relation types.
     *
     * @return array List of forum relation types.
     */
    public function GetRelationTypes()
    {
        return $this->relationTypes;
    }

    /**
     * Get the list of supported RSS article types.
     *
     * @return array List of RSS article types.
     */
    public function GetRssArticleTypes()
    {
        $result = array();

        foreach ($this->articleTypes as $articleType => $articleOpen)
        {
            if ($articleOpen)
            {
                $result[] = $articleType;
            }
        }

        return $result;
    }

    /**
     * Get the REST path contents as a string.
     *
     * @param string $path REST path to use (required).
     * @param null|array $getParameters GET parameters (optional).
     * @param null|array $postParameters POST parameters (optional).
     * @param bool $jsonCheck Check for JSON false result (optional), default true.
     * @throws DwApiException EX_INVALID_STRING on invalid or empty path.
     * @throws DwApiException EX_INVALID_ARRAY on invalid array $getParameters or $postParameters.
     * @throws DwApiException EX_INVALID_BOOL on invalid bool $jsonCheck.
     * @throws DwApiException EX_CONFIGURATION on disabled curl and allow_url_fopen.
     * @throws DwApiException EX_CURL on curl failure.
     * @throws DwApiException EX_FOPEN on file_get_contents failure.
     * @throws DwApiException EX_DANIWEB on invalid request or no data.
     * @return bool|string URL page contents, false on error.
     */
    protected function GetUrl($path, $getParameters = null, $postParameters = null, $jsonCheck = true)
    {
        if (!is_string($path) or empty($path))
        {
            throw new DwApiException('$path', DwApiException::EX_INVALID_STRING);
        }

        if (($getParameters != null) and !is_array($getParameters))
        {
            throw new DwApiException('$getParameters', DwApiException::EX_INVALID_ARRAY);
        }

        if (($postParameters != null) and !is_array($postParameters))
        {
            throw new DwApiException('$postParameters', DwApiException::EX_INVALID_ARRAY);
        }

        if (!is_bool($jsonCheck))
        {
            throw new DwApiException('$jsonCheck', DwApiException::EX_INVALID_BOOL);
        }

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
                $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);

                if ($httpStatus == 200)
                {
                    $result = $curlResult;
                }
                else if (in_array($httpStatus, array(301, 400)))
                {
                    // 301 catches an RSS feed with an invalid forum ID
                    throw new DwApiException(null, DwApiException::EX_DANIWEB);
                }
                else
                {
                    throw new DwApiException(null, DwApiException::EX_CURL);
                }
            }
        }
        else if (ini_get('allow_url_fopen'))
        {
            $result = @file_get_contents($url);
            if ($result === false)
            {
                throw new DwApiException(null, DwApiException::EX_FOPEN);
            }
        }
        else
        {
            throw new DwApiException(null, DwApiException::EX_CONFIGURATION);
        }

        if ($jsonCheck and is_string($result))
        {
            // check if the returned json result contains a false success response
            $data = json_decode($result);
            if (isset($data) and isset($data->data) and isset($data->data->success) and !$data->data->success)
            {
                throw new DwApiException(null, DwApiException::EX_DANIWEB);
            }
        }

        return $result;
    }

    /**
     * Converts an array of ID's to a separated string.
     *
     * @param array|int $ids ID as int, or array of int.
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
     * If access token is null, checks open API types, otherwise all types.
     *
     * @param string $articleType Article type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsArticleType($articleType)
    {
        return in_array($articleType, $this->GetArticleTypes());
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
     * Check if article type is valid.
     *
     * @param string $articleType Article type.
     * @return bool True when valid, false otherwise.
     */
    protected function IsRssArticleType($articleType)
    {
        return in_array($articleType, $this->GetRssArticleTypes());
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