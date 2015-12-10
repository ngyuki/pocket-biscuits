<?php
namespace App;

use Silex\Application\UrlGeneratorTrait;
use Silex\Application\TwigTrait;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property Session $session
 * @property \Twig_Environment $twig
 * @property OAuthInterface $oauth
 */
class Application extends \Silex\Application
{
    use TwigTrait;
    use UrlGeneratorTrait;

    public function __get($name)
    {
        return $this[$name];
    }

    public function init()
    {
        $this->initConfig();
        $this->initService();
        $this->initRoute();

        return $this;
    }

    protected function initConfig()
    {
        $dir = realpath(__DIR__ . '/../config');
        $env = $this['env'] = getenv('APP_ENV') ?: 'dev';

        $list = [
            "$dir/default.php",
            "$dir/envs/$env.php",
            "$dir/config.php",
        ];

        foreach ($list as $fn) {
            if (file_exists($fn)) {
                /** @noinspection PhpUnusedParameterInspection */
                call_user_func(static function ($app) {
                    /** @noinspection PhpIncludeInspection */
                    require func_get_arg(1);
                }, $this, $fn);
            }
        }
    }

    protected function initService()
    {
        $this->register(new ServiceControllerServiceProvider());

        $this->register(new SessionServiceProvider());

        $this->register(new UrlGeneratorServiceProvider());

        $this->register(new TwigServiceProvider(), array(
            'twig.path' => dirname(__DIR__) . '/views',
        ));

        $this['twig.options'] = $this->share(function () {
            if ($this['debug']) {
                return [];
            } else {
                $dir = $this['tmp_dir'] . '/twig';
                is_dir($dir) or mkdir($dir);
                return ['cache' => $dir];
            }
        });

        $this['oauth'] = $this->share(function () {
            $consumerKey = $this['pocket.consumer_key'];
            return new OAuth($consumerKey, $this->url('authorize'));
        });

        $this['app.controller'] = function() {
            return new Controller();
        };
    }

    protected function initRoute()
    {
        $this->before(function (Request $request) {
            if (preg_match('#^/(login|authorize|logout)$#', $request->getPathInfo()) == 0) {
                $accessToken = $this->session->get("accessToken");
                if (strlen($accessToken)) {
                    $this->oauth->setAccessToken($accessToken);
                }
                if ($this->oauth->hasAccessToken() == false) {
                    return $this->render('login.html.twig');
                }
            }
        });

        $this->before(function (Request $request) {
            if ($request->isMethod('POST')) {
                if ($request->isXmlHttpRequest() == false) {
                    $this->abort(400);
                }
            }
        });

        $this->get('/login', 'app.controller:login')->bind("login");

        $this->get('/authorize', 'app.controller:authorize')->bind("authorize");

        $this->get('/logout', 'app.controller:logout')->bind('logout');

        $this->get('/', 'app.controller:latest')->bind("top");

        $this->get('/random', 'app.controller:random')->bind("random");

        $this->post('/archive', 'app.controller:archive')->bind("archive");

        $this->post('/unarchive', 'app.controller:unarchive')->bind("unarchive");
    }
}
