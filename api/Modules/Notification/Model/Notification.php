<?php

namespace Modules\Notification\Model;

use Modules\Notification\Service\CellVoz;
use Modules\User\Model\User;

final class Notification
{

    public static function sendNotification(User $user,String $message): void
    {
        CellVoz::sendSMS('57',$user->phone,$message);
        //TODO mailgun
    }




}
