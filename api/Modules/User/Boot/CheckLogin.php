<?php

namespace Modules\User\Boot;

use Core\BaseBoot;
use Core\Util;
use Firebase\JWT\JWT;
use Exception;
use Modules\User\Model\Token;
use Modules\User\Model\User;

class CheckLogin extends BaseBoot
{
    public function fire()
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])){
            try {
                if (!$headers['Authorization']){
                    throw new Exception('Token limpio',401);
                }

                $authorization = $headers['Authorization'];
                $authorization = explode("_",$authorization);
                unset($authorization[0]);
                $authorization = implode("_",$authorization);
                $token = Token::first([
                    'token' => $headers['Authorization'],
                    'due_date[>]' => gmdate('Y-m-d H:i:s')
                ]);

                if(!$token){
                    throw new Exception('Token vencido',401);
                }

                Token::update(["id"=>$token->id],['due_date'=>gmdate('Y-m-d H:i:s',strtotime('+1 days'))]);
                $payload = JWT::decode($authorization, Util::$config['keys']['public'],['HS256']);

                if (Util::$config['keys']['private']!=$payload->prk || $payload->user->id!=$token->user_id){
                    throw new Exception('Token invalido',401);
                }

                User::setCurrent(User::first(['id'=>$token->user_id]));
                Token::setCurrent($token);

            } catch (Exception $e){
                throw new Exception('Token de autorización invalido',401);
            }
        }
    }

}
