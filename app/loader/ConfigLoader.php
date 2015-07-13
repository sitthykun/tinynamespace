<?php
/**
 * Created by PhpStorm.
 * User: MasakoKh or Sitthykun LY
 * Date: 5/3/15
 * Time: 4:21 PM
 */

namespace app\loader;

use app\loader\ConfigureInterface;


class ConfigLoader implements ConfigureInterface
{
    // data
    private $data;
    private $filePath;
    private $environment;

    /**
     *
     */
    public function __construct()
    {
        // set environment
        // development || production || testing || re-production || staging
        $this->environment = isset($_SERVER[self::SERVER_ENVIRONMENT_NAME]) ? $_SERVER[self::SERVER_ENVIRONMENT_NAME]: self::TEST_ENVIRONMENT;

        // set configuration directory
        $this->filePath = self::CONFIGURATION_PATH . '/' . $this->environment . '.php';

        // load all value into this data
        $this->data     = $this->getAll();
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return include self::getEnvironmentPath();
    }

    /**
     * @return string
     */
    public function getEnvironmentPath()
    {
        return $this->filePath;
    }

    /**
     * @param null $param
     * @return mixed
     */
    public function getDatabase($param = null)
    {
        return isset($param) ? $this->data['database'][$param]: $this->data['database'];
    }

    /**
     * @param null $param
     * @return mixed
     */
    public function getContent($param = null)
    {
        return isset($param) ? $this->getDatabase()['content'][$param]: $this->getDatabase()['content'];
    }

    /**
     * @param null $param
     * @return mixed
     */
    public function getMail($param = null)
    {
        return isset($param) ? $this->getDatabase()['mail'][$param]: $this->getDatabase()['mail'];
    }

    /**
     * @param null $param
     * @return mixed
     */
    public function getRedis($param =  null)
    {
        return isset($param) ? $this->data['redis'][$param]: $this->data['redis'];
    }

    /**
     * @param null $param
     * @return mixed
     */
    public function getStaticPage($param = null)
    {
        return sset($param) ? $this->data['url'][$param]: $this->data['url'];
    }

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @static var Singleton $instance The *Singleton* instances of this class.
     * @see http://www.phptherightway.com/pages/Design-Patterns.html
     * @return Singleton The *Singleton* instance.
     */
    static public function load()
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
}