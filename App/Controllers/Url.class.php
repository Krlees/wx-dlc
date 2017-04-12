<?php


namespace App\Controllers;

use App\Responses\Response;

class Url extends Common
{
    public function shorten()
    {
        $url = $this->request->get('url') or Response::_instance()->callback(1004);

        $result = $this->app->url->shorten($url);

        echo $result;
        exit;
    }
}