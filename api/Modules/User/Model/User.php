<?php

namespace Modules\User\Model;

use Core\BaseModel;

class User extends BaseModel
{

    protected array $fill = ['identification','phone','first_name','last_name','email','active','code'];

    protected int $identification = 0;
    public int $phone = 0;
    public ?String $first_name = null;
    public ?String $last_name = null;
    public ?String $email = null;
    public int $active=0;
    public ?String $code = null;

    public static String $table = "users";
    public static User $current;

    public function __construct(array $properties = [])
    {
        //Todo create validation on create properties
        if (!isset($properties['code'])){
            $this->generateCode();
        }
        parent::__construct($properties);
    }

    public function generateCode(){
        $digits = 6;
        $this->code = rand(pow(10, $digits-1), pow(10, $digits)-1);;
    }

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




}
