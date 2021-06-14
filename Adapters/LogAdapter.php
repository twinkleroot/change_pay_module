<?php
namespace App\Adapters;

use App\Adapters\Interfaces\LogAdapterInterface;

class LogAdapter implements LogAdapterInterface 
{
    public function writeLog($level, $str, $path="log")
    {
        $dirPath = $_SERVER["DOCUMENT_ROOT"] . "/".$path;
        $logfile = $dirPath."/". date('Y-m-d') . ".log";
        $logstr = "[".date('Y-m-d H:i:s')."] [".$level."] $str\n";
    
        if( !file_exists($dirPath) )
        {
            mkdir($dirPath, 0777, true);
        }
    
        if( !file_exists($logfile) )
        {
            touch($logfile);
            chmod($logfile, 0777);
        }
        error_log($logstr, 3, $logfile);
    }
}