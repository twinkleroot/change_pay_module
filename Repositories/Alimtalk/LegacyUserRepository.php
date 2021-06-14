<?php
namespace App\Repositories\Alimtalk;

use App\Common\Util;
use App\Common\LogLevel;
use App\Models\Alimtalk\LegacyUserModel;
use App\Repositories\Alimtalk\Interfaces\LegacyUserRepositoryInterface;
use App\Repositories\Alimtalk\BaseRepository;

class LegacyUserRepository extends BaseRepository implements LegacyUserRepositoryInterface 
{
    // 주문자 가져오기
    public function getUser($model) 
    {
        $query =
            " get user query ";
        $param = [];
        
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        $result = $this->db->run($query, $param);
        
        $returnModel = (new LegacyUserModel())
            ->setId($result[0]['id'])
            ->setTel($result[0]['tel'])
            ->setEmail($result[0]['email']);

        return $returnModel;
    }

}