<?php
namespace App;

class OAuthMock implements OAuthInterface
{
    private $authorizeUrl;
    private $accessToken;

    public function __construct($authorizeUrl)
    {
        $this->authorizeUrl = $authorizeUrl;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function hasAccessToken()
    {
        return strlen($this->accessToken) > 0;
    }

    public function getUsername()
    {
        return "dummy";
    }

    public function fetchRequestToken()
    {
        return "dummy_request_token";
    }

    public function generateAuthorizeUrl($requestToken)
    {
        return $this->authorizeUrl;
    }

    public function fetchAccessToken($requestToken)
    {
        return "dummy:dummy_access_token";
    }

    public function retrieveLatest()
    {
        $file = file_get_contents(__DIR__ . '/../mock/data.json');
        return json_decode($file, true);
    }

    public function retrieveRandom()
    {
        $file = file_get_contents(__DIR__ . '/../mock/data.json');
        return json_decode($file, true);
    }

    public function archive($item_id)
    {
        switch ($item_id) {
            case '8888888888':
                sleep(1);
                return;
            case '9999999999';
                sleep(1);
                throw new \RuntimeException("oops!");
        }
    }

    public function readd($item_id)
    {
        $this->archive($item_id);
    }

    public function delete($item_id)
    {
        $this->archive($item_id);
    }
}
