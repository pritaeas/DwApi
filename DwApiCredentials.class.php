<?php
class DwApiCredentials
{
    /**
     * @var int Client ID.
     */
    protected $clientId;

    /**
     * @var string Client secret.
     */
    protected $clientSecret;

    /**
     * @var string Code.
     */
    protected $code;

    /**
     * @var string Access token.
     */
    public $AccessToken;

    /**
     * @param int $clientId Client ID.
     * @param string $clientSecret Client secret.
     * @param null|string $serializedObject Serialized DwApiCredentials object (optional).
     * @return DwApiCredentials DwApiCredentials object.
     */
    public static function Create($clientId, $clientSecret, $serializedObject = null)
    {
        $object = @unserialize($serializedObject);

        if (get_class($object) != get_class())
        {
            $object = new DwApiCredentials();

            $object->clientId = $clientId;
            $object->clientSecret = $clientSecret;
        }

        $object->Authenticate();

        return $object;
    }

    /**
     * OAuth authentication flow for retrieving code and access token.
     */
    protected function Authenticate()
    {
        $currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        if (isset($_GET['code']) and (!empty($_GET['code'])) and is_string($_GET['code']) and ($this->code != $_GET['code']))
        {
            $this->code = $_GET['code'];
        }

        if (empty($this->code))
        {
            $url = "http://www.daniweb.com/api/oauth?client_id={$this->clientId}&redirect_uri=" . urlencode($currentUrl);
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_HEADER, true);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            preg_match("@(Location: )(https?://([-\w\.]+)+(:\d+)?(/([\w/_\-\.]*(\?\S+)?)?)?)@", $result, $matches);
            $targetUrl = $matches[2];

            $join = $targetUrl == "http://www.daniweb.com/members/join";

            if (!$join and in_array($httpCode, array (301, 302)))
            {
                $urlParts = parse_url($targetUrl);
                parse_str($urlParts['query'], $queryParts);

                $this->code = $queryParts['code'];
            }
            else
            {
                header("Location: $url");
            }
        }

        if (empty($this->AccessToken))
        {
            $ch = curl_init('http://www.daniweb.com/api/access_token');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_HEADER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS,
                array
                (
                    'code' => $this->code,
                    'redirect_uri' => $currentUrl,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                )
            );

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            preg_match("@(Location: )(https?://([-\w\.]+)+(:\d+)?(/([\w/_\-\.]*(\?\S+)?)?)?)@", $result, $matches);
            $targetUrl = $matches[2];

            if ($httpCode == 301 or $httpCode == 302)
            {
                $urlParts = parse_url($targetUrl);
                parse_str($urlParts['query'], $queryParts);
                $this->AccessToken = $queryParts['access_token'];
            }
        }
    }
}
?>