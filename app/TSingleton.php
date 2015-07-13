<?php
/**
 * Project: procambodia.dev
 * User: MasakoKh or Sitthykun LY
 * Date: 5/31/15
 * Time: 12:33 PM
 */

namespace app;

/**
 * Class TSingleton
 * @package app
 * @see http://stackoverflow.com/questions/7104957/building-a-singleton-trait-with-php-5-4
 */
trait TSingleton
{
    protected static $instance;

    /**
     * @return static
     */
    final public static function getInstance()
    {
        return isset(static::$instance) ? static::$instance: static::$instance = new static;
    }

    /**
     *
     */
    final private function __clone()
    {

    }

    /**
     *
     */
    final private function __wakeup()
    {

    }
}