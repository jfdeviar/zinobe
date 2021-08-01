<?php

namespace Modules\User\Model;

use Core\BaseModel;

class Register extends User
{

    public static String $table = "registers";
    public ?String $code = null;
    protected array $fill = ['identification','phone','password','code'];

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

}
