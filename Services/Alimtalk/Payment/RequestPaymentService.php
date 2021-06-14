<?php
namespace App\Services\Alimtalk\Payment;

use App\Common\DiContainer;
use App\Common\Util;
use App\Common\LogLevel;
use App\Common\ResultCode;
use App\Models\Alimtalk\PayHistoryModel;
use App\Services\Alimtalk\Payment\Interfaces\RequestPaymentServiceInterface;

class RequestPaymentService implements RequestPaymentServiceInterface 
{
    protected $payHistoryRepository;
    protected $url;
    protected $logger;
    protected $logMessagePrefix;

    public function __construct(DiContainer $container, $url) 
    {
        $this->logger = $container->get('Logger');
        $this->payHistoryRepository = $container->get('PayHistoryRepository');
        try {
            $this->payHistoryRepository->setDbConnection($container->get('DbConnectionFactory')->create()->getCafe03Db());
        } catch(\Exception $e) {
            throw new \Exception("[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] msg :  ".$e->getMessage(), ResultCode::DB_CONNECTION_ERROR()->__toString());
        }
        $this->url = $url;
        $this->loggingClassName = "[".Util::GetClassName($this)."]"; 
    }

    // 결제 이력 입력
    public function insertPayHistory(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Insert to pay_history about pay info.");
        $lastInsertId = 0;
        try {
            $lastInsertId = $this->payHistoryRepository->insert($payHistory);
        } catch (\PDOException $pe) {
            $msg = "Failure to insert pay history";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_INSERT_PAY_HISTORY()->__toString());
        }

        return $lastInsertId;
    }

    // PG API PayId 발급요청 (결제 기본 정보 등록)
    public function requestPayId(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Request PayId to PG.");
        $payId = '';
        $api_url = $this->url. "requestPayId";
        $data = array(
             'Type' => $payHistory->getType()
            ,'PaymentCode' => 2 // 고정 값
            ,'OrderNumber' => $payHistory->getId()
            ,'Amount' => $payHistory->getTotalPrice()
            ,'Name' => $_SESSION['id']  // 업체 ID
            ,'Email'=> $_SESSION['email']
            ,'TelNumber' => $_SESSION['tel'] // 업체 전화번호
        );

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Request PayId Data.". json_encode($data));

        $response = json_decode(CURL_POST($api_url, $data));

        if(!empty($response) && $response->ResultCode == 0) {
            $payId = $response->PayId;
            $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Received PayId From PG. PayId : ". $payId);
        } else {
            throw new \Exception("Failure to pay request", ResultCode::FAIL_REQUEST_PAY_ID()->__toString());
        }

        return $payId;
    }

    // 저장된 결제 이력에 PayId 업데이트
    public function updatePayId(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Update PayId to pay_history DB. PayId : ". $payHistory->getPayId());
        $count = 0;
        try {
            $count = $this->payHistoryRepository->updatePayId($payHistory);
        } catch (\PDOException $pe) {
            $msg = "Failure to update pay Id";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_UPDATE_PAY_ID()->__toString());
        }

        return $count;
    }
}