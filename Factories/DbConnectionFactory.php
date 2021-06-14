<?php
namespace App\Factories;

use App\Repositories\Alimtalk\Interfaces\UserRepositoryInterface;
use App\Models\Common\DbConnectionEntity;
use App\Models\Alimtalk\UserModel;
use App\Common\Util;

class DbConnectionFactory 
{
    protected $userRepository;
    protected $legecyDb;
    protected $alimtalkDb;
    protected $userDb;

    public function __construct(UserRepositoryInterface $userRepositoryInterface) 
    {
        $this->userRepository = $userRepositoryInterface;
    }
    
    public function create() 
    {
        $userId = Util::GetUserId();
        $this->legecyDb = new \Pdocon(_LEGACY_DB_NAME_, _LEGACY_DB_ID_, _LEGACY_DB_PW_);
        $this->alimtalkDb = new \Pdocon(_ALIMTALK_DB_NAME_, _ALIMTALK_DB_ID_, _ALIMTALK_DB_PW_, _ALIMTALK_DB_IP_);
        
        $user = $this->userRepository
            ->setDbConnection($this->alimtalkDb)
            ->getUserByUserId(
                (new UserModel())->setUserId($userId)
            );

        // 특수한 db 환경에 따른 db travel 로직이 추가.
        $this->userDb = new \Pdocon(_USER_DB_NAME_, _USER_DB_ID_, _USER_DB_PW_, $user->getDbIp());
        
        return (new DbConnectionEntity())
            ->setLegacyDb($this->legecyDb)
            ->setAlimtalkDb($this->alimtalkDb)
            ->setUserDb($this->userDb);
    }
}