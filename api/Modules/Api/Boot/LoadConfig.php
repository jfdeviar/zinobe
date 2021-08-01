<?php

namespace Modules\Api\Boot;

use Core\BaseBoot;
use Core\Util;
use Medoo\Medoo;

class LoadConfig extends BaseBoot
{

    public function fire()
    {
        $config = require 'config.php';
        Util::$config = $config;
        error_reporting($config['error_reporting']);
    }

}
