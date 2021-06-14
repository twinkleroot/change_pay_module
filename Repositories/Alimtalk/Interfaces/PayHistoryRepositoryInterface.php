<?php
namespace App\Repositories\Alimtalk\Interfaces;

use App\Models\Alimtalk\PayHistoryModel;
use App\Repositories\Alimtalk\Interfaces\BaseRepositoryInterface;

interface PayHistoryRepositoryInterface extends BaseRepositoryInterface 
{
    public function cancelPayment(PayHistoryModel $payHistory);
    public function selectByPayId($payId);
    public function updatePayId(PayHistoryModel $payHistory);
    public function insert(PayHistoryModel $payHistory);
    public function updatePaySuccess(PayHistoryModel $payHistory);
}