<?php

namespace Modules\Api\Boot;

use Core\BaseBoot;
use Exception;
use Modules\Api\Controller\ApiController;

class RegisterLog extends BaseBoot
{
    public function fire()
    {
        ApiController::registerLog('request: '.$_SERVER["HTTP_HOST"].' '.$_SERVER["REQUEST_URI"].' params: '.var_export($this->params,true),'log');
    }

}
