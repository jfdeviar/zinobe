<?php

namespace Modules\Api\Controller;

use Core\BaseController;
use Exception;
use Modules\User\Model\Token;
use Modules\User\Model\User;

final class ApiController extends BaseController
{

    /**
     * @return false|string
     */
    public static function getStatus(): bool|string
    {
        return self::getResponse();
    }

    /**
     * @param mixed ...$args    data, code, message, actions, headers
     * @return false|string
     */
    public static function getResponse(...$args): bool|string
    {
        $data = ['data'=>[],'code'=>200,'message'=>[],'actions'=>[],'headers'=>[],'status'=>false];

        foreach ($args as $k=>$arg){
            if (in_array($k,array_keys($data))){
                $data[$k] = $arg;
            }
        }

        if (in_array($data['code'],["200","201"])){
            $data['status'] = true;
        }


        $token = Token::$current;
        if (Token::$current){
            $data['headers']['Authorization'] = $token->token;
        }

        foreach($data['headers'] as $k=>$header){
            header($k.": ".$header);
        }
        unset($data['headers']);

        http_response_code($data['code']);
        return json_encode($data);
    }


    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @throws Exception
     */
    public static function getError($errno, $errstr, $errfile, $errline ) {
        $message = "Error: ".$errno.": ".$errstr." in ".$errfile.':'.$errline;
        throw new Exception($message,500);
    }

    /**
     * @param $message
     * @param string $file
     */
    public static function registerLog($message,$file="error"){
        file_put_contents('logs/'.$file.'.log',$message.PHP_EOL ,FILE_APPEND);
    }

}
