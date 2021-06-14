<?
namespace App\Models\NewAdmin;

class IpInfoModel 
{
    protected $lunaIp;

    public function getIp() 
    {
        return $this->lunaIp;
    }

    public function setIp($lunaIp) 
    {
        $this->lunaIp = $lunaIp;
        return $this;
    }
}
