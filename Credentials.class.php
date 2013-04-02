<?php
class Credentials
{
    private $clientId;
    private $clientSecret;

    public $Code;
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