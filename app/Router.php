<?php
/**
 * Project: procambodia.dev
 * User: MasakoKh or Sitthykun LY
 * Date: 5/31/15
 * Time: 3:15 PM
 */

namespace app;

use app\TSingleton;
use app\systems\URI;


class Router
{
    use TSingleton;

    /**
     *
     */
    static public function executePage()
    {
        // self::getInstance();
        self::renderController();
    }

    /**
     * @param $string
     * @return mixed
     */
    private function removeDotHtml($string)
    {
        return str_replace('.html', '', $string);
    }

    /**
     * @return mixed
     */
    public function renderController()
    {
        $sURI           = new URI($_SERVER['REQUEST_URI']);
        $uriPaths       = explode('/', $sURI->getSubURI());
        $mainModuleName = '';
        $controllerName = '';
        $methodName     = '';

        // validate uri
        if (count($uriPaths) > 2)
        {
            // path 0 does not need
            // path 1 is a main path as folder
            // path 2 is a controller name
            // path 3 is a method name
            if (isset($uriPaths[1]))
            {
                // main module name
                $mainModuleName = $uriPaths[1];

                // controller name
                if (isset($uriPaths[2]))
                {
                    // controller name
                    $controllerName = ucfirst($uriPaths[2]) . 'Controller';

                    // method name
                    if (isset($uriPaths[3]))
                    {
                        // method name
                        $methodName = self::removeDotHtml($uriPaths[3]);
                    }
                    else
                    {
                        // set default but we have __construct as default
                        // $methodName = 'index';
                        $methodName = '';
                    }
                }
            }

            // no controller or default controller
            $virtualClassName   = 'pages\\' . $mainModuleName . '\\modules\\controllers\\' . $controllerName;
            // $vi = 'pages\backend\modules\controllers';
            $className          = new $virtualClassName();

            // check method name of the class
            if (method_exists($className, $methodName))
            {
                // load method
                call_user_func([$className, $methodName]);
            }
            // load constructor
            else
            {
                // load method
                call_user_func([$className, '__construct']);
            }
        }
    }
}