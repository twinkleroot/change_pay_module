<?php
namespace App\Common;

use App\Common\Enum;

class LogLevel extends Enum 
{
    const ERROR = 'Error';
    const WARN = 'Warn';
    const INFO = 'Info';
    const DEBUG = 'Debug';
    const TRACE = 'Trace';
}