<?php

namespace Modules\User\Service;


use Core\Util;
use Modules\Api\Controller\ApiController;

class Client
{
    protected ?String $api_key = null;
    protected ?String $api_url = 'https://api.misdatos.com.co/api/co/';

    public function __construct(){
        $this->loadKeys();
    }

    public function loadKeys(){
        $this->api_key = Util::$config['mis_datos']['api_key'];
    }


    public static function fillData($identification,&$first_name,&$last_name){
        if (empty($first_name) || empty($last_name)){
            $data = Client::requestNames($identification);
            if (is_array($data)){
                if (isset($data['firstName']) && $data['firstName'] && empty($first_name)){
                    $first_name = $data['firstName'];
                }

                if (isset($data['lastName']) && $data['lastName'] && empty($last_name)){
                    $last_name = $data['lastName'];
                }
            }
        }
    }

    public static function requestNames($identification,$type="CC"){
        ApiController::registerLog('Request identification: '.$identification,'misdatos');
        return self::sendRequest('consultarNombres','documentType='.$type.'&documentNumber='.$identification);
    }

    private static function sendRequest($calledFunction, $fields){
        $client = new Client();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $client->api_url.$calledFunction,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$client->api_key,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);
        $data = json_decode($response,true);
        ApiController::registerLog('Response identification: '.var_export($data['data']),'misdatos');

        return $data['data'];

    }




}
