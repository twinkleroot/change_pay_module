<?php
namespace App\Services\Alimtalk\Payment\Interfaces;

use App\Models\Alimtalk\PayHistoryModel;

interface CompletePaymentServiceInterface 
{
    public function getPayHistoryByPayId($payId);
    public function requestPayStatus(PayHistoryModel $payHistory);
    public function addRemainCount(PayHistoryModel $payHistory);
    public function applyMonthlyService(PayHistoryModel $payHistory);
    public function updatePaySuccess(PayHistoryModel $payHistory);
    public function sendResultSms(PayHistoryModel $payHistory);
}