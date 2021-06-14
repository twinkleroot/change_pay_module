<?php
namespace App\Repositories\Alimtalk;

use App\Common\Util;
use App\Common\LogLevel;
use App\Models\Alimtalk\PayHistoryModel;
use App\Repositories\Alimtalk\Interfaces\PayHistoryRepositoryInterface;
use App\Repositories\Alimtalk\BaseRepository;

class PayHistoryRepository extends BaseRepository implements PayHistoryRepositoryInterface 
{
    // 결제 히스토리에 PG 결제 ID 넣어 줌
    public function updatePayId(PayHistoryModel $payHistory) 
    {
        $query = 
            " update pay id in pay history query ";
                        
        $param = [];

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        return $this->db->run($query, $param);
    }

    // 결제 히스토리 저장
    public function insert(PayHistoryModel $payHistory) 
    {
        $query = " insert pay history query ";
        $param = [];

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));
        
        $re = $this->db->run($query, $param);
    
        return $this->db->get_last_insert_id();
    }

    // 결제 이력 취소 처리 - pay_type = 5, memeo = '카드 결제 취소'로 처리
    public function cancelPayment(PayHistoryModel $payHistory) 
    {
        $query = " update pay history for pay cancel query ";
        $param = [];

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        return $this->db->run($query, $param);
    }

    // PayId로 결제 이력 가져오기
    public function selectByPayId($payId) 
    {
        $query = 
            " select payId from pg query ";
                        
        $param = [];

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        $result = $this->db->run($query, $param);
        
        $returnModel = (new PayHistoryModel())
            ->setId($result[0]['id']);

            $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] return model : ". (string)$returnModel);

        return $returnModel;
    }

    // 결제 완료로 변경
    public function updatePaySuccess(PayHistoryModel $payHistory) 
    {
        $query = 
            " update pay success process to pay history ";
                        
        $param = [];

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] $query , parameter : ". json_encode($param));

        return $this->db->run($query, $param);
    }
}