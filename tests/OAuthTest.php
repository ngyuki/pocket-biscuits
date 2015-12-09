<?php
namespace Tests;

use App\OAuth;

class OAuthTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $key = getenv('CUSTOMER_KEY');

        if (strlen($key) === 0) {
            $this->markTestSkipped("must set CUSTOMER_KEY env");
        }

        $oauth = new OAuth($key, "http://example.com/");

        $requestToken = $oauth->fetchRequestToken();
        assertThat($requestToken, logicalNot(isEmpty()));

        $authorizeUrl = $oauth->generateAuthorizeUrl($requestToken);
        assertThat($authorizeUrl, logicalNot(isEmpty()));
    }
}
