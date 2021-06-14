<?php
// 사용하는 클래스 auto load
spl_autoload_register(function($className){
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $namespace = lcfirst($namespace);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = $_SERVER["DOCUMENT_ROOT"]. '/'. str_replace('\\', '/', $namespace) . '/';
    }
    $fileName .= str_replace('\\', '/', $className) . '.php';
    require_once($fileName);
});