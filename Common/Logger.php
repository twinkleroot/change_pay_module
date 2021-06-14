<?php
namespace App\Common;

use App\Adapters\Interfaces\LogAdapterInterface;

class Logger 
{
    protected $logger;

    public function __construct(LogAdapterInterface $logger) 
    {
        $this->logger = $logger;
    }

    public function log($level, $str, $path='Log') 
    {
        $this->logger->writeLog($level, $str, $path);
    }
}