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
                $authorization = $headers['Authorization'];
                $token = Token::first([
                    'token' => $authorization,
                    'due_date[>]' => gmdate('Y-m-d H:i:s')
                ]);

                if(!$token){
                    throw new Exception('Token vencido',401);
                }

                $payload = JWT::decode($authorization, Util::$config['keys']['public']);
                $payload['user']['id'] = $token->filterApi();

                if (Util::$config['keys']['private']!=$payload['prk'] || $payload['user']['id']!=$token->user_id){
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
