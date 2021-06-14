<?php
namespace App\Models\Common;

class DbConnectionEntity 
{
    protected $legacyDb;
    protected $alimtalkDb;
    protected $userDb;

    public function getLegacyDb() 
    {
        return $this->legacyDb;
    }

    public function setLegacyDb($dbCon) 
    {
        $this->legacyDb = $dbCon;
        return $this;
    }

    public function getAlimtalkDb() 
    {
        return $this->alimtalkDb;
    }

    public function setAlimtalkDb($dbCon) 
    {
        $this->alimtalkDb = $dbCon;
        return $this;
    }

    public function getUserDb() 
    {
        return $this->userDb;
    }

    public function setUserDb($dbCon) 
    {
        $this->userDb = $dbCon;
        return $this;
    }
}