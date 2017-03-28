<?php

namespace VivialConnect\Transport;


class ConnectionManager
{
    protected static $connections = [];

    /**
     * Add a connection
     *
     * @param $name
     * @param Connection $connection
     */
    public static function add($name, Connection $connection)
    {
        self::$connections[$name] = $connection;
    }

    /**
     * Get a connection by its name
     *
     * @param $name
     * @throws ResourceException
     * @return Connection
     */
    public static function get($name)
    {
        if (isset(self::$connections[$name])) {
            return self::$connections[$name];
        }
        throw new ResourceException("Connection \"{$name}\" not found");
    }
}
