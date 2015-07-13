<?php
/**
 * Created by PhpStorm.
 * User: MasakoKh or Sitthykun LY
 * Date: 5/3/15
 * Time: 3:06 PM
 */

namespace app\systems;


class URI
{
    private $uri;

    /**
     * @param $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function getDomain()
    {

    }

    public function getURI()
    {
        return $this->uri;
    }

    public function getSubURI()
    {
        return $this->uri;
    }

    public function getAction()
    {
        return $this->uri;
    }

    public function getMethodType()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getFull()
    {
        return $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];
    }

    public function getHTTPProtocol()
    {
        return 'http' . (isset($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == 443 ? 's': '');
    }

    public function splitURI()
    {
        return explode('/', $this->uri);
    }
}