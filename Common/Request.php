<?php
namespace App\Common;
/*
*   $_POST, $_GET, $_SESSION 등의 요청값을 프로퍼티화 
*/
class Request 
{
    protected $request;

    public function __construct() 
    {
        $this->request = new \stdClass();
    }

    public function set(array $args = []) 
    {
        foreach($args as $key => $value) {
            $this->request->{$key} = $value;
        }
        return $this;
    }

    public function get() 
    {
        return $this->request;
    }
}
