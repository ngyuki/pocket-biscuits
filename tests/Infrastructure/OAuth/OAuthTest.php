<?php
namespace Tests\Infrastructure\OAuth;

use App\Infrastructure\OAuth\OAuth;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\logicalNot;

class OAuthTest extends TestCase
{
    public function test()
    {
        $key = getenv('POCKET_CUSTOMER_KEY');

        if (strlen($key) === 0) {
            $this->markTestSkipped("must set POCKET_CUSTOMER_KEY env");
        }

        $oauth = new OAuth($key);
        $oauth->setRedirectUri('http://example.com/');

        $requestToken = $oauth->fetchRequestToken();
        assertThat($requestToken, logicalNot(isEmpty()));

        $authorizeUrl = $oauth->generateAuthorizeUrl($requestToken);
        assertThat($authorizeUrl, logicalNot(isEmpty()));
    }
}
