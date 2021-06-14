<?php
namespace App\Common;
/*
*   의존성 주입을 위한 DI 컨테이너
*/
class DiContainer 
{
    protected $container;

    public function __construct() 
    {
        $this->container = new \stdClass();
    }

    public function setObject($name, $obj) 
    {
        $this->container->{$name} = $obj;
        return $this;
    }

    public function setClassPath($name, $classPath, $args = []) 
    {
        require_once($_SERVER['DOCUMENT_ROOT']. $classPath);
        
        $this->container->{$name} = (new \ReflectionClass($name))->newInstanceArgs($args);
    }

    public function get($name) 
    {
        return $this->container->{$name};
    }
}