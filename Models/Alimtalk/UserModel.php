<?php
namespace App\Models\Alimtalk;

use App\Models\Common\BaseEntity;

class UserModel extends BaseEntity 
{
    protected $userId;
    protected $dbIp;

    public function getUserId() 
    {
        return $this->userId;
    }

    public function setUserId($id) 
    {
        $this->userId = $id;
        return $this;
    }

    public function getDbIp() 
    {
        return $this->dbIp;
    }

    public function setDbIp($ip) 
    {
        $this->dbIp = $ip;
        return $this;
    }
}