<?php
namespace App;

interface OAuthInterface
{
    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken);

    /**
     * @return bool
     */
    public function hasAccessToken();

    /**
     * @return string
     */
    public function fetchRequestToken();

    /**
     * @param string $requestToken
     * @return string
     */
    public function generateAuthorizeUrl($requestToken);

    /**
     * @param string $requestToken
     * @return string
     */
    public function fetchAccessToken($requestToken);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return array
     */
    public function retrieveLatest();

    /**
     * @return array
     */
    public function retrieveRandom();

    /**
     * @param string $item_id
     */
    public function archive($item_id);

    /**
     * @param string $item_id
     */
    public function readd($item_id);
}
