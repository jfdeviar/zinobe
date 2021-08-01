<?php

namespace Modules\User\Model;

use Core\BaseModel;
use Modules\Api\Controller\ApiController;

class User extends BaseModel
{

    protected array $fill = ['identification','phone','first_name','last_name','email','password'];

    protected int $identification = 0;
    public int $phone = 0;
    public ?String $first_name = null;
    public ?String $last_name = null;
    public ?String $email = null;
    public ?String $password = null;

    public static String $table = "users";
    public static User $current;

    public function generateSlug($properties){
        $this->slug = $properties['identification'];
    }

    /**
     * @param User $current
     */
    public static function setCurrent(User $current): void
    {
        self::$current = $current;
    }

    /**
     * @param String $password
     */
    public function setPassword(String $password): void
    {
        $info = password_get_info($password);
        if ($info['algo'] ?? null){
            $this->password = $password;
        } else {
            $this->password = password_hash($password,PASSWORD_BCRYPT);
        }
    }

    /**
     * @param String $password
     * @return bool
     */
    public function confirmPassword(String $password):bool
    {
        return password_verify($password,$this->password);
    }


}
