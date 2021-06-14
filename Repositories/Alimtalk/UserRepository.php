<?php
namespace App\Repositories\Alimtalk;

use App\Common\Util;
use App\Common\LogLevel;
use App\Repositories\Alimtalk\Interfaces\UserRepositoryInterface;
use App\Repositories\Alimtalk\BaseRepository;
use App\Models\Alimtalk\UserModel;

class UserRepository extends BaseRepository implements UserRepositoryInterface 
{
    public function getUserByUserId(UserModel $user) 
    {
        $query = " select user by userId query ";
        $param = [];
        $msg ="[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param);

        $this->logger->log(LogLevel::INFO(), $msg);

        $result = $this->db->run($query, $param);

        return $user->setId($result[0]['id']);
    }
}