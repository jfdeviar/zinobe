<?php


namespace Core;


class BaseBoot
{
    protected array $params;
    protected array $headers;

    public function __construct()
    {
        $this->params = $_GET ?? $_POST;

    }
}
