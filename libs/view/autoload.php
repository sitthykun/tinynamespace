<?php
/**
 * RainTPL4 engine main class
 *
 * @package Rain
 * @version 4.0
 */
if(!defined("BASE_DIR"))
    define("BASE_DIR", dirname(dirname(__DIR__)));

// register the autoloader
spl_autoload_register('RainTplAutoloader');

// autoloader
function RainTplAutoloader($class)
{
    // it only autoload class into the Rain scope
    if (strpos($class,'Rain\\') !== false){

        // transform the namespace in path
        $path = str_replace("\\", DIRECTORY_SEPARATOR, $class);

        $paths = array(
            BASE_DIR . "/library/" . $path . ".php",
            BASE_DIR . "/library/RainTPL3Compatibility/" . $path . ".php",
        );

        foreach ($paths as $path)
        {
            if (is_file($path))
            {
                require_once $path;
                return;
            }
        }
    }

}