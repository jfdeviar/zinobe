<?php


namespace Core;


use Modules\User\Model\User;

class BaseModel
{
    protected array $api = ['id','slug']; //lo que retornamos en el api publico
    protected array $api_private = []; //lo que retornamos en el api privado
    protected array $fill = ['slug'];// lo que insertamos en la base de datos

    protected int $id=0;
    protected String $slug='';
    public static String $table = "";
    protected String $created_at;
    protected String $updated_at;

    protected bool $has_slug = true;


    public function __construct(array $properties=[]){
        foreach($properties as $key => $value){
            $method = 'set'.ucfirst($key);
            if(method_exists($this,$method)) {
                $this->{$method}($value);
                continue;
            }
            $this->{$key} = $value;
        }

        if (!isset($properties['slug'])){
            $this->generateSlug($properties);
        }
    }

    public function generateSlug($properties){
        if ($this->has_slug){
            $this->slug = "";
        }
    }

    /**
     * @return array
     */
    public function filllData($fields):array{
        $data = [];
        foreach($fields as $key){
            $method = 'get'.ucfirst($key);
            if(method_exists($this,$method)){
                $data[$key] = $this->{$method}();
            } elseif(property_exists($this, $key)){
                $data[$key] = $this->$key;
            } else {
                $data[$key] = null;
            }
        }

        return $data;
    }

    public function save(){
        $data = $this->filllData($this->getFill());


        if (!$this->id){
            $object = static::insert($data);
            $this->id = $object->id;
        } else {
            static::update(['id'=>$this->id],$data);
        }
    }

    public function remove(){
        static::delete(['id'=>$this->id]);
    }

    /**
     * @return array
     */
    public function filterApi(): array
    {
        return $this->filllData($this->getApi());

    }

    /**
     * @return array
     */
    public function getApi(): array
    {
        return User::$current?$this->api:array_merge($this->api,$this->api_private);
    }

    /**
     * @return array
     */
    public function getFill(): array
    {
        return array_merge($this->fill,$this->has_slug?['slug']:[]);
    }


    /**
     * @param array|null $objects
     * @return array
     */
    public static function convertObject(?array $objects=[]):array
    {
        foreach ($objects as &$object){
            $object = new static($object);
        }

        return $objects;
    }

    /**
     * @param $filter
     * @param string $select
     * @return ?static
     */
    public static function first($filter,$select="*"): ?static
    {
        $filter['LIMIT'] = 1;
        $data = static::get($filter,$select);
        if (count($data)==0){
            return null;
        }

        return $data[0];

    }

    /**
     * @param array $filter
     * @param array|string $select
     * @return array
     */
    public static function get(array $filter,array|string $select="*"): array
    {
        if (empty(static::$table)){
            return [];
        }

        $result = Util::$database->select(static::$table, $select, $filter);
        return static::convertObject($result);

    }


    /**
     * @param array $data
     * @return static|null
     */
    public static function insert(array $data): ?static
    {

        if (empty(static::$table)){
            return null;
        }

        try {
            Util::$database->insert(static::$table, $data);
        } catch (\Exception $e){
            var_dump($e);
            die;
        }


        return static::first(['id'=>Util::$database->id()]);
    }

    /**
     * @param array $data
     * @return static|null
     */
    public static function create(array $data): ?static
    {
        $object = new static($data);
        $object->save();
        return $object;
    }


    /**
     * @param array $filter
     * @param array $data
     */
    public static function update(array $filter,array $data=[])
    {
        Util::$database->update(static::$table, $data, $filter);

    }

    /**
     * @param array $filter
     */
    public static function delete(array $filter)
    {
        Util::$database->delete(static::$table, $filter);
    }

}
