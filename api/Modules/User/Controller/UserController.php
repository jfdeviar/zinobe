<?php

namespace Modules\User\Controller;

use Core\BaseController;
use Modules\Api\Controller\ApiController;
use Modules\Data\Model\Data;
use Modules\Notification\Model\Notification;
use Modules\User\Model\Record;
use Modules\User\Model\Register;
use Modules\User\Model\Token;
use Modules\User\Model\User;
use Exception;
use Modules\User\Service\Client;

class UserController extends BaseController
{

    /**
     * @return bool|string
     * @throws Exception
     */
    public function getLogin(): bool|string
    {
        $user = User::$current;
        if(!$user){
            throw new Exception('Acceso no autorizado',402);
        }

        return ApiController::getResponse(message: "Bienvenido ".$user->first_name,data: $user->filterApi());
    }

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
            'password' => $password,
        ]);

        //Notification::sendNotification($register,'Para verificar su cuenta, por favor ingrese el siguiente código: '.$register->code);
        return ApiController::getResponse(message: "Se ha enviado un código de verificación a: ".$register->phone);
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    public function doRecover(): bool|string
    {
        $phone = $this->params['phone'];

        $user = User::first([
            "phone" => $phone,
        ]);

        if(!$user){
            throw new Exception("Usuario no encontrado",500);
        };

        $user->generateCode();
        $user->save();

        //Notification::sendNotification($user,'Para reiniciar su contraseña, por favor ingrese el siguiente código: '.$user->code);
        return ApiController::getResponse(message: "Se ha enviado un sms a ".$user->phone);
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    public function doReset(): bool|string
    {
        $phone = $this->params['phone'];
        $code = $this->params['code'];
        $password = $this->params['password'];

        $user = User::first([
            "phone" => $phone,
            "code" => $code
        ]);

        if(!$user){
            throw new Exception("El código es incorrecto",500);
        };

        $user->setPassword($password);
        $user->code = null;
        $user->save();

        User::setCurrent($user);
        Token::generateToken();

        return ApiController::getResponse(message: "Se ha cambiado su contraseña");
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

        $first_name = '';
        $last_name = '';

        Client::fillData($identification,$first_name,$last_name);

        $user = User::create([
            'phone' => $phone,
            'identification' => $identification,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ]);

        Record::create([
            'identification' => $identification,
            'phone' => $phone,
            'email' => $user->email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_id' => $user->id
        ]);

        User::setCurrent($user);

        Data::registerData('Consultado',1);
        Data::registerData('Registro',1);

        Token::generateToken();
        $register->remove();

        Notification::sendNotification($user,'Para verificar su cuenta, por favor ingrese el siguiente código: '.$register->code);
        return ApiController::getResponse(message: "Se ha confirmado su cuenta");
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    public function doLogin(){

        $phone = $this->params['phone'];
        $password = $this->params['password'];
        $user = User::first([
            "phone" => $phone
        ]);


        if(!$user || !$user->confirmPassword($password)){
            throw new Exception("La contraseña no coincide o el usuario no existe",500);
        }
        User::setCurrent($user);

        Token::generateToken();

        return ApiController::getResponse(message: "Ingreso exitoso", data: $user->filterApi());
    }

}
