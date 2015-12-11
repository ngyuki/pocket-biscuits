<?php
namespace App;

use App\Application;
use Symfony\Component\HttpFoundation\Request;

class Controller
{
    public function login(Application $app)
    {
        $requestToken = $app->oauth->fetchRequestToken();
        $app->session->set("requestToken", $requestToken);
        $authorizeUrl = $app->oauth->generateAuthorizeUrl($requestToken);

        return $app->redirect($authorizeUrl);
    }

    public function authorize(Application $app)
    {
        $requestToken = $app->session->get("requestToken");
        $accessToken = $app->oauth->fetchAccessToken($requestToken);
        $app->session->set('accessToken', $accessToken);

        return $app->redirect($app->path('top'));
    }

    public function logout(Application $app)
    {
        $app->session->clear();
        return $app->redirect($app->path('top'));
    }

    public function latest(Application $app)
    {
        $list = $app->oauth->retrieveLatest();
        $username = $app->oauth->getUsername();

        return $app->render('index.html.twig', [
            'list' => $list,
            'username' => $username
        ]);
    }

    public function random(Application $app)
    {
        $list = $app->oauth->retrieveRandom();
        $username = $app->oauth->getUsername();

        return $app->render('index.html.twig', [
            'list' => $list,
            'username' => $username
        ]);
    }

    public function archive(Application $app, Request $request)
    {
        $item_id = $request->get('item_id');
        $app->oauth->archive($item_id);

        return $app->json(true);
    }

    public function unarchive(Application $app, Request $request)
    {
        $item_id = $request->get('item_id');
        $app->oauth->readd($item_id);

        return $app->json(true);
    }

    public function delete(Application $app, Request $request)
    {
        $item_id = $request->get('item_id');
        $app->oauth->delete($item_id);

        return $app->json(true);
    }
}
