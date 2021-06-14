<?php
namespace App\Services\Alimtalk\Payment\Interfaces;

use App\Models\Alimtalk\PayHistoryModel;

interface RequestPaymentServiceInterface 
{
    public function insertPayHistory(PayHistoryModel $payHistory);
    public function requestPayId(PayHistoryModel $payHistory);
    public function updatePayId(PayHistoryModel $payHistory);
}