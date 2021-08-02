<?php

namespace Modules\User\Controller;

use Core\BaseController;
use Modules\Api\Controller\ApiController;
use Modules\Notification\Model\Notification;
use Modules\User\Model\Register;
use Modules\User\Model\Token;
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

        $register = Register::create([
            'phone' => $phone,
            'identification' => $identification,
            'password' => $password
        ]);

        //Notification::sendNotification($user,'Para verificar su cuenta, por favor ingrese el siguiente código: '.$register->code);
        return ApiController::getResponse(message: "Se ha enviado un código de verificación a: ".$register->phone);
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    public function doConfirm(): bool|string
    {
        $phone = $this->params['phone'];
        $identification = $this->params['identification'];
        $code = $this->params['code'];
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

        $register = Register::first([
            "phone" => $phone,
            "identification" => $identification,
            "code" => $code
        ]);

        if(!$register){
            throw new Exception("El código ingresado no es valido",500);
        };

        if(!$register->confirmPassword($password)){
            throw new Exception("La contraseña no coincide",500);
        }


        $user = User::create([
            'phone' => $phone,
            'identification' => $identification,
            'password' => $password,
        ]);

        User::setCurrent($user);

        Token::generateToken();
        $register->remove();

        //Notification::sendNotification($user,'Para verificar su cuenta, por favor ingrese el siguiente código: '.$register->code);
        return ApiController::getResponse(message: "Se ha confirmado su cuenta");
    }

}
