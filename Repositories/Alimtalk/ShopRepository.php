<?php
namespace App\Repositories\Alimtalk;

use App\Common\Util;
use App\Common\LogLevel;
use App\Models\Alimtalk\ShopModel;
use App\Repositories\Alimtalk\Interfaces\ShopRepositoryInterface;
use App\Repositories\Alimtalk\BaseRepository;

class ShopRepository extends BaseRepository implements ShopRepositoryInterface {
    // 대상 Shop의 서비스 기간 정보를 가져온다.
    public function selectServiceLifeInfo(ShopModel $shop) {
        $query = " select shop service life info query ";
        $param = [];

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        $result = $this->db->run($query, $param);

        $returnModel = (new ShopModel())
            ->setShopId($result[0]['id'])
            ->setServiceStart($result[0]['start'])
            ->setPayEnd($result[0]['end']);
        
        return $returnModel;
    }

    // 대상 Shop에 월간 정기 결제를 적용한다.
    public function applyMonthlyService(ShopModel $shop) {
        $query = " apply monthly program query ";
        $param = [];
        
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        return $this->db->run($query, $param);
    }
}