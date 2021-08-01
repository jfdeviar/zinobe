<?php


namespace Core;
trait ConstructParams {

    protected array $params;
    protected array $headers;

    public function __construct()
    {

        if (!isset($_SERVER['REQUEST_METHOD'])){
            return;
        }

        if ($_SERVER['REQUEST_METHOD']==="POST"){
            $this->params = $_POST;
        } else {
            $this->params = $_GET;
        }

    }
}


