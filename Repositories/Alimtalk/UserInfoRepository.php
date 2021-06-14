<?php
namespace App\Repositories\Alimtalk;

use App\Common\Util;
use App\Common\LogLevel;
use App\Models\Alimtalk\PayHistoryModel;
use App\Models\Alimtalk\UserInfoModel;
use App\Repositories\Alimtalk\Interfaces\UserInfoRepositoryInterface;
use App\Repositories\Alimtalk\BaseRepository;

class UserInfoRepository extends BaseRepository implements UserInfoRepositoryInterface {
    // 알림톡 남은 갯수를 올려준다.
    public function updateRemainCount(PayHistoryModel $payHistory) {
        $query = " update remain count query ";
        $param = [];

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        return $this->db->run($query, $param);
    }

    // 알림톡 남은 갯수를 가져온다.
    public function getRemainCount() {
        $query = " select remain count query ";

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query");

        $result = $this->db->run($query);

        $returnModel = (new UserInfoModel())->setRemainCnt($result[0]['cnt']);

        return $returnModel;
    }
}