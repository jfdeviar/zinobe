<?php

namespace Modules\Api\Boot;

use Core\BaseBoot;
use Core\Util;
use Medoo\Medoo;
use Exception;

class ConnectDatabase extends BaseBoot
{

    public function fire()
    {
        $config = Util::$config['database'];
        Util::$database = new Medoo([
            'type' => 'mysql',
            'host' => $config['host'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password']
        ]);

        Util::$database->pdo->beginTransaction();

        try {
            //test connection
            Util::$database->count('users');
        } catch (Exception $e){
            //Execute raw sql file
            $content = file_get_contents('generator/database.base');
            Util::$database->query($content);
            //throw new Exception("No esta configurada la base de datos",500);
        }

    }

}
