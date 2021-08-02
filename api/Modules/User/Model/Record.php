<?php

namespace Modules\User\Model;

use Core\BaseModel;

class Record extends BaseModel
{

    protected array $api_private = ['identification','phone','email','first_name','last_name']; //lo que retornamos en el api privado

    public ?int $identification = null;
    public ?int $phone = null;
    public ?String $email = null;
    public ?String $first_name = null;
    public ?String $last_name = null;
    public ?int $user_id = null;

    public static String $table = "records";
    protected array $fill = ['identification','phone','email','first_name','last_name','user_id'];

    public function __construct(array $properties = [])
    {
        parent::__construct($properties);
    }

    public function generateSlug($properties){
        $this->slug = $properties['identification'];
    }

}
