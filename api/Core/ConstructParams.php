<?php


namespace Core;
trait ConstructParams {

    protected array $params=[];
    protected array $headers=[];

    public function __construct()
    {

        if (!isset($_SERVER['REQUEST_METHOD'])){
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == "PUT" || $_SERVER['REQUEST_METHOD'] == "DELETE") {
            parse_str(file_get_contents('php://input'), $this->params);
            $GLOBALS["_{$_SERVER['REQUEST_METHOD']}"] = $this->params;
            $_REQUEST = $this->params + $_REQUEST;
        }elseif ($_SERVER['REQUEST_METHOD']==="POST"){
            $this->params = $_POST;
        } else {
            $this->params = $_GET;
        }

    }
}


