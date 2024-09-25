<?php

class Database
{
    private static $connection = null;

    public static function connect()
    {
        $env = parse_ini_file('local.env');
        if (null == self::$connection) {
            try {
                self::$connection = new PDO(
                    sprintf(
                        'pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s',
                        $env['POSTGRES_HOST'],
                        5432,
                        $env['POSTGRES_DB'],
                        $env['POSTGRES_USER'],
                        $env['POSTGRES_PASSWORD']
                    )
                );
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$connection;
    }

    public static function disconnect()
    {
        self::$connection = null;
    }
}