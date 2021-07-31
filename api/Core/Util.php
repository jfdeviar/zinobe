<?php


namespace Core;


class Util
{
    public static array $params;

    public static function loadConfig()
    {
        $config = require 'config.php';
        self::$params = $config;
        error_reporting($config['error_reporting']);

    }
}
