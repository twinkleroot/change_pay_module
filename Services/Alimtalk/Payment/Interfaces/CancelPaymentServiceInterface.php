<?php
namespace App\Services\Alimtalk\Payment\Interfaces;

use App\Models\Alimtalk\PayHistoryModel;

interface CancelPaymentServiceInterface 
{
    public function cancelPayment(PayHistoryModel $payhistory);
}