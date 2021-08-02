<?php

namespace Modules\User\Controller;

use Core\BaseController;
use Modules\Api\Controller\ApiController;
use Modules\Notification\Model\Notification;
use Modules\User\Model\Record;
use Modules\User\Model\Register;
use Modules\User\Model\Token;
use Modules\User\Model\User;
use Exception;
use Modules\User\Service\Client;

class RecordController extends BaseController
{

    function __construct()
    {
        $user = User::$current;
        if (!$user){
            throw new Exception('Acceso no autorizado',401);
        }
        parent::__construct();
    }

    public function getItem($params): bool|string
    {
        $slug = $params['slug'];
        $user = User::$current;

        $record = Record::first([
            'slug' => $slug,
            'user_id' => $user->id
        ]);

        return ApiController::getResponse(data: $record->filterApi());
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    public function getItems(): bool|string
    {
        $user = User::$current;

        $records = Record::get([
            'user_id' => $user->id
        ]);

        $data = [];
        foreach ($records as $record){
            $data[]=$record->filterApi();
        }

        return ApiController::getResponse(data: $data);
    }


    public function doRegister($params){
        $id = $params['id'];
        $identification = $this->params['identification'];
        $phone = $this->params['phone'] ?? '';
        $email = $this->params['email'] ?? '';
        $first_name = $this->params['first_name'] ?? '';
        $last_name = $this->params['last_name'] ?? '';
        $user = User::$current;

        $record = Record::first(['id' => $id,'user_id'=>$user->id]);
        if($record){
            throw new Exception('Ya hay un registro con esa identificación',500);
        }

        Client::fillData($identification,$first_name,$last_name);

        $record = Record::create([
            'identification' => $identification,
            'phone' => $phone,
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_id' => $user->id
        ]);
        return ApiController::getResponse(data: $record->filterApi(),message: "Registro exitoso");

    }


    public function doUpdate($params){
        $id = $params['id'];
        $phone = $this->params['phone'] ?? '';
        $email = $this->params['email'] ?? '';
        $first_name = $this->params['first_name'] ?? '';
        $last_name = $this->params['last_name'] ?? '';
        $user = User::$current;

        $record = Record::first(['id' => $id,'user_id'=>$user->id]);
        if(!$record){
            throw new Exception('No se encontró el registro',500);
        }

        Client::fillData($record->identification,$first_name,$last_name);

        $record->phone = $phone;
        $record->email = $email;
        $record->first_name = $first_name;
        $record->last_name = $last_name;
        $record->save();

        return ApiController::getResponse(data: $record->filterApi(),message: "Registro actualizado");

    }

    public function doRemove($params){
        $id = $params['id'];
        $user = User::$current;

        $record = Record::first(['id' => $id,'user_id'=>$user->id]);
        if(!$record){
            throw new Exception('No se encontró el registro',500);
        }

        $record->remove();

        return ApiController::getResponse(message: "Se ha eliminado el registro");

    }


}
