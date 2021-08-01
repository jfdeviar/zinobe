<?php

namespace Modules\User\Controller;

use Core\BaseController;
use Modules\Api\Controller\ApiController;
use Modules\Notification\Model\Notification;
use Modules\User\Model\User;
use Exception;

class UserController extends BaseController
{

    /**
     * @return bool|string
     * @throws Exception
     */
    public function doRegister(): bool|string
    {

        $phone = $this->params['phone'];
        $identification = $this->params['identification'];
        $password = $this->params['password'];

        $user = User::first([
            "OR" => [
                "phone" => $phone,
                "identification" => $identification
            ]
        ]);


        if($user){
            throw new Exception("El usuario que ingresó, ya se encuentra registrado",500);
        };



        $user = new User([
            'phone' => $phone,
            'identification' => $identification,
            'password' => $password
        ]);

        Notification::sendNotification($user,'Para verificar su cuenta, por favor ingrese el siguiente código: '.$user->code);



        return ApiController::getResponse(message: "Se ha enviado un código de verificación a: ".$user->phone);
    }
}
