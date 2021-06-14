<?php
namespace App\Services\Alimtalk\Payment;

use App\Common\DiContainer;
use App\Common\Util;
use App\Common\LogLevel;
use App\Common\ResultCode;
use App\Models\Alimtalk\PayHistoryModel;
use App\Services\Alimtalk\Payment\Interfaces\CancelPaymentServiceInterface;

class CancelPaymentService implements CancelPaymentServiceInterface 
{
    protected $payHistoryRepository;
    protected $url;
    protected $logger;

    public function __construct(DiContainer $container, $url) 
    {
        // set repository from container
        $this->payHistoryRepository = $container->get('PayHistoryRepository');
        // db connection from container
        try {
            $this->payHistoryRepository->setDbConnection($container->get('DbConnectionFactory')->create()->getCafe03Db());
        } catch(\Exception $e) {
            throw new \Exception("[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] msg : ".$e->getMessage(), ResultCode::DB_CONNECTION_ERROR()->__toString());
        }
        // set logger from container
        $this->logger = $container->get('Logger');
        // set pg api url
        $this->url = $url;
    }

    // PG에서 결제 실패, 결제 중단 후 처리
    public function cancelPayment(PayHistoryModel $payhistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Cancel(Fail) process for payment with credit card. PayId : ". $payhistory->getPayId());
        $re = "";
        try {
            $re = $this->payHistoryRepository->cancelPayment($payhistory);
        } catch (\PDOException $pe) {
            $msg = "Failure to cancel payment history";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_DELETE_PAY_HISTORY()->__toString());
        }

        return $re;
    }
}