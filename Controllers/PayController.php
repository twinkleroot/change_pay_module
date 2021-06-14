<?php
namespace App\Controllers;

use App\Common\DiContainer;
use App\Common\Request;
use App\Common\Util;
use App\Common\LogLevel;
use App\Common\ResultCode;

// 알림톡 카드결제 컨트롤러
class PayController 
{
    protected $postRequest;
    protected $getRequest;
    protected $paymentTemplate;
    protected $logger;

    public function __construct(DiContainer $container, Request $postRequest, Request $getRequest) 
    {
        $this->postRequest = $postRequest;
        $this->getRequest = $getRequest;
        $this->paymentTemplate = $container->get('PaymentTemplate');
        $this->logger = $container->get('Logger');
    }

    public function action($action) 
    {
        $func = Util::Camelize($action);
        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".$func."] : [".Util::GetUserId()."] Start $action");
        
        if(!method_exists($this, $func)) {
            $func = 'nothing';
        }
        
        return $this->$func();
    }

    // 결제 요청
    private function requestPayment() 
    {
        try {
            return $this->paymentTemplate->requestPayment($this->postRequest);
        } catch(\Exception $e) {
            throw $e;
        }
    }

    // 결제 성공 후 처리
    private function completePayment() 
    {
        try {
            return $this->paymentTemplate->completePayment($this->getRequest);
        } catch(\Exception $e) {
            throw $e;
        }
    }

    // 결제 실패 후 처리
    private function cancelPayment() 
    {
        try {
            return $this->paymentTemplate->cancelPayment($this->getRequest);
        } catch(\Exception $e) {
            throw $e;
        }
    }

    // action 명이 없는 메서드 요청일 떄의 처리
    private function nothing() 
    {
        return array(
            'msg' => 'There is nothing to do.',
            'code' => ResultCode::FAIL()->__toString()
        );
    }
}