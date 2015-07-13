<?php
/**
 * Project: procambodia.dev
 * User: MasakoKh or Sitthykun LY
 * Date: 6/13/15
 * Time: 1:55 AM
 */

namespace app;


class Controller
{
    // properties
    // protected static $dir = __DIR__;

    /**
     * @return string
     */
    protected function getDir()
    {
        return static::$dir;
    }

    /**
     * @param $dir
     * @param $file
     * @param array $params
     */
    public function renderView($dir, $file, $params = [])
    {
        // assign full path file view
        $fullPathFileView = $dir . '/../views/' . $file . '.php';

        // validate file
        if (file_exists($fullPathFileView))
        {
            require $fullPathFileView;
        }
    }
}