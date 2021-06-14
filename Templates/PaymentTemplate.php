<?php
namespace App\Templates;

use App\Common\Util;
use App\Common\DiContainer;
use App\Common\Request;
use App\Common\LogLevel;
use App\Common\ResultCode;
use App\Models\Alimtalk\PayHistoryModel;

class PaymentTemplate 
{
    protected $logger;
    protected $payHistoryFactory;
    protected $requsetPaymentService;
    protected $completePaymentService;
    protected $cancelPaymentService;
    protected $serviceType;

    public function __construct(DiContainer $container, $serviceType) 
    {
        $this->logger = $container->get('Logger');
        $this->payHistoryFactory = $container->get('PayHistoryFactory');
        $this->requsetPaymentService = $container->get('RequestPaymentService');
        $this->completePaymentService = $container->get('CompletePaymentService');
        $this->cancelPaymentService = $container->get('CancelPaymentService');
        $this->serviceType = $serviceType;
    }

    // 결제 요청 - PG PAY ID 발급
    public function requestPayment(Request $request) 
    {
        $result = array('msg' => 'fail');
        $payHistory = $this->payHistoryFactory->create($request);
        // 1. DB에 결제 이력 저장
        $orderId = $this->requsetPaymentService->insertPayHistory($payHistory);
        if($orderId) { // 1. DB에 결제 이력이 잘 들어갔을 때
            // 2. PG API PayId 발급요청 (결제 기본 정보 등록)
            $payHistory->setId($orderId);
            $payId = $this->requsetPaymentService->requestPayId($payHistory);
    
            if($payId) {
                $payHistory->setPayId($payId);
                $updateCount = $this->requsetPaymentService->updatePayId($payHistory);
                
                if($updateCount) {
                    $result['msg'] = 's';
                    $result['pay_id'] = $payId;
                }
            }
        }
        
        return $result;
    }

    // 결제 완료 후 처리
    public function completePayment(Request $request) 
    {
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Callback pg - Request : ". json_encode($request->get()));
        
        $result = array();
        $req = $request->get();
        $payId = $req->PayId;
        $confirmDate = $req->ConfirmDate;
        
        // 1. 저장된 결제 이력 가져오기 by PayId
        $payHistory = $this->completePaymentService->getPayHistoryByPayId($payId);
        $payHistory->setServiceType($this->serviceType);
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] PayHistory model : ". (string)$payHistory);
                
        // 2. 결제 상태 조회 후 분기
        if ($this->completePaymentService->requestPayStatus($payHistory)) {
            // 3-1. (업데이트전) 알림톡 남은 갯수 가져 온다.
            $userInfo = $this->completePaymentService->getRemainCount();
            // 3-2. 알림톡 남은 갯수 올려 준다.
            $this->completePaymentService->addRemainCount($payHistory);
            // 3-3. (업데이트후) 알림톡 남은 갯수 가져 온다.
            $userInfo = $this->completePaymentService->getRemainCount();

            // 4. shop에 월간 결제 적용
            if( strlen($payHistory->getMonthlyFee()) > 0 ) {
                $this->completePaymentService->applyMonthlyService($payHistory);
            }

            // 5. 결제 완료 처리
            $payHistory->setConfirmDate($confirmDate);
            $isUpdate = $this->completePaymentService->updatePaySuccess($payHistory);
            if ($isUpdate) {
                // 6. 결제 결과 문자 발송
                $sendSmsIsSuccess = false;
                if(!empty($_SESSION['tel_num'])) {
                    $sendSmsIsSuccess = $this->completePaymentService->sendResultSms($payHistory);
                }
                
                if($sendSmsIsSuccess === false) {
                    $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Fail Sending Sms. PayId : ". $payId);
                }

                $result['code'] = ResultCode::SUCCESS()->getValue();
                $result['msg'] = '결제에 성공하였습니다.';
            }
        } else {
            // PayId 불일치 및 잘못된 접근이므로 fail process
            $result = $this->cancelPayment($request);
            throw new \Exception($result['msg'], ResultCode::NOT_EQUAL_PAY_ID()->__toString());
        }

        return $result;
    }

    // PG에서 결제 실패, 결제 중단 후 처리
    public function cancelPayment(Request $request) 
    {
        $payId = $request->get()->PayId;
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Fail payment with credit card. Start Cancel(Fail) Process. payId : ". $payId);
        // 결제 이력 취소 처리
        $this->cancelPaymentService->cancelPayment(
            (new PayHistoryModel())->setPayId($payId)
        );

        return array(
            'code' => ResultCode::FAIL()->getValue(),  // 결제 실패
            'msg' => '결제에 실패하였습니다.'
        );
    }
}