<?php
/**
 * Project: procambodia.dev
 * User: MasakoKh or Sitthykun LY
 * Date: 6/13/15
 * Time: 2:31 AM
 */

namespace app;

use app\loader\ConfigLoader;


class Model
{
    // properties
    protected $connection;
    protected $properties;

    /**
     *
     */
    protected function connectedContent()
    {
        $this->properties     = ConfigLoader::load()->getContent();

        // connect
        $this->connection = pg_connect(
            $this->getConnectionString(
                $this->properties['host'],
                $this->properties['port'],
                $this->properties['dbname'],
                $this->properties['username'],
                $this->properties['password']
            )
        );

        // validate
        if(!$this->connection)
        {
            echo "Error : Unable to open database\n";
        }
        else
        {
            echo "Opened database successfully\n";
        }
    }

    /**
     *
     */
    protected function closeConnection()
    {
        // validate
        if (isset($this->connection))
        {
            pg_close($this->connection);
        }
    }

    /**
     *
     */
    public function connectedMail()
    {
        $this->properties     = ConfigLoader::load()->getContent();

        // connect
        $this->connection = pg_connect(
            $this->getConnectionString(
                $this->properties['host'],
                $this->properties['port'],
                $this->properties['dbname'],
                $this->properties['username'],
                $this->properties['password']
            )
        );

        // validate
        if(!$this->connection)
        {
            echo "Error : Unable to open database\n";
        }
        else
        {
            echo "Opened database successfully\n";
        }
    }

    /**
     * @param $sql
     */
    protected function executeQuery($sql)
    {
        $id = 9;
        $name = "BMW";
        $price = 36000;

        $sql = "INSERT INTO cars VALUES($1, $2, $3)";

        pg_prepare($this->connection, "prepare1", $sql)
        or die ("Cannot prepare statement\n");

        pg_execute($this->connection, "prepare1", array($id, $name, $price))
        or die ("Cannot execute statement\n");
    }

    /**
     * @param $host
     * @param $port
     * @param $dbname
     * @param $user
     * @param $password
     * @return string
     */
    private function getConnectionString($host, $port, $dbname, $user, $password)
    {
        $host           = 'host=' . $host;
        $port           = 'port=' . $port;
        $dbName         = 'dbname=' . $dbname;
        $credentials    = 'user=' . $user . ' password=' . $password;
        $connectString  = $host . $port . $dbName . $credentials;

        // return finally string
        return $connectString;
    }

    /**
     * @param $sql
     * @return bool
     */
    protected function getQuery($sql)
    {
        $ret = pg_query($this->properties['dbname'], $sql);

        // validate
        if(!$ret)
        {
            echo pg_last_error($this->properties['dbname']);
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * @param $sql
     * @return array|null
     */
    protected function getFetch($sql)
    {
        $ret = pg_query($this->properties['dbname'], $sql);

        // validate
        if(!$ret)
        {
            echo pg_last_error($this->properties['dbname']);
            return null;
        }
        else
        {
            return pg_fetch_row($ret);
        }
    }
}