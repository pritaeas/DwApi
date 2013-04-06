<?php
class DwApiCredentials
{
    /**
     * @var int Client ID.
     */
    private $clientId;

    /**
     * @var string Client secret.
     */
    private $clientSecret;

    /**
     * @var string Code.
     */
    public $Code;

    /**
     * @var string Access token..
     */
    public $AccessToken;

    function __construct($id, $secret)
    {
        $this->clientId = $id;
        $this->clientSecret = $secret;
    }

    public function GetId()
    {
        return $this->clientId;
    }

    public function GetSecret()
    {
        return $this->clientSecret;
    }
}
?>