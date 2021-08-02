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
        if (!isset($properties['code'])){
            $this->generateCode();
        }
        parent::__construct($properties);
    }

}
