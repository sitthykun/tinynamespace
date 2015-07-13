<?php
/**
 * Created by PhpStorm.
 * User: MasakoKh or Sitthykun LY
 * Date: 4/5/15
 * Time: 2:28 PM
 */

namespace app\loader;

/**
 * Class ClassesLoader
 * @package app
 */

use app\systems\Constants;
use app\log\Logger;
use app\Router;

class ClassesLoader
{
    // declare
    private $constants;

    /**
     *
     */
    protected function __construct()
    {
        /*** specify extensions that may be loaded ***/
        spl_autoload_extensions('.php, .class.php, .inc.php');

        /*** nullify any existing autoloads ***/
        // spl_autoload_register(null, false);
        spl_autoload_register(__NAMESPACE__ . '\ClassesLoader::registerClass');

        // constants
        $this->constants = new Constants();
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {

    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {

    }

    /**
     *
     */
    public function execute()
    {
        // execute page
        Router::executePage();
    }

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar Singleton $instance The *Singleton* instances of this class.
     * @see http://www.phptherightway.com/pages/Design-Patterns.html
     * @return Singleton The *Singleton* instance.
     */
    public static function load()
    {
        // instance new variable
        static $instance = null;

        // check variable
        if (null === $instance)
        {
            $instance = new static();
        }

        // return object
        return $instance;
    }

    /**
     *`
     * @param string $name
     */
    static public function registerClass($name)
    {
        // temporary
        $tempFilename = strtr($name . '.php', '\\', DIRECTORY_SEPARATOR);

        // validate file
        if (file_exists($tempFilename) && is_file($tempFilename) && is_readable($tempFilename))
        {
            // load class and add class to classes' array
            require_once $tempFilename;
        }

        // clear
        $tempFilename = NULL;
    }

    /**
     *
     */
    private function test()
    {
        // $sURI = new URI($_SERVER['REQUEST_URI']);
        // load configure environment
        // var_dump(ConfigLoader::load()->getDatabase('content'));

        Logger::console('hello');
        // echo 'Hello';
    }
}