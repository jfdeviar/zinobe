<?php

namespace Modules\Data\Controller;

use Core\BaseController;
use Modules\Api\Controller\ApiController;
use Exception;
use Modules\Data\Model\Data;
use Modules\User\Model\User;

class DataController extends BaseController
{

    /**
     * @return bool|string
     * @throws Exception
     */
    public function getCurrent(): bool|string
    {
        $date = \DateTime::createFromFormat('Y-m-d',gmdate('Y-m-d'))->setTimezone(new \DateTimeZone('America/Bogota'))->sub(new \DateInterval('P30D'))->format('Y-m-d');
        $user = User::$current;
        $statistics = Data::get([
            'user_id' => $user,
            'format[>=]' => $date,
            "ORDER" => [
                "format" => "ASC"
            ]
        ]);

        $data = [];
        foreach ($statistics as $statistic){
            if (!isset($data[$statistic->format])){
                $data[$statistic->format]=['Registro'=>0,'Consultado'=>0,'format'=>$statistic->format];
            }
            $data[$statistic->format][$statistic->display_name] += $statistic->value;
        }


        return ApiController::getResponse(data: array_values($data));
    }

}
