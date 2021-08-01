<?php

namespace Modules\User\Model;

use Core\BaseModel;
use Core\Util;
use Firebase\JWT\JWT;

class Token extends BaseModel
{

    protected array $fill = ['user_id','token','due_date'];

    public int $user_id = 0;
    public ?String $token = null;
    public ?String $due_date = null;

    public static String $table = "tokens";
    public static ?Token $current = null;

    protected bool $has_slug = false;

    /**
     * @param array $payload
     * @return Token
     */
    public static function generateToken(array $payload=[]): Token
    {
        $user = User::$current;
        $payload['user'] = $user->filterApi();
        $payload['prk'] = Util::$config['keys']['private'];

        $token = gmdate('U').'_'.JWT::encode($payload, Util::$config['keys']['public']);

        $token = Token::create([
            'user_id' => $user->id,
            'token' => $token,
            'due_date' => gmdate('Y-m-d H:i:s',strtotime("+1 days"))
        ]);

        Token::setCurrent($token);
        return $token;

    }

    /**
     * @param Token|null $current
     */
    public static function setCurrent(?Token $current): void
    {
        self::$current = $current;
    }


}
