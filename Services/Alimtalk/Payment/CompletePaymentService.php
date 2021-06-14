<?php
namespace App\Services\Alimtalk\Payment;

use App\Common\DiContainer;
use App\Common\Util;
use App\Common\ResultCode;
use App\Common\LogLevel;
use App\Models\Alimtalk\PayHistoryModel;
use App\Models\Alimtalk\ShopModel;
use App\Services\Alimtalk\Payment\Interfaces\CompletePaymentServiceInterface;

class CompletePaymentService implements CompletePaymentServiceInterface 
{
    protected $payHistoryRepository;
    protected $userInfoRepository;
    protected $shopRepository;
    protected $smsSender;
    protected $logger;
    protected $url;

    public function __construct(DiContainer $container, $url) 
    {
        // set repository
        $this->payHistoryRepository = $container->get('PayHistoryRepository');
        $this->userInfoRepository = $container->get('UserInfoRepository');
        $this->shopRepository = $container->get('ShopRepository');
        // db connection
        try {
            $dbConnections = $container->get('DbConnectionFactory')->create();
            $this->payHistoryRepository->setDbConnection($dbConnections->getAlimtalkDb());
            $this->userInfoRepository->setDbConnection($dbConnections->getUserDb());
            $this->shopRepository->setDbConnection($dbConnections->getUserDb());
        } catch(\Exception $e) {
            throw new \Exception("[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] msg : ".$e->getMessage(), ResultCode::DB_CONNECTION_ERROR()->__toString());
        }
        // set sms sender
        $this->smsSender = $container->get('SmsSender');
        // set logger
        $this->logger = $container->get('Logger');
        // set pg api url
        $this->url = $url;
    }

    // 1. pay_id로 저장된 결제 이력 가져오기
    public function getPayHistoryByPayId($payId) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Get pay_history by PayId.");
        $re = "";
        try {
            $re = $this->payHistoryRepository->selectByPayId($payId);
        } catch (\PDOException $pe) {
            $msg = "Failure to select pay_history";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_SELECT_PAY_HISTORY()->__toString());
        }

        return $re;
    }
    
    // 2. 결제 상태 조회
    public function requestPayStatus(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Request payment status from PayId. PayId : ". $payHistory->getPayId());

        $url = $this->url. "requestPayStatus";
        $data = array(
            'Type' => $payHistory->getType(),
            'PayId' => $payHistory->getPayId()
        );
        $response = json_decode(CURL_POST($url, $data));

        return (!empty($response) && $response->ResultCode == 0 && $response->PayStatus == 2 && $response->PayId == $payHistory->getPayId());
    }

    // 3-1,3. 알림톡 남은 갯수 가져온다.
    public function getRemainCount() 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Get remain alimtalk count ");
        try {
            $userInfo = $this->userInfoRepository->getRemainCount();
            $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Current alimtalk remain_cnt  : ". $userInfo->getRemainCnt());
        } catch (\PDOException $pe) {
            $msg = "Failure to get remain_cnt of user_Info";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_SELECT_REMAIN_CNT()->__toString());
        }
    }

    // 3-2. 알림톡 남은 갯수 올려 준다.
    public function addRemainCount(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Plus remain alimtalk count : ". $payHistory->getOrderQty(). " PayId : ". $payHistory->getPayId());
        try {
            $this->userInfoRepository->updateRemainCount($payHistory);
        } catch (\PDOException $pe) {
            $msg = "Failure to update remain_cnt of user_Info";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_UPDATE_REMAIN_CNT()->__toString());
        }
    }

    // 4. shop에 월간 결제 적용
    public function applyMonthlyService(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Apply monthly service : ". $payHistory->getMonthlyPrice(). " PayId : ". $payHistory->getPayId());
        try {
            $fees = json_decode(str_replace("&quot;","\"",$payHistory->getMonthlyPrice()), true);
    
            foreach($fees as $shopId => $month) {
                $shop = new ShopModel();
                $shop->setShopId($shopId);
                $shop = $this->shopRepository->selectServiceLifeInfo($shop);
    
                $oldPayEnd = max($shop->getPayEnd(), $shop->getCheckPayEnd());
                $newPayEnd = date('Y-m-d', strtotime("+$month months", strtotime($oldPayEnd)));
                $shop->setNewPayEnd($newPayEnd);
    
                $this->shopRepository->applyMonthlyService($shop);
            }
        } catch (\PDOException $pe) {
            $msg = "Failure to apply monthly service";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_UPDATE_MONTHLY_SERVICE()->__toString());
        }
    }

    // 5. 결제 완료 처리
    public function updatePaySuccess(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Update pay with credit card to success. PayId : ". $payHistory->getPayId());
        $re = "";
        try {
            $re = $this->payHistoryRepository->updatePaySuccess($payHistory);
        } catch (\PDOException $pe) {
            $msg = "Failure to update pay status";
            throw new \Exception($msg . " " . $pe->getMessage() . " ", ResultCode::FAIL_UPDATE_PAY_STATUS()->__toString());
        }

        return $re;
    }

    // 6. 결제 결과 문자 발송
    public function sendResultSms(PayHistoryModel $payHistory) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Send result sms to ". $_SESSION['tel_num']. " PayId : ". $payHistory->getPayId());
        $par = array();
        $par['type']            = 'mms';
        $par['is_cut']          = '0';
        $par['process']         = '카드결제';
        $par['sender']          = '11112222';
        $par['receiver_no']     = $_SESSION['tel_num'];
        $par['receiver_name']   = $_SESSION['id'];
        $par['subject']         = '알림톡 결제';
        $par['content']         = "카드 결제가 완료되었습니다.\n";
        $par['content']         .= "결제하신 금액:" . number_format($payHistory->getTotalPrice())." 원 (부가세 포함).\n";
        $par['content']         .= '감사합니다.';
        $par['file'] = null;

        return $this->smsSender->send($par);
    }

}
