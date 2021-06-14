<?php
namespace App\Adapters\Interfaces;

interface LogAdapterInterface 
{
    public function writeLog($level, $str, $path);
}