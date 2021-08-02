<?php

namespace Modules\Notification\Service;


use Core\Util;
use Modules\Api\Controller\ApiController;

class CellVoz
{
    protected ?String $api_key = null;
    protected ?String $account = null;
    protected ?String $password = null;
    protected ?String $api_url = 'https://api.cellvoz.co/v2/';

    public function __construct(){
        $this->loadKeys();
    }

    public function loadKeys(){
        $this->api_key = Util::$config['sms']['api_key'];
        $this->account = Util::$config['sms']['account'];
        $this->password = Util::$config['sms']['password'];
    }

    public static function sendSMS($code,$number,$text){
        ApiController::registerLog('Send sms: '.$text,'sms');
        self::sendRequest('sms/single',['number'=>$code.$number,'message'=>$text,'type'=>1]);
    }

    public function getToken(){
        return self::sendRequest('auth/login',['account'=>$this->account,'password'=>$this->password],'POST');
    }

    private static function sendRequest($calledFunction, $data,$method='POST'){

        $cellvoz = new CellVoz();
        if($calledFunction!='auth/login'){
            $token = $cellvoz->getToken();
        }
        $request_url = $cellvoz->api_url.$calledFunction;

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_URL,$request_url);
        curl_setopt($curl,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$method);
        if($calledFunction!='auth/login') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["authorization: Bearer " . $token->token, "content-type: application/json","api-key: ".$cellvoz->api_key]);
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["content-type: application/json"]);
        }
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        if($method=="POST" || $method=="PATCH"){
            curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
        }
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl)['http_code'];
        curl_close($curl);
        ApiController::registerLog('CellVoz response: '.var_export($response,true),'sms');
        if ($err || $code!=200) {
            ApiController::registerLog('Error on SMS: '.var_export($err,true),'sms');
            return false;
        }

        return json_decode($response);
    }


}
