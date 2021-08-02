<?php

namespace Modules\Data\Model;

use Core\BaseModel;
use Modules\Api\Controller\ApiController;
use Modules\User\Model\User;

class Data extends BaseModel
{

    protected array $api_private = ['value','display_name','format'];
    protected array $fill = ['value','display_name','format','user_id'];
    protected bool $has_slug = false;
    public static String $table = "statistics";

    public ?String $display_name = null;
    public ?String $format = null;
    public int $value = 0;
    public int $user_id = 0;

    public static function registerData($display_name,$value=1){
        $user = User::$current;
        if (!$user){
            return;
        }
        $date = \DateTime::createFromFormat('Y-m-d',gmdate('Y-m-d'))->setTimezone(new \DateTimeZone('America/Bogota'))->format('Y-m-d');

        $statistic = Data::first([
            'display_name' => $display_name,
            'format' => $date,
            'user_id' => $user->id
        ]);

        if (!$statistic){
            $statistic = new Data([
                'display_name' => $display_name,
                'format' => $date,
                'user_id' => $user->id,
                'value' => 0
            ]);
        }


        $statistic->value += $value;
        $statistic->save();
    }

}
