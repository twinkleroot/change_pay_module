<?php
namespace App\Common;

use App\Adapters\Interfaces\SmsAdapterInterface;

class SmsSender 
{
    protected $sender;

    public function __construct(SmsAdapterInterface $sender) 
    {
        $this->sender = $sender;
    }

    public function send($par) 
    {
        return $this->sender->sendSMS($par);
    }
}