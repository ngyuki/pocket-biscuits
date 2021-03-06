<?php
namespace App\Infrastructure\OAuth;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class OAuth implements OAuthInterface
{
    const AUTHORIZE_URL = "https://getpocket.com/auth/authorize";
    const ENDPOINT_URL = "https://getpocket.com/v3/";
    const REQUEST_TOKEN_URL = "oauth/request";
    const ACCESS_TOKEN_URL  = "oauth/authorize";

    private string $consumerKey;

    private string $redirectUri = '';
    private string $accessToken = '';
    private string $username = '';

    public function __construct(string $consumerKey)
    {
        $this->consumerKey = $consumerKey;
    }

    public function setRedirectUri(string $redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    public function setAccessToken($accessToken)
    {
        list ($username, $accessToken) = explode(":", $accessToken, 2);

        $this->accessToken = $accessToken;
        $this->username = $username;
    }

    public function hasAccessToken()
    {
        return strlen($this->accessToken) > 0;
    }

    protected function createClient()
    {
        return new Client([
            'base_uri' => self::ENDPOINT_URL,
            RequestOptions::HEADERS => [
                'X-Accept' => 'application/json',
                'User-Agent' => 'Unknown',
            ],
        ]);
    }

    public function fetchRequestToken()
    {
        $response = $this->createClient()->post(self::REQUEST_TOKEN_URL, [
            RequestOptions::JSON => [
                'consumer_key' => $this->consumerKey,
                'redirect_uri' => $this->redirectUri,
            ],
        ]);

        return json_decode($response->getBody(), true)['code'];
    }

    public function generateAuthorizeUrl($requestToken)
    {
        $query = http_build_query([
            'request_token' => $requestToken,
            'redirect_uri' => $this->redirectUri,
        ]);

        return self::AUTHORIZE_URL . '?' . $query;
    }

    public function fetchAccessToken($requestToken)
    {
        $response = $this->createClient()->post(self::ACCESS_TOKEN_URL, [
            RequestOptions::JSON => [
                'consumer_key' => $this->consumerKey,
                'code' => $requestToken,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $this->username = $data['username'];
        $this->accessToken = $data['access_token'];

        return "$this->username:$this->accessToken";
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function retrieve($params)
    {
        $params += [
            'consumer_key' => $this->consumerKey,
            'access_token' => $this->accessToken,
        ];

        $response = $this->createClient()->post('get', [
            RequestOptions::JSON => $params
        ]);

        return json_decode($response->getBody(), true)['list'];
    }

    public function retrieveLatest()
    {
        $list = $this->retrieve([
            'state' => 'unread',
            'tag' => '_untagged_',
            'detailType' => 'simple',
            'count' => 10,
        ]);

        return $list;
    }

    public function retrieveRandom()
    {
        $list = $this->retrieve([
            'state' => 'unread',
            'tag' => '_untagged_',
            'detailType' => 'simple',
        ]);

        $list = array_intersect_key($list, array_flip(array_rand($list, 10)));
        shuffle($list);
        return $list;
    }

    public function modify($action)
    {
        $actions = json_encode([$action]);

        $this->createClient()->get('send', [
            RequestOptions::QUERY => [
                'consumer_key' => $this->consumerKey,
                'access_token' => $this->accessToken,
                'actions' => $actions,
            ]
        ]);
    }

    public function archive($item_id)
    {
        $this->modify([
            'action' => 'archive',
            'item_id' => $item_id,
        ]);
    }

    public function readd($item_id)
    {
        $this->modify([
            'action' => 'readd',
            'item_id' => $item_id,
        ]);
    }

    public function delete($item_id)
    {
        $this->modify([
            'action' => 'delete',
            'item_id' => $item_id,
        ]);
    }
}
